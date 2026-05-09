<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendLaporanReminder extends Command
{
    protected $signature = 'laporan:send-reminders';
    protected $description = 'Kirim reminder untuk laporan yang belum selesai';

    protected $telegramService;
    protected $statusToRemind = ['open', 'process', 'escalate', 'pending'];

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    public function handle()
    {
        $this->info('Memulai pengiriman reminder...');

        try {
            $laporans = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])
                ->whereIn('status', $this->statusToRemind)
                ->get();

            $this->info('Ditemukan ' . $laporans->count() . ' laporan yang perlu dicek');

            if ($laporans->isEmpty()) {
                $this->info('Tidak ada laporan yang perlu diingatkan');
                return Command::SUCCESS;
            }

            // Tampilkan detail laporan
            foreach ($laporans as $laporan) {
                $this->line(" - Tiket: {$laporan->nomor_ticket}, Status: {$laporan->status}, ID: {$laporan->id}");
            }

            $sentCount = 0;

            foreach ($laporans as $laporan) {
                $shouldSend = $this->shouldSendReminder($laporan);
                $this->line("Tiket {$laporan->nomor_ticket}: shouldSend=" . ($shouldSend ? 'YES' : 'NO'));

                if ($shouldSend) {
                    $this->sendReminderMessage($laporan);
                    $this->updateReminderCache($laporan);
                    $sentCount++;
                    $this->info("✓ Reminder terkirim untuk tiket: {$laporan->nomor_ticket}");
                    usleep(500000); // 0.5 detik
                }
            }

            $this->info("Reminder terkirim: {$sentCount} laporan");
            Log::info("Reminder scheduler selesai - Terkirim: {$sentCount} laporan");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Error di reminder scheduler: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function shouldSendReminder($laporan)
    {
        $reminderKey = 'reminder_' . $laporan->id;
        $lastSent = Cache::get($reminderKey);

        // SEMUA STATUS: INTERVAL 2 MENIT UNTUK TESTING
        $intervalMinutes = 2; // 2 menit

        $this->line("  Last sent: " . ($lastSent ? $lastSent->format('H:i:s') : 'NEVER'));
        $this->line("  Interval: {$intervalMinutes} menit");

        if (!$lastSent) {
            $this->line("  → Akan dikirim (belum pernah dikirim)");
            return true;
        }

        $minutesSince = $lastSent->diffInMinutes(now());
        $this->line("  Menit terakhir: {$minutesSince} menit yang lalu");

        $shouldSend = $minutesSince >= $intervalMinutes;
        $this->line("  → " . ($shouldSend ? "AKAN DIKIRIM" : "BELUM waktunya"));

        return $shouldSend;
    }

    protected function updateReminderCache($laporan)
    {
        // INTERVAL 2 MENIT UNTUK TESTING
        $intervalMinutes = 2;

        // Simpan sebagai menit (2 menit = 120 detik)
        Cache::put('reminder_' . $laporan->id, now(), $intervalMinutes * 60);

        $countKey = 'reminder_count_' . $laporan->id;
        $count = Cache::get($countKey, 0);
        Cache::put($countKey, $count + 1, 86400);

        $this->line("  Cache updated - Next reminder in {$intervalMinutes} minutes");
    }

    protected function sendReminderMessage($laporan)
    {
        $statusEmoji = [
            'open' => '🟡 OPEN',
            'process' => '🔵 PROCESS',
            'pending' => '🟠 PENDING',
            'escalate' => '🔴 ESCALATE'
        ];

        $statusText = $statusEmoji[$laporan->status] ?? strtoupper($laporan->status);

        $hoursSince = floor(now()->diffInHours($laporan->created_at));
        $daysSince = floor(now()->diffInDays($laporan->created_at));

        $reminderCount = Cache::get('reminder_count_' . $laporan->id, 0) + 1;

        $detailUrl = env('APP_URL') . '/laporan/' . $laporan->id;
        $editUrl = env('APP_URL') . '/laporan/' . $laporan->id . '/edit';

        $message = "<b>⏰ PENGINGAT STATUS LAPORAN (TESTING - 2 MENIT)</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "<b>⚠️ PERHATIAN!</b>\n";
        $message .= "Laporan berikut masih dalam proses:\n\n";
        $message .= "<b>🆔 Tiket:</b> <code>{$laporan->nomor_ticket}</code>\n";
        $message .= "<b>📊 Status:</b> {$statusText}\n";
        $message .= "<b>📅 Dibuat:</b> " . $laporan->created_at->format('d/m/Y H:i') . " WIB\n";

        if ($daysSince > 0) {
            $message .= "<b>⏱️ Durasi:</b> {$daysSince} hari, {$hoursSince} jam\n";
        } else {
            $message .= "<b>⏱️ Durasi:</b> {$hoursSince} jam\n";
        }

        $message .= "<b>🔄 Frekuensi:</b> Setiap 2 menit (TESTING)\n";
        $message .= "<b>👤 Pelapor:</b> {$laporan->nama_pelapor}\n";
        $message .= "<b>🏢 Kantor:</b> " . ($laporan->kantor->nama_cabang ?? 'N/A') . "\n";
        $message .= "<b>📱 Aplikasi:</b> " . ($laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A') . "\n";
        $message .= "<b>📦 Produk:</b> " . ($laporan->produk->nama_produk ?? 'N/A') . "\n\n";
        $message .= "<b>📢 Pengingat ke-{$reminderCount}</b>\n\n";

        if ($laporan->status == 'pending' && $laporan->pending_deskripsi) {
            $message .= "<b>⏳ Alasan Pending:</b>\n";
            $message .= "└ " . wordwrap($laporan->pending_deskripsi, 50, "\n  ") . "\n\n";
        }

        if ($laporan->status == 'escalate' && $laporan->escalate_deskripsi) {
            $message .= "<b>⬆️ Alasan Escalate:</b>\n";
            $message .= "└ " . wordwrap($laporan->escalate_deskripsi, 50, "\n  ") . "\n\n";
        }

        // Ringkasan kronologi singkat
        $ringkasanKronologi = strlen($laporan->kronologi) > 100
            ? substr($laporan->kronologi, 0, 100) . '...'
            : $laporan->kronologi;
        $message .= "<b>📝 Ringkasan Kronologi:</b>\n";
        $message .= "└ " . wordwrap($ringkasanKronologi, 50, "\n  ") . "\n\n";

        $message .= "<b>🎯 Action:</b>\n";
        $message .= "<a href='{$detailUrl}'>🔍 Lihat Detail</a> | ";
        $message .= "<a href='{$editUrl}'>✏️ Update Status</a>\n\n";

        if ($hoursSince >= 24) {
            $message .= "<b>⚠️ URGENT!</b>\nLaporan ini sudah lebih dari 24 jam belum terselesaikan!\n\n";
        }

        if ($laporan->status == 'escalate' && $hoursSince >= 48) {
            $message .= "<b>🔴 KRITIS!</b>\nLaporan dengan status ESCALATE sudah lebih dari 48 jam!\n\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "<i>⚠️ Mode TESTING - Pengingat setiap 2 menit</i>\n";
        $message .= "<i>🤖 Pengingat otomatis dari sistem E-Ticketing</i>";

        $result = $this->telegramService->sendMessage($message);

        if ($result) {
            $this->info("  ✅ Pesan Telegram berhasil dikirim");
            Log::info("Reminder otomatis dikirim untuk tiket: {$laporan->nomor_ticket} (ke-{$reminderCount})");
        } else {
            $this->error("  ❌ Gagal mengirim pesan Telegram");
            Log::error("Gagal kirim reminder untuk tiket: {$laporan->nomor_ticket}");
        }
    }
}

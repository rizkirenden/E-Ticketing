<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $chatId;
    protected $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send message to Telegram
     */
    public function sendMessage($message, $parseMode = 'HTML')
    {
        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true
            ]);

            if ($response->failed()) {
                Log::error('Telegram API Error: ' . $response->body());
                return false;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram Send Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send document/file to Telegram
     */
    public function sendDocument($filePath, $caption = '')
    {
        try {
            // Pastikan file exists di storage
            if (!file_exists($filePath)) {
                Log::error('File not found: ' . $filePath);
                return false;
            }

            $response = Http::attach(
                'document',
                file_get_contents($filePath),
                basename($filePath)
            )->post("{$this->apiUrl}/sendDocument", [
                'chat_id' => $this->chatId,
                'caption' => $caption
            ]);

            if ($response->failed()) {
                Log::error('Telegram Document Error: ' . $response->body());
                return false;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram Send Document Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send photo to Telegram
     */
    public function sendPhoto($filePath, $caption = '')
    {
        try {
            if (!file_exists($filePath)) {
                Log::error('File not found: ' . $filePath);
                return false;
            }

            $response = Http::attach(
                'photo',
                file_get_contents($filePath),
                basename($filePath)
            )->post("{$this->apiUrl}/sendPhoto", [
                'chat_id' => $this->chatId,
                'caption' => $caption
            ]);

            if ($response->failed()) {
                Log::error('Telegram Photo Error: ' . $response->body());
                return false;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram Send Photo Error: ' . $e->getMessage());
            return false;
        }
    }

     /**
     * Format laporan message untuk Telegram
     */
    public function formatLaporanMessage($laporan)
    {
        // Status emoji
        $statusEmoji = [
            'open' => '🟡',
            'process' => '🔵',
            'done' => '🟢',
            'reject' => '🔴',
            'pending' => '🟠',
            'escalate' => '🔴'
        ];

        $emoji = $statusEmoji[$laporan->status] ?? '📋';

        // Parse nomor ticket untuk mendapatkan komponen
        $ticketParts = explode('-', $laporan->nomor_ticket);
        $kodeAplikasi = $ticketParts[0] ?? 'N/A';
        $kodeProduk = $ticketParts[1] ?? 'N/A';
        $tanggalTicket = $ticketParts[3] ?? 'N/A';
        $urutanTicket = $ticketParts[4] ?? 'N/A';

        $message = "<b>📋 LAPORAN BARU</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n\n";

        $message .= "<b>🆔 NOMOR TICKET:</b>\n";
        $message .= "<code>{$laporan->nomor_ticket}</code>\n";

        $message .= "<b>📊 STATUS:</b> {$emoji} <b>{$laporan->status}</b>\n\n";

        $message .= "<b>👤 DATA PELAPOR:</b>\n";
        $message .= "├ Nama: <b>{$laporan->nama_pelapor}</b>\n";
        $message .= "├ No. HP: <code>{$laporan->no_handphone}</code>\n";
        $message .= "└ Kantor: {$laporan->kantor->nama_cabang} ({$laporan->kantor->kode_cabang})\n\n";

        $message .= "<b>📱 DETAIL APLIKASI & PRODUK:</b>\n";
        $message .= "├ Aplikasi: <b>{$laporan->jenisAplikasi->jenis_aplikasi}</b>\n";
        $message .= "├ Kode Aplikasi: <code>{$laporan->jenisAplikasi->kode_jenis_aplikasi}</code>\n";
        $message .= "├ Produk: <b>{$laporan->produk->nama_produk}</b>\n";
        $message .= "└ Kode Produk: <code>{$laporan->produk->kode_produk}</code>\n\n";

        $message .= "<b>📝 KRONOLOGI:</b>\n";
        $message .= "└ " . wordwrap($laporan->kronologi, 50, "\n  ") . "\n\n";

        $message .= "<b>📅 TANGGAL LAPORAN:</b>\n";
        $message .= "└ " . $laporan->tanggal_laporan->format('d/m/Y H:i') . " WIB\n\n";

        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "<a href='" . route('laporan.show', $laporan->id) . "'>🔗 Klik untuk lihat detail</a>\n";
        $message .= "<i>E-Ticketing System</i>";

        return $message;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use App\Models\Kantor;
use App\Models\Jenisaplikasi;
use App\Services\TelegramService;
use App\Helpers\RoleAplikasiHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SendMonthlyRecap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:monthly-recap
                            {--month= : Bulan (format: Y-m)}
                            {--send-to-telegram : Kirim ke Telegram}
                            {--save-only : Hanya simpan PDF tanpa kirim}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim rekap laporan bulanan dalam format PDF';

    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Tentukan bulan yang akan direkap
            $targetDate = $this->option('month')
                ? Carbon::parse($this->option('month') . '-01')
                : Carbon::now()->subMonth(); // Default: bulan lalu

            $startDate = $targetDate->copy()->startOfMonth();
            $endDate = $targetDate->copy()->endOfMonth();

            $this->info("Membuat rekap laporan bulan: " . $targetDate->format('F Y'));
            $this->info("Periode: " . $startDate->format('d/m/Y') . " - " . $endDate->format('d/m/Y'));

            // Cek apakah sudah ada laporan di bulan tersebut
            $totalLaporan = Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->count();

            if ($totalLaporan == 0 && !$this->option('save-only')) {
                $this->warn("Tidak ada laporan pada bulan " . $targetDate->format('F Y'));

                // Kirim notifikasi bahwa tidak ada laporan
                $this->sendEmptyReportNotification($targetDate);
                return Command::SUCCESS;
            }

            // Generate PDF Recap
            $pdfPath = $this->generateMonthlyRecapPdf($startDate, $endDate, $targetDate);

            if ($this->option('save-only')) {
                $this->info("PDF tersimpan di: " . $pdfPath);
                return Command::SUCCESS;
            }

            // Kirim ke Telegram
            if ($this->option('send-to-telegram') || !$this->option('save-only')) {
                $this->sendToTelegram($pdfPath, $targetDate, $totalLaporan);
            }

            $this->info("Rekap bulanan berhasil dikirim!");

            // Clean up old PDF files (lebih dari 3 bulan)
            $this->cleanupOldPdfs();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Error sending monthly recap: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());

            // Kirim notifikasi error ke Telegram
            $this->sendErrorNotification($e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Generate monthly recap PDF
     */
    private function generateMonthlyRecapPdf($startDate, $endDate, $targetDate)
    {
        // Ambil data laporan berdasarkan filter
        $query = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])
            ->whereBetween('tanggal_laporan', [$startDate, $endDate]);

        $laporans = $query->orderBy('tanggal_laporan', 'desc')->get();

        // Statistik per aplikasi
        $statPerAplikasi = [];
        $statPerStatus = [];
        $statPerKantor = [];
        $statPerHari = [];

        // DAFTAR SEMUA STATUS YANG MUNGKIN ADA
        $allStatuses = [
            'open' => 'Open',
            'process' => 'Process',
            'done' => 'Done',
            'reject' => 'Reject',
            'pending' => 'Pending',
            'escalate' => 'Escalate'
        ];

        // Inisialisasi semua status dengan nilai 0
        foreach ($allStatuses as $key => $label) {
            $statPerStatus[$label] = 0;
        }

        foreach ($laporans as $laporan) {
            // Per aplikasi
            $aplikasi = $laporan->jenisAplikasi->jenis_aplikasi ?? 'Tidak Diketahui';
            if (!isset($statPerAplikasi[$aplikasi])) {
                $statPerAplikasi[$aplikasi] = 0;
            }
            $statPerAplikasi[$aplikasi]++;

            // Per status - menggunakan label yang sudah terdefinisi
            $statusKey = strtolower($laporan->status);
            $statusLabel = $allStatuses[$statusKey] ?? ucfirst($laporan->status);
            $statPerStatus[$statusLabel]++;

            // Per kantor
            $kantor = $laporan->kantor->nama_cabang ?? 'Tidak Diketahui';
            if (!isset($statPerKantor[$kantor])) {
                $statPerKantor[$kantor] = 0;
            }
            $statPerKantor[$kantor]++;

            // Per hari
            $hari = $laporan->tanggal_laporan->format('Y-m-d');
            if (!isset($statPerHari[$hari])) {
                $statPerHari[$hari] = 0;
            }
            $statPerHari[$hari]++;
        }

        // Sort arrays
        arsort($statPerAplikasi);
        arsort($statPerKantor);
        ksort($statPerHari);

        // Untuk status, urutkan berdasarkan definisi
        $orderedStatus = [];
        foreach ($allStatuses as $label) {
            if (isset($statPerStatus[$label])) {
                $orderedStatus[$label] = $statPerStatus[$label];
            }
        }
        $statPerStatus = $orderedStatus;

        // ============================================
        // PERBAIKAN: Hitung jumlah hari berdasarkan KALENDER INDONESIA
        // Menggunakan daysInMonth untuk mendapatkan jumlah hari yang benar
        // ============================================
        $jumlahHari = $targetDate->daysInMonth; // 31, 28, 29, 30, dll sesuai kalender
        $rataPerHari = $laporans->count() > 0 ? round($laporans->count() / $jumlahHari, 2) : 0;

        // Waktu penyelesaian rata-rata (untuk laporan yang sudah done)
        $doneLaporans = $laporans->where('status', 'done')
            ->filter(function($laporan) {
                return $laporan->tanggal_selesai != null;
            });

        $avgCompletionTime = 0;
        if ($doneLaporans->count() > 0) {
            $totalHours = 0;
            foreach ($doneLaporans as $laporan) {
                $hours = $laporan->tanggal_laporan->diffInHours($laporan->tanggal_selesai);
                $totalHours += $hours;
            }
            $avgCompletionTime = round($totalHours / $doneLaporans->count(), 1);
        }

        // Data untuk chart (JSON)
        $chartData = [
            'labels' => array_keys($statPerHari),
            'data' => array_values($statPerHari),
            'aplikasi_labels' => array_keys($statPerAplikasi),
            'aplikasi_data' => array_values($statPerAplikasi),
            'status_labels' => array_keys($statPerStatus),
            'status_data' => array_values($statPerStatus)
        ];

        // Hitung statistik tambahan untuk dashboard
        $stats = [
            'total' => $laporans->count(),
            'open' => $statPerStatus['Open'] ?? 0,
            'process' => $statPerStatus['Process'] ?? 0,
            'done' => $statPerStatus['Done'] ?? 0,
            'reject' => $statPerStatus['Reject'] ?? 0,
            'pending' => $statPerStatus['Pending'] ?? 0,
            'escalate' => $statPerStatus['Escalate'] ?? 0,
            'completion_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Done'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
            'open_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Open'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
            'process_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Process'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
            'reject_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Reject'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
            'pending_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Pending'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
            'escalate_rate' => $laporans->count() > 0
                ? round((($statPerStatus['Escalate'] ?? 0) / $laporans->count()) * 100, 1)
                : 0,
        ];

        $data = [
            'bulan' => $targetDate->format('F Y'),
            'bulan_indonesia' => $this->getIndonesianMonth($targetDate->month),
            'tahun' => $targetDate->year,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_laporan' => $laporans->count(),
            'jumlah_hari' => $jumlahHari,
            'rata_per_hari' => $rataPerHari,
            'avg_completion_time' => $avgCompletionTime,
            'stat_per_aplikasi' => $statPerAplikasi,
            'stat_per_status' => $statPerStatus,
            'stat_per_kantor' => $statPerKantor,
            'stat_per_hari' => $statPerHari,
            'chart_data' => $chartData,
            'stats' => $stats,
            'laporans' => $laporans->take(20),
            'generate_date' => Carbon::now(),
            'generated_by' => 'System Auto Recap'
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.monthly-recap', $data);
        $pdf->setPaper('A4', 'landscape');

        // Simpan PDF
        $filename = 'monthly_recap_' . $targetDate->format('Y_m') . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        $path = 'recaps/' . $targetDate->format('Y') . '/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        $this->info("PDF generated: " . $path);
        $this->info("Jumlah hari dalam periode: {$jumlahHari} hari");

        return Storage::disk('public')->path($path);
    }

    /**
     * Send PDF to Telegram
     */
    private function sendToTelegram($pdfPath, $targetDate, $totalLaporan)
    {
        try {
            $monthName = $this->getIndonesianMonth($targetDate->month);
            $year = $targetDate->year;

            // Ambil statistik lengkap
            $startDate = $targetDate->copy()->startOfMonth();
            $endDate = $targetDate->copy()->endOfMonth();

            $stats = [
                'open' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'open')->count(),
                'process' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'process')->count(),
                'done' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'done')->count(),
                'reject' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'reject')->count(),
                'pending' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'pending')->count(),
                'escalate' => Laporan::whereBetween('tanggal_laporan', [$startDate, $endDate])->where('status', 'escalate')->count(),
            ];

            $caption = "<b>📊 LAPORAN REKAP BULANAN</b>\n";
            $caption .= "━━━━━━━━━━━━━━━━━━━\n\n";
            $caption .= "<b>📅 Periode:</b> {$monthName} {$year}\n";
            $caption .= "<b>📊 Total Laporan:</b> {$totalLaporan} tiket\n\n";

            if ($totalLaporan > 0) {
                $caption .= "<b>📈 RINGKASAN STATUS:</b>\n";
                $caption .= "└ 🟢 <b>Done (Selesai):</b> {$stats['done']} tiket\n";
                $caption .= "└ 🔵 <b>Process:</b> {$stats['process']} tiket\n";
                $caption .= "└ 🟡 <b>Open:</b> {$stats['open']} tiket\n";
                $caption .= "└ 🔴 <b>Reject:</b> {$stats['reject']} tiket\n";
                $caption .= "└ 🟠 <b>Pending:</b> {$stats['pending']} tiket\n";
                $caption .= "└ ⬆️ <b>Escalate:</b> {$stats['escalate']} tiket\n\n";

                $completionRate = $totalLaporan > 0 ? round(($stats['done'] / $totalLaporan) * 100, 1) : 0;
                $caption .= "<b>📊 Tingkat Penyelesaian:</b> {$completionRate}%\n";
                $caption .= "<b>📈 Dalam Proses:</b> " . ($stats['open'] + $stats['process']) . " tiket\n\n";
            }

            $caption .= "<i>Lampiran PDF berisi detail lengkap rekap bulanan</i>\n";
            $caption .= "<i>Generated: " . Carbon::now()->format('d/m/Y H:i') . "</i>";

            // Kirim file PDF
            $result = $this->telegramService->sendDocument($pdfPath, $caption);

            if ($result) {
                $this->info("PDF sent to Telegram successfully");
                Log::info("Monthly recap PDF sent to Telegram for {$monthName} {$year}", $stats);
            } else {
                $this->warn("Failed to send PDF to Telegram");
                Log::warning("Failed to send monthly recap PDF to Telegram");
            }

        } catch (\Exception $e) {
            Log::error('Error sending PDF to Telegram: ' . $e->getMessage());
            $this->error('Error sending to Telegram: ' . $e->getMessage());
        }
    }

    /**
     * Send empty report notification
     */
    private function sendEmptyReportNotification($targetDate)
    {
        try {
            $monthName = $this->getIndonesianMonth($targetDate->month);
            $year = $targetDate->year;

            $message = "<b>📊 REKAP BULANAN - TIDAK ADA LAPORAN</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "<b>📅 Periode:</b> {$monthName} {$year}\n";
            $message .= "<b>📊 Status:</b> Tidak ada laporan yang masuk\n\n";
            $message .= "<i>Tidak ada laporan pada periode ini.</i>";

            $this->telegramService->sendMessage($message);

            Log::info("Empty report notification sent for {$monthName} {$year}");
        } catch (\Exception $e) {
            Log::error('Error sending empty report notification: ' . $e->getMessage());
        }
    }

    /**
     * Send error notification
     */
    private function sendErrorNotification($errorMessage)
    {
        try {
            $message = "<b>❌ ERROR - REKAP BULANAN</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "<b>Error:</b>\n";
            $message .= "└ " . substr($errorMessage, 0, 200) . "\n\n";
            $message .= "<i>Silakan cek log untuk detail lebih lanjut.</i>";

            $this->telegramService->sendMessage($message);
        } catch (\Exception $e) {
            Log::error('Failed to send error notification: ' . $e->getMessage());
        }
    }

    /**
     * Clean up old PDF files (more than 3 months)
     */
    private function cleanupOldPdfs()
    {
        try {
            $recapPath = storage_path('app/public/recaps');
            if (!file_exists($recapPath)) {
                return;
            }

            $files = glob($recapPath . '/*/*.pdf');
            $now = Carbon::now();
            $deletedCount = 0;
            $deletedSize = 0;

            foreach ($files as $file) {
                $fileDate = Carbon::createFromTimestamp(filemtime($file));
                if ($fileDate->diffInMonths($now) > 3) {
                    $deletedSize += filesize($file);
                    unlink($file);
                    $deletedCount++;
                }
            }

            if ($deletedCount > 0) {
                $sizeInMB = round($deletedSize / 1024 / 1024, 2);
                $this->info("Cleaned up {$deletedCount} old PDF files ({$sizeInMB} MB)");
                Log::info("Monthly recap cleanup: deleted {$deletedCount} files ({$sizeInMB} MB)");
            }
        } catch (\Exception $e) {
            Log::warning('Error cleaning up old PDFs: ' . $e->getMessage());
        }
    }

    /**
     * Get Indonesian month name
     */
    private function getIndonesianMonth($month)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[$month] ?? 'Unknown';
    }
}

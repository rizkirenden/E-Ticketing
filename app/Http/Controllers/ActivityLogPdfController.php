<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogPdfController extends Controller
{
    /**
     * Generate PDF activity logs
     */
    public function generate(Request $request)
    {
        try {
            // Validasi orientasi
            $request->validate([
                'orientation' => 'required|in:portrait,landscape'
            ]);

            $orientation = $request->orientation;
            $paperSize = 'A4';

            // Query data dengan filter
            $query = ActivityLog::with('user');

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('aktivitas', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('modul', 'like', "%{$search}%")
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('nama', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('modul')) {
                $query->where('modul', $request->modul);
            }

            if ($request->filled('aktivitas')) {
                $query->where('aktivitas', $request->aktivitas);
            }

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tanggal_aktivitas', [
                    $request->tanggal_awal . ' 00:00:00',
                    $request->tanggal_akhir . ' 23:59:59'
                ]);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            // Konversi logo ke base64
            $logoBase64 = '';
            $logoPath = public_path('assets/logo4.PNG');
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }

            // Statistik
            $statistics = [
                'total' => $logs->count(),
                'by_module' => $logs->groupBy('modul')->map->count(),
                'by_activity' => $logs->groupBy('aktivitas')->map->count(),
                'by_user' => $logs->groupBy(function($item) {
                    return $item->user ? $item->user->nama : 'System';
                })->map->count()
            ];

            // Data untuk PDF
            $data = [
                'logs' => $logs,
                'title' => 'Laporan Activity Logs',
                'subtitle' => 'Catatan Aktivitas Pengguna',
                'date' => now()->format('d F Y H:i:s'),
                'logoBase64' => $logoBase64,
                'statistics' => $statistics,
                'filters' => [
                    'search' => $request->search ?: '-',
                    'modul' => $request->modul ?: 'Semua',
                    'aktivitas' => $request->aktivitas ?: 'Semua',
                    'tanggal_awal' => $request->tanggal_awal ?: '-',
                    'tanggal_akhir' => $request->tanggal_akhir ?: '-',
                ]
            ];

            // Pilih view berdasarkan orientasi
            $view = $orientation == 'portrait'
                ? 'pdf.activity-logs-portrait'
                : 'pdf.activity-logs-landscape';

            $pdf = PDF::loadView($view, $data)
                ->setPaper($paperSize, $orientation)
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => public_path(),
                ]);

            // Download PDF
            $filename = 'activity-logs-' . date('Y-m-d-His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF untuk single activity log
     */
    public function generateSingle($id, Request $request)
    {
        try {
            $request->validate([
                'orientation' => 'required|in:portrait,landscape'
            ]);

            $orientation = $request->orientation;
            $log = ActivityLog::with('user')->findOrFail($id);

            // Decode JSON data
            $log->data_sebelum = $log->data_sebelum ? json_decode($log->data_sebelum, true) : null;
            $log->data_sesudah = $log->data_sesudah ? json_decode($log->data_sesudah, true) : null;

            // Konversi logo ke base64
            $logoBase64 = '';
            $logoPath = public_path('assets/logo4.PNG');
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }

            $data = [
                'log' => $log,
                'title' => 'Detail Activity Log',
                'date' => now()->format('d F Y H:i:s'),
                'logoBase64' => $logoBase64,
            ];

            $view = $orientation == 'portrait'
                ? 'pdf.activity-log-detail-portrait'
                : 'pdf.activity-log-detail-landscape';

            $pdf = PDF::loadView($view, $data)
                ->setPaper('A4', $orientation)
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            $filename = 'activity-log-' . $log->id . '-' . date('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating single PDF: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanPdfController extends Controller
{
    /**
     * Generate PDF dengan pilihan orientasi
     */
    public function generate(Request $request)
    {
        $request->validate([
            'orientation' => 'required|in:portrait,landscape'
        ]);

        $orientation = $request->orientation;
        $paperSize = 'A4';

        // Ambil data laporan dengan filter jika ada
        $query = Laporan::with(['kantor', 'jenisAplikasi']);

        // Apply filters from request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_ticket', 'like', "%{$search}%")
                    ->orWhere('nama_pelapor', 'like', "%{$search}%")
                    ->orWhere('no_handphone', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('kantor', function($q) use ($search) {
                        $q->where('nama_cabang', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jenisAplikasi', function($q) use ($search) {
                        $q->where('jenis_aplikasi', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('kantor')) {
            $query->where('kantor_id', $request->kantor);
        }

        if ($request->filled('aplikasi')) {
            $query->where('jenis_aplikasi_id', $request->aplikasi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_laporan', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_laporan', '<=', $request->tanggal_akhir);
        }

        $laporans = $query->orderBy('created_at', 'desc')->get();

        // Konversi logo ke base64 - menggunakan logo4.PNG
        $logoBase64 = '';
        $logoPath = public_path('assets/logo4.PNG'); // Ganti ke logo4.PNG
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        // Data untuk PDF
        $data = [
            'laporans' => $laporans,
            'title' => 'Laporan Data Tiket E-Ticketing',
            'date' => now()->format('d F Y H:i:s'),
            'total' => $laporans->count(),
            'logoBase64' => $logoBase64,
            'filters' => [
                'search' => $request->search,
                'kantor' => $request->kantor ? optional(\App\Models\Kantor::find($request->kantor))->nama_cabang : 'Semua',
                'aplikasi' => $request->aplikasi ? optional(\App\Models\Jenisaplikasi::find($request->aplikasi))->jenis_aplikasi : 'Semua',
                'status' => $request->status ? ucfirst($request->status) : 'Semua',
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]
        ];

        // Pilih view berdasarkan orientasi
        $view = $orientation == 'portrait'
            ? 'pdf.laporan-portrait'
            : 'pdf.laporan-landscape';

        $pdf = PDF::loadView($view, $data)
            ->setPaper($paperSize, $orientation)
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        // Download PDF dengan nama file yang dinamis
        $filename = 'laporan-tiket-' . date('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate PDF untuk single laporan
     */
    public function generateSingle($id, Request $request)
    {
        $request->validate([
            'orientation' => 'required|in:portrait,landscape'
        ]);

        $orientation = $request->orientation;
        $laporan = Laporan::with(['kantor', 'jenisAplikasi'])->findOrFail($id);

        // Konversi logo ke base64 - menggunakan logo4.PNG
        $logoBase64 = '';
        $logoPath = public_path('assets/logo4.PNG'); // Ganti ke logo4.PNG
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $data = [
            'laporan' => $laporan,
            'title' => 'Detail Laporan Tiket',
            'date' => now()->format('d F Y H:i:s'),
            'logoBase64' => $logoBase64,
        ];

        $view = $orientation == 'portrait'
            ? 'pdf.laporan-detail-portrait'
            : 'pdf.laporan-detail-landscape';

        $pdf = PDF::loadView($view, $data)
            ->setPaper('A4', $orientation)
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        $filename = 'laporan-' . $laporan->nomor_ticket . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}

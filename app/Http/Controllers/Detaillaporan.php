<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kantor;
use App\Models\Jenisaplikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Detaillaporan extends Controller
{
    /**
     * Display a listing of the resource for public.
     */
    public function index(Request $request)
    {
        // Ambil data untuk filter
        $kantors = Kantor::orderBy('nama_cabang')->get();
        $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();

        // Query laporan dengan filter
        $laporans = Laporan::with(['kantor', 'jenisAplikasi'])
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nomor_ticket', 'like', "%{$search}%")
                      ->orWhere('nama_pelapor', 'like', "%{$search}%")
                      ->orWhere('no_handphone', 'like', "%{$search}%")
                      ->orWhere('kronologi', 'like', "%{$search}%")
                      ->orWhereHas('kantor', function($q2) use ($search) {
                          $q2->where('nama_cabang', 'like', "%{$search}%");
                      })
                      ->orWhereHas('jenisAplikasi', function($q2) use ($search) {
                          $q2->where('jenis_aplikasi', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->kantor, function($query, $kantorId) {
                return $query->where('kantor_id', $kantorId);
            })
            ->when($request->aplikasi, function($query, $aplikasiId) {
                return $query->where('jenis_aplikasi_id', $aplikasiId);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            // Filter range tanggal
            ->when($request->tanggal_awal, function($query, $tanggalAwal) {
                return $query->whereDate('tanggal_laporan', '>=', Carbon::parse($tanggalAwal));
            })
            ->when($request->tanggal_akhir, function($query, $tanggalAkhir) {
                return $query->whereDate('tanggal_laporan', '<=', Carbon::parse($tanggalAkhir));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // Penting untuk mempertahankan query string di pagination

        if ($request->ajax()) {
            return view('detail_laporan.table', compact('laporans'))->render();
        }

        return view('detail_laporan.index', compact('laporans', 'kantors', 'jenisAplikasis'));
    }

    /**
     * Display the specified resource for public.
     */
    public function show($id)
    {
        $laporan = Laporan::with(['kantor', 'jenisAplikasi'])->findOrFail($id);
        $lampiran = json_decode($laporan->lampiran, true) ?? [];

        $relatedLaporans = Laporan::with(['kantor', 'jenisAplikasi'])
            ->where('kantor_id', $laporan->kantor_id)
            ->where('id', '!=', $laporan->id)
            ->limit(3)
            ->get();

        return view('detail_laporan.show', compact('laporan', 'lampiran', 'relatedLaporans'));
    }

    /**
     * Track progress by ticket number
     */
    public function track(Request $request)
    {
        $request->validate([
            'nomor_ticket' => 'required|string|exists:laporans,nomor_ticket'
        ]);

        $laporan = Laporan::with(['kantor', 'jenisAplikasi'])
            ->where('nomor_ticket', $request->nomor_ticket)
            ->first();

        return redirect()->route('detail_laporan.show', $laporan->id);
    }
}

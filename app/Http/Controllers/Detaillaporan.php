<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kantor;
use App\Models\Jenisaplikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Detaillaporan extends Controller
{
    public function index(Request $request)
    {
        $kantors = Kantor::orderBy('nama_cabang')->get();
        $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();

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
            ->when($request->tanggal_awal, function($query, $tanggalAwal) {
                return $query->whereDate('tanggal_laporan', '>=', Carbon::parse($tanggalAwal));
            })
            ->when($request->tanggal_akhir, function($query, $tanggalAkhir) {
                return $query->whereDate('tanggal_laporan', '<=', Carbon::parse($tanggalAkhir));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('detail_laporan.table', compact('laporans'))->render();
        }

        return view('detail_laporan.index', compact('laporans', 'kantors', 'jenisAplikasis'));
    }

    public function show($id)
    {
        $laporan = Laporan::with(['kantor', 'jenisAplikasi', 'histories'])->findOrFail($id);
        $lampiran = json_decode($laporan->lampiran, true) ?? [];

        $timeline = $this->generateTimelineFromHistories($laporan);
        $alasanTimeline = $this->generateAlasanTimeline($laporan);

        return view('detail_laporan.show', compact('laporan', 'lampiran', 'timeline', 'alasanTimeline'));
    }

    private function generateTimelineFromHistories($laporan)
    {
        $timeline = [];

        $timeline[] = [
            'id' => 'create_' . $laporan->id,
            'timestamp' => ($laporan->tanggal_laporan ?? $laporan->created_at)->format('Y-m-d H:i:s'),
            'old_status' => null,
            'new_status' => 'open',
            'old_status_label' => null,
            'new_status_label' => 'Laporan Dibuat',
            'description' => null,
            'updated_by' => $laporan->nama_pelapor,
            'icon' => '📝',
            'color' => 'gray',
            'is_initial' => true
        ];

        $histories = $laporan->histories;

        foreach ($histories as $history) {
            $timeline[] = [
                'id' => 'history_' . $history->id,
                'timestamp' => $history->changed_at->format('Y-m-d H:i:s'),
                'old_status' => $history->old_status,
                'new_status' => $history->new_status,
                'old_status_label' => $this->getStatusLabel($history->old_status),
                'new_status_label' => $this->getStatusLabel($history->new_status),
                'description' => $history->description,
                'updated_by' => $history->updated_by,
                'icon' => $this->getStatusIcon($history->new_status),
                'color' => $this->getStatusColor($history->new_status),
                'is_initial' => false
            ];
        }

        if ($histories->isEmpty() && $laporan->status != 'open') {
            $timeline = $this->generateFallbackTimeline($laporan, $timeline);
        }

        return $timeline;
    }

    private function generateAlasanTimeline($laporan)
    {
        $alasanTimeline = [];

        $histories = $laporan->histories;

        foreach ($histories as $history) {
            if (!empty($history->description) && in_array($history->new_status, ['pending', 'escalate', 'done'])) {
                $alasanTimeline[] = [
                    'id' => 'alasan_' . $history->id,
                    'timestamp' => $history->changed_at->format('Y-m-d H:i:s'),
                    'status' => $history->new_status,
                    'status_label' => $this->getStatusLabel($history->new_status),
                    'description' => $history->description,
                    'updated_by' => $history->updated_by,
                    'icon' => $this->getStatusIcon($history->new_status),
                    'color' => $this->getStatusColor($history->new_status)
                ];
            }
        }

        if (empty($alasanTimeline)) {
            if (!empty($laporan->pending_deskripsi)) {
                $alasanTimeline[] = [
                    'id' => 'pending_fallback',
                    'timestamp' => $laporan->updated_at->format('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'status_label' => 'Ditunda',
                    'description' => $laporan->pending_deskripsi,
                    'updated_by' => 'System',
                    'icon' => '⏳',
                    'color' => 'orange'
                ];
            }

            if (!empty($laporan->escalate_deskripsi)) {
                $alasanTimeline[] = [
                    'id' => 'escalate_fallback',
                    'timestamp' => $laporan->updated_at->format('Y-m-d H:i:s'),
                    'status' => 'escalate',
                    'status_label' => 'Escalasi',
                    'description' => $laporan->escalate_deskripsi,
                    'updated_by' => 'System',
                    'icon' => '⬆️',
                    'color' => 'purple'
                ];
            }

            if ($laporan->status == 'done' && !empty($laporan->solusi)) {
                $alasanTimeline[] = [
                    'id' => 'done_fallback',
                    'timestamp' => ($laporan->tanggal_selesai ?? $laporan->updated_at)->format('Y-m-d H:i:s'),
                    'status' => 'done',
                    'status_label' => 'Selesai',
                    'description' => $laporan->solusi,
                    'updated_by' => 'System',
                    'icon' => '✅',
                    'color' => 'green'
                ];
            }
        }

        return $alasanTimeline;
    }

    private function generateFallbackTimeline($laporan, $timeline)
    {
        $currentStatus = $laporan->status;
        $hasPending = !empty($laporan->pending_deskripsi);
        $hasEscalate = !empty($laporan->escalate_deskripsi);
        $createTime = ($laporan->tanggal_laporan ?? $laporan->created_at);
        $updateTime = $laporan->updated_at;

        if ($currentStatus != 'open') {
            $timeline[] = [
                'id' => 'fallback_process',
                'timestamp' => $createTime->copy()->addSeconds(5)->format('Y-m-d H:i:s'),
                'old_status' => 'open',
                'new_status' => 'process',
                'old_status_label' => 'Laporan Dibuat',
                'new_status_label' => 'Sedang Diproses',
                'description' => null,
                'updated_by' => 'System',
                'icon' => '🔄',
                'color' => 'blue',
                'is_initial' => false
            ];
        }

        if ($hasPending) {
            $timeline[] = [
                'id' => 'fallback_pending',
                'timestamp' => $createTime->copy()->addSeconds(10)->format('Y-m-d H:i:s'),
                'old_status' => 'process',
                'new_status' => 'pending',
                'old_status_label' => 'Sedang Diproses',
                'new_status_label' => 'Ditunda',
                'description' => $laporan->pending_deskripsi,
                'updated_by' => 'System',
                'icon' => '⏳',
                'color' => 'orange',
                'is_initial' => false
            ];
        }

        if ($hasEscalate) {
            $previousStatus = $hasPending ? 'pending' : 'process';
            $previousLabel = $hasPending ? 'Ditunda' : 'Sedang Diproses';

            $timeline[] = [
                'id' => 'fallback_escalate',
                'timestamp' => $createTime->copy()->addSeconds(15)->format('Y-m-d H:i:s'),
                'old_status' => $previousStatus,
                'new_status' => 'escalate',
                'old_status_label' => $previousLabel,
                'new_status_label' => 'Escalasi',
                'description' => $laporan->escalate_deskripsi,
                'updated_by' => 'System',
                'icon' => '⬆️',
                'color' => 'purple',
                'is_initial' => false
            ];
        }

        if ($currentStatus == 'done') {
            $doneTime = $laporan->tanggal_selesai ?? $updateTime;
            $previousStatus = 'process';
            $previousLabel = 'Sedang Diproses';

            if ($hasEscalate) {
                $previousStatus = 'escalate';
                $previousLabel = 'Escalasi';
            } elseif ($hasPending) {
                $previousStatus = 'pending';
                $previousLabel = 'Ditunda';
            }

            $timeline[] = [
                'id' => 'fallback_done',
                'timestamp' => $doneTime->format('Y-m-d H:i:s'),
                'old_status' => $previousStatus,
                'new_status' => 'done',
                'old_status_label' => $previousLabel,
                'new_status_label' => 'Selesai',
                'description' => $laporan->solusi,
                'updated_by' => 'System',
                'icon' => '✅',
                'color' => 'green',
                'is_initial' => false
            ];
        }

        if ($currentStatus == 'reject') {
            $timeline[] = [
                'id' => 'fallback_reject',
                'timestamp' => $updateTime->format('Y-m-d H:i:s'),
                'old_status' => 'process',
                'new_status' => 'reject',
                'old_status_label' => 'Sedang Diproses',
                'new_status_label' => 'Ditolak',
                'description' => null,
                'updated_by' => 'System',
                'icon' => '❌',
                'color' => 'red',
                'is_initial' => false
            ];
        }

        return $timeline;
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'open' => 'Laporan Dibuat',
            'process' => 'Sedang Diproses',
            'pending' => 'Ditunda',
            'escalate' => 'Escalasi',
            'done' => 'Selesai',
            'reject' => 'Ditolak'
        ];
        return $labels[$status] ?? ucfirst($status);
    }

    private function getStatusIcon($status)
    {
        $icons = [
            'open' => '📝',
            'process' => '🔄',
            'pending' => '⏳',
            'escalate' => '⬆️',
            'done' => '✅',
            'reject' => '❌'
        ];
        return $icons[$status] ?? '📌';
    }

    private function getStatusColor($status)
    {
        $colors = [
            'open' => 'gray',
            'process' => 'blue',
            'pending' => 'orange',
            'escalate' => 'purple',
            'done' => 'green',
            'reject' => 'red'
        ];
        return $colors[$status] ?? 'gray';
    }

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

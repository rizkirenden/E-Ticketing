<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Kantor;
use App\Models\Jenisaplikasi;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // ===================================================
        // STATUS TICKET YANG TERSEDIA:
        // open, process, done, reject, pending, escalate
        // ===================================================

        // Statistik Utama (BERDASARKAN TAHUN YANG DIPILIH)
        $totalTickets = Laporan::whereYear('tanggal_laporan', $tahun)->count();

        // Active Tickets (Open + Process + Pending + Escalate)
        $activeTickets = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereIn('status', ['open', 'process', 'pending', 'escalate'])
            ->count();

        // Resolved Tickets (Done)
        $resolvedTickets = Laporan::whereYear('tanggal_laporan', $tahun)
            ->where('status', 'done')
            ->count();

        // Rejected Tickets
        $rejectedTickets = Laporan::whereYear('tanggal_laporan', $tahun)
            ->where('status', 'reject')
            ->count();

        // Closed Tickets (Done + Reject)
        $closedTickets = $resolvedTickets + $rejectedTickets;

        // ===================================================
        // TOTAL PER STATUS (filter berdasarkan TAHUN)
        // ===================================================
        $totalOpen = Laporan::where('status', 'open')->whereYear('tanggal_laporan', $tahun)->count();
        $totalProcess = Laporan::where('status', 'process')->whereYear('tanggal_laporan', $tahun)->count();
        $totalPending = Laporan::where('status', 'pending')->whereYear('tanggal_laporan', $tahun)->count();
        $totalEscalate = Laporan::where('status', 'escalate')->whereYear('tanggal_laporan', $tahun)->count();
        $totalDone = Laporan::where('status', 'done')->whereYear('tanggal_laporan', $tahun)->count();
        $totalReject = Laporan::where('status', 'reject')->whereYear('tanggal_laporan', $tahun)->count();

        // Statistik berdasarkan tahun yang dipilih
        $ticketsPerBulan = Laporan::select(
            DB::raw('MONTH(tanggal_laporan) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tanggal_laporan', $tahun)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get()
        ->pluck('total', 'bulan')
        ->toArray();

        // Data untuk chart batang (12 bulan)
        $dataPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPerBulan[$i] = $ticketsPerBulan[$i] ?? 0;
        }

        // Statistik per aplikasi
        $statistikAplikasi = Jenisaplikasi::withCount(['laporans' => function($query) use ($tahun) {
            $query->whereYear('tanggal_laporan', $tahun);
        }])->get();

        // Total per kuartal
        $kuartal1 = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', '<=', 3)
            ->count();
        $kuartal2 = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', '>=', 4)
            ->whereMonth('tanggal_laporan', '<=', 6)
            ->count();
        $kuartal3 = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', '>=', 7)
            ->whereMonth('tanggal_laporan', '<=', 9)
            ->count();
        $kuartal4 = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', '>=', 10)
            ->count();

        // Statistik per kantor
        $statistikKantor = Kantor::withCount(['laporans' => function($query) use ($tahun) {
            $query->whereYear('tanggal_laporan', $tahun);
        }])->get();

        // Ticket terbaru (filter berdasarkan tahun yang dipilih)
        $recentTickets = Laporan::with(['kantor', 'jenisAplikasi'])
            ->whereYear('tanggal_laporan', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik status dengan urutan yang benar (sudah filter tahun)
        $statusCount = [
            'open' => $totalOpen,
            'process' => $totalProcess,
            'pending' => $totalPending,
            'escalate' => $totalEscalate,
            'done' => $totalDone,
            'reject' => $totalReject,
        ];

        // Statistik user (tidak perlu filter tahun)
        $totalUsers = User::count();
        $usersPerRole = Role::withCount('users')->get();

        // Data untuk tahun filter
        $availableYears = Laporan::select(DB::raw('YEAR(tanggal_laporan) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get()
            ->pluck('tahun');

        // Jika tidak ada data tahun, tambahkan tahun sekarang
        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // Growth percentages (dibanding tahun lalu)
        $tahunLalu = $tahun - 1;
        $totalTahunIni = Laporan::whereYear('tanggal_laporan', $tahun)->count();
        $totalTahunLalu = Laporan::whereYear('tanggal_laporan', $tahunLalu)->count();

        $growthTotal = $totalTahunLalu > 0
            ? round((($totalTahunIni - $totalTahunLalu) / $totalTahunLalu) * 100, 1)
            : 0;

        $growthActive = $this->hitungGrowth($tahun, $tahunLalu, 'active');
        $growthClosed = $this->hitungGrowth($tahun, $tahunLalu, 'closed');

        // Siapkan data untuk pie chart
        $pieData = [];
        foreach ($statistikAplikasi as $aplikasi) {
            if ($aplikasi->laporans_count > 0) {
                $pieData[] = [
                    'name' => $aplikasi->jenis_aplikasi,
                    'y' => $aplikasi->laporans_count
                ];
            }
        }

        // Hitung persentase
        $activePercentage = $totalTickets > 0 ? round(($activeTickets / $totalTickets) * 100) : 0;
        $closedPercentage = $totalTickets > 0 ? round(($closedTickets / $totalTickets) * 100) : 0;
        $donePercentage = $totalTickets > 0 ? round(($totalDone / $totalTickets) * 100) : 0;
        $rejectPercentage = $totalTickets > 0 ? round(($totalReject / $totalTickets) * 100) : 0;

        return view('dashboard.dashboard', compact(
            'totalTickets',
            'activeTickets',
            'resolvedTickets',
            'rejectedTickets',
            'closedTickets',
            'totalOpen',
            'totalProcess',
            'totalPending',
            'totalEscalate',
            'totalDone',
            'totalReject',
            'dataPerBulan',
            'statistikAplikasi',
            'kuartal1',
            'kuartal2',
            'kuartal3',
            'kuartal4',
            'statistikKantor',
            'recentTickets',
            'statusCount',
            'totalUsers',
            'usersPerRole',
            'availableYears',
            'tahun',
            'growthTotal',
            'growthActive',
            'growthClosed',
            'totalTahunIni',
            'pieData',
            'activePercentage',
            'closedPercentage',
            'donePercentage',
            'rejectPercentage'
        ));
    }

    /**
     * Hitung growth percentage antara tahun ini dan tahun lalu
     *
     * @param int $tahunIni
     * @param int $tahunLalu
     * @param string $type (active, closed)
     * @return float
     */
    private function hitungGrowth($tahunIni, $tahunLalu, $type)
    {
        $queryIni = Laporan::whereYear('tanggal_laporan', $tahunIni);
        $queryLalu = Laporan::whereYear('tanggal_laporan', $tahunLalu);

        if ($type == 'closed') {
            $queryIni->whereIn('status', ['done', 'reject']);
            $queryLalu->whereIn('status', ['done', 'reject']);
        } elseif ($type == 'active') {
            $queryIni->whereIn('status', ['open', 'process', 'pending', 'escalate']);
            $queryLalu->whereIn('status', ['open', 'process', 'pending', 'escalate']);
        }

        $totalIni = $queryIni->count();
        $totalLalu = $queryLalu->count();

        return $totalLalu > 0
            ? round((($totalIni - $totalLalu) / $totalLalu) * 100, 1)
            : 0;
    }

    /**
     * Get chart data for AJAX requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Data untuk chart batang (12 bulan)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = Laporan::whereYear('tanggal_laporan', $tahun)
                ->whereMonth('tanggal_laporan', $i)
                ->count();
        }

        // Data untuk pie chart
        $pieData = Jenisaplikasi::withCount(['laporans' => function($query) use ($tahun) {
            $query->whereYear('tanggal_laporan', $tahun);
        }])->get()
        ->filter(function($item) {
            return $item->laporans_count > 0;
        })
        ->map(function($item) {
            return [
                'name' => $item->jenis_aplikasi,
                'y' => $item->laporans_count
            ];
        })->values();

        // Data untuk card utama
        $totalTickets = Laporan::whereYear('tanggal_laporan', $tahun)->count();
        $activeTickets = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereIn('status', ['open', 'process', 'pending', 'escalate'])
            ->count();
        $closedTickets = Laporan::whereYear('tanggal_laporan', $tahun)
            ->whereIn('status', ['done', 'reject'])
            ->count();

        // Data status untuk semua 6 status
        $statusData = [
            'open' => Laporan::where('status', 'open')->whereYear('tanggal_laporan', $tahun)->count(),
            'process' => Laporan::where('status', 'process')->whereYear('tanggal_laporan', $tahun)->count(),
            'pending' => Laporan::where('status', 'pending')->whereYear('tanggal_laporan', $tahun)->count(),
            'escalate' => Laporan::where('status', 'escalate')->whereYear('tanggal_laporan', $tahun)->count(),
            'done' => Laporan::where('status', 'done')->whereYear('tanggal_laporan', $tahun)->count(),
            'reject' => Laporan::where('status', 'reject')->whereYear('tanggal_laporan', $tahun)->count(),
        ];

        // Hitung persentase
        $activePercentage = $totalTickets > 0 ? round(($activeTickets / $totalTickets) * 100) : 0;
        $closedPercentage = $totalTickets > 0 ? round(($closedTickets / $totalTickets) * 100) : 0;
        $donePercentage = $totalTickets > 0 ? round(($statusData['done'] / $totalTickets) * 100) : 0;
        $rejectPercentage = $totalTickets > 0 ? round(($statusData['reject'] / $totalTickets) * 100) : 0;

        // Recent tickets
        $recentTickets = Laporan::with(['kantor', 'jenisAplikasi'])
            ->whereYear('tanggal_laporan', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($ticket) {
                return [
                    'nomor_ticket' => $ticket->nomor_ticket,
                    'nama_pelapor' => $ticket->nama_pelapor,
                    'kantor' => $ticket->kantor->nama_cabang ?? '-',
                    'aplikasi' => $ticket->jenisAplikasi->jenis_aplikasi ?? '-',
                    'status' => $ticket->status,
                    'tanggal' => $ticket->tanggal_laporan->format('d/m/Y H:i'),
                    'status_badge_class' => $this->getStatusBadgeClass($ticket->status)
                ];
            });

        return response()->json([
            'barData' => $chartData,
            'pieData' => $pieData,
            'totalTickets' => $totalTickets,
            'activeTickets' => $activeTickets,
            'closedTickets' => $closedTickets,
            'activePercentage' => $activePercentage,
            'closedPercentage' => $closedPercentage,
            'donePercentage' => $donePercentage,
            'rejectPercentage' => $rejectPercentage,
            'statusData' => $statusData,
            'recentTickets' => $recentTickets,
            'total' => array_sum($chartData)
        ]);
    }

    /**
     * Get CSS class for status badge
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'done':
                return 'bg-green-400/10 text-green-400';
            case 'open':
                return 'bg-yellow-400/10 text-yellow-400';
            case 'process':
                return 'bg-blue-400/10 text-blue-400';
            case 'pending':
                return 'bg-orange-400/10 text-orange-400';
            case 'escalate':
                return 'bg-purple-400/10 text-purple-400';
            case 'reject':
                return 'bg-red-400/10 text-red-400';
            default:
                return 'bg-gray-400/10 text-gray-400';
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('aktivitas', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('modul', 'like', "%{$search}%")
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('nama', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->modul, function($query, $modul) {
                return $query->where('modul', $modul);
            })
            ->when($request->aktivitas, function($query, $aktivitas) {
                return $query->where('aktivitas', $aktivitas);
            })
            ->when($request->tanggal_awal && $request->tanggal_akhir, function($query) use ($request) {
                return $query->whereBetween('tanggal_aktivitas', [
                    $request->tanggal_awal . ' 00:00:00',
                    $request->tanggal_akhir . ' 23:59:59'
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar modul unik untuk filter
        $modules = ActivityLog::distinct()->pluck('modul')->filter()->values();

        // Ambil daftar aktivitas unik untuk filter
        $activities = ActivityLog::distinct()->pluck('aktivitas')->filter()->values();

        if ($request->ajax()) {
            return view('activity_log.table', compact('logs'))->render();
        }

        return view('activity_log.index', compact('logs', 'modules', 'activities'));
    }

    /**
     * Display the specified activity log.
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        // Decode JSON data
        $log->data_sebelum = $log->data_sebelum ? json_decode($log->data_sebelum, true) : null;
        $log->data_sesudah = $log->data_sesudah ? json_decode($log->data_sesudah, true) : null;

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $log
            ]);
        }

        return view('activity_log.show', compact('log'));
    }

    /**
     * Get ALL statistics at once
     */
    public function getAllStatistics()
    {
        try {
            $today = ActivityLog::whereDate('tanggal_aktivitas', today())->count();
            $week = ActivityLog::whereBetween('tanggal_aktivitas', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $month = ActivityLog::whereMonth('tanggal_aktivitas', now()->month)->count();
            $year = ActivityLog::whereYear('tanggal_aktivitas', now()->year)->count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'today' => $today,
                    'week' => $week,
                    'month' => $month,
                    'year' => $year
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get aktivitas statistik (per period)
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'today');

        $query = ActivityLog::query();

        switch ($period) {
            case 'today':
                $query->whereDate('tanggal_aktivitas', today());
                break;
            case 'week':
                $query->whereBetween('tanggal_aktivitas', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('tanggal_aktivitas', now()->month);
                break;
            case 'year':
                $query->whereYear('tanggal_aktivitas', now()->year);
                break;
        }

        $total = $query->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $total,
                'period' => $period
            ]
        ]);
    }

    /**
     * Export logs to JSON
     */
    public function export(Request $request)
    {
        try {
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

            $formattedLogs = $logs->map(function($log) {
                return [
                    'id' => $log->id,
                    'user' => $log->user ? $log->user->nama : 'System',
                    'username' => $log->user ? $log->user->username : '-',
                    'aktivitas' => $log->aktivitas,
                    'deskripsi' => $log->deskripsi,
                    'modul' => $log->modul,
                    'data_id' => $log->data_id,
                    'data_sebelum' => $log->data_sebelum ? json_decode($log->data_sebelum) : null,
                    'data_sesudah' => $log->data_sesudah ? json_decode($log->data_sesudah) : null,
                    'tanggal_aktivitas' => $log->tanggal_aktivitas->format('Y-m-d H:i:s'),
                    'created_at' => $log->created_at->format('Y-m-d H:i:s')
                ];
            });

            $filename = 'activity-logs-' . date('Y-m-d-His') . '.json';

            return response()->json($formattedLogs, 200, [
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Type' => 'application/json'
            ]);

        } catch (\Exception $e) {
            Log::error('Error exporting logs: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal export logs: ' . $e->getMessage()
            ], 500);
        }
    }
}

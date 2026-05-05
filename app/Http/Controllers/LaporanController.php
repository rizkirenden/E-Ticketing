<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kantor;
use App\Models\Jenisaplikasi;
use App\Models\Produk;
use App\Services\TelegramService;
use App\Helpers\RoleAplikasiHelper;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Traits\LogsActivity;
use App\Traits\HasPermission;

class LaporanController extends Controller
{
    use LogsActivity, HasPermission;

    protected $telegramService;

    // Status yang perlu diingatkan
    protected $statusToRemind = ['open', 'process', 'escalate', 'pending'];

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;

        // Cek dan kirim pengingat setiap ada request (hanya sekali per jam)
        $this->checkAndSendReminders();
    }

    /**
     * Cek dan kirim pengingat untuk laporan yang belum selesai
     * Method ini akan dipanggil di constructor
     */
    protected function checkAndSendReminders()
    {
        try {
            // Cek apakah sudah waktunya kirim reminder (setiap 1 jam)
            $lastReminderCheck = Cache::get('last_reminder_check', now()->subHours(2));

            // Jika kurang dari 1 jam sejak pengecekan terakhir, skip
            if ($lastReminderCheck->diffInMinutes(now()) < 60) {
                return;
            }

            // Update waktu pengecekan terakhir
            Cache::put('last_reminder_check', now(), 3600);

            // Ambil semua laporan yang perlu diingatkan
            $laporans = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])
                ->whereIn('status', $this->statusToRemind)
                ->get();

            if ($laporans->isEmpty()) {
                return;
            }

            // Kirim reminder untuk setiap laporan
            foreach ($laporans as $laporan) {
                // Cek apakah sudah pernah dikirim reminder dalam 1 jam terakhir
                $reminderKey = 'reminder_' . $laporan->id;
                $lastSent = Cache::get($reminderKey);

                if (!$lastSent || $lastSent->diffInMinutes(now()) >= 60) {
                    $this->sendReminderNotification($laporan);
                    Cache::put($reminderKey, now(), 3600);

                    // Update reminder count di cache
                    $countKey = 'reminder_count_' . $laporan->id;
                    $count = Cache::get($countKey, 0);
                    Cache::put($countKey, $count + 1, 86400); // simpan 24 jam

                    // Delay untuk menghindari rate limit
                    usleep(500000); // 0.5 detik
                }
            }

            Log::info('Reminder dikirim untuk ' . $laporans->count() . ' laporan');

        } catch (\Exception $e) {
            Log::error('Error saat mengirim reminder: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi reminder ke Telegram
     */
    protected function sendReminderNotification($laporan)
    {
        try {
            $statusEmoji = [
                'open' => '🟡 OPEN',
                'process' => '🔵 PROCESS',
                'pending' => '🟠 PENDING',
                'escalate' => '🔴 ESCALATE'
            ];

            $statusText = $statusEmoji[$laporan->status] ?? strtoupper($laporan->status);

            // Hitung durasi laporan
            $duration = $laporan->created_at->diffForHumans(now(), true);
            $hoursSince = floor(now()->diffInHours($laporan->created_at));

            // Dapatkan jumlah reminder yang sudah dikirim
            $reminderKey = 'reminder_count_' . $laporan->id;
            $reminderCount = Cache::get($reminderKey, 0) + 1;

            $message = "<b>⏰ PENGINGAT STATUS LAPORAN</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━\n\n";

            $message .= "<b>⚠️ PERHATIAN!</b>\n";
            $message .= "Laporan berikut masih dalam proses:\n\n";

            $message .= "<b>🆔 Tiket:</b> <code>{$laporan->nomor_ticket}</code>\n";
            $message .= "<b>📊 Status:</b> {$statusText}\n";
            $message .= "<b>📅 Dibuat:</b> " . $laporan->created_at->format('d/m/Y H:i') . " WIB\n";
            $message .= "<b>⏱️ Durasi:</b> {$duration} ({$hoursSince} jam)\n";
            $message .= "<b>👤 Pelapor:</b> {$laporan->nama_pelapor}\n";
            $message .= "<b>🏢 Kantor:</b> " . ($laporan->kantor->nama_cabang ?? 'N/A') . "\n";
            $message .= "<b>📱 Aplikasi:</b> " . ($laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A') . "\n";
            $message .= "<b>📦 Produk:</b> " . ($laporan->produk->nama_produk ?? 'N/A') . "\n\n";

            // Tambahkan info reminder ke berapa
            $message .= "<b>📢 Pengingat ke-{$reminderCount}</b>\n\n";

            // Tambahkan deskripsi berdasarkan status
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

            // Action buttons
            $message .= "<b>🎯 Action:</b>\n";
            $message .= "<a href='" . route('laporan.show', $laporan->id) . "'>🔍 Lihat Detail</a> | ";
            $message .= "<a href='" . route('laporan.edit', $laporan->id) . "'>✏️ Update Status</a>\n\n";

            // Tambahan untuk laporan yang sudah lebih dari 24 jam
            if ($hoursSince >= 24) {
                $message .= "<b>⚠️ URGENT!</b>\n";
                $message .= "Laporan ini sudah lebih dari 24 jam belum terselesaikan!\n";
                $message .= "Harap segera ditindaklanjuti.\n\n";
            }

            $message .= "━━━━━━━━━━━━━━━━━━━\n";
            $message .= "<i>⚠️ Harap segera ditindaklanjuti!</i>\n";
            $message .= "<i>Pesan ini dikirim otomatis oleh sistem setiap 1 jam.</i>";

            $this->telegramService->sendMessage($message);

            Log::info("Reminder dikirim untuk tiket: {$laporan->nomor_ticket}");

        } catch (\Exception $e) {
            Log::error("Gagal kirim reminder untuk tiket {$laporan->nomor_ticket}: " . $e->getMessage());
        }
    }

    /**
     * Manual trigger reminder (bisa diakses via route jika diperlukan)
     */
    public function triggerReminder()
    {
        // Cek permission untuk trigger reminder
        $this->checkPermission('laporan', 'reminder');

        try {
            $laporans = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])
                ->whereIn('status', $this->statusToRemind)
                ->get();

            if ($laporans->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada laporan yang perlu diingatkan',
                    'count' => 0
                ]);
            }

            $sentCount = 0;
            foreach ($laporans as $laporan) {
                $this->sendReminderNotification($laporan);

                $reminderKey = 'reminder_' . $laporan->id;
                Cache::put($reminderKey, now(), 3600);

                $countKey = 'reminder_count_' . $laporan->id;
                $count = Cache::get($countKey, 0);
                Cache::put($countKey, $count + 1, 86400);

                $sentCount++;
                usleep(500000);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil mengirim {$sentCount} reminder",
                'count' => $sentCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error manual trigger reminder: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reminder statistics
     */
    public function reminderStats()
    {
        // Cek permission untuk melihat stats reminder
        $this->checkPermission('laporan', 'reminder');

        try {
            $stats = [
                'open' => Laporan::where('status', 'open')->count(),
                'process' => Laporan::where('status', 'process')->count(),
                'pending' => Laporan::where('status', 'pending')->count(),
                'escalate' => Laporan::where('status', 'escalate')->count(),
                'total_need_reminder' => Laporan::whereIn('status', $this->statusToRemind)->count(),
                'laporan_terlama' => null
            ];

            $terlama = Laporan::whereIn('status', $this->statusToRemind)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($terlama) {
                $stats['laporan_terlama'] = [
                    'tiket' => $terlama->nomor_ticket,
                    'status' => $terlama->status,
                    'durasi_jam' => $terlama->created_at->diffInHours(now()),
                    'nama_pelapor' => $terlama->nama_pelapor
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error get reminder stats: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil statistik reminder'
            ], 500);
        }
    }

    /**
     * Clear reminder cache (untuk reset pengingat)
     */
    public function clearReminderCache($id = null)
    {
        $this->checkPermission('laporan', 'reminder');

        try {
            if ($id) {
                // Clear untuk satu laporan
                Cache::forget('reminder_' . $id);
                Cache::forget('reminder_count_' . $id);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cache reminder untuk tiket ' . $id . ' berhasil dihapus'
                ]);
            } else {
                // Clear semua cache reminder (hati-hati)
                // Ini hanya contoh, sebaiknya jangan digunakan
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hapus semua cache tidak disarankan, gunakan parameter id'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu laporan (hanya untuk halaman index/internal)
        $this->checkPermission('laporan', 'view');

        $user = Auth::user();
        $query = Laporan::with(['kantor', 'jenisAplikasi', 'produk']);

        // FILTER BERDASARKAN ROLE DAN APLIKASI
        $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();

        if (!empty($allowedAplikasiIds)) {
            $query->whereIn('jenis_aplikasi_id', $allowedAplikasiIds);
        } else {
            $query->whereRaw('1 = 0');
        }

        // Filter berdasarkan pencarian
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
                    })
                    ->orWhereHas('produk', function($q) use ($search) {
                        $q->where('nama_produk', 'like', "%{$search}%")
                          ->orWhere('kode_produk', 'like', "%{$search}%");
                    });
            });
        }

        // Filter lainnya
        if ($request->filled('kantor')) {
            $query->where('kantor_id', $request->kantor);
        }

        if ($request->filled('aplikasi') && in_array($request->aplikasi, $allowedAplikasiIds)) {
            $query->where('jenis_aplikasi_id', $request->aplikasi);
        }

        if ($request->filled('produk')) {
            $query->where('kode_produk_id', $request->produk);
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

        $laporans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Dapatkan permission untuk buttons
        $permissions = PermissionHelper::getButtonPermissions('laporan');

        // Untuk filter dropdown
        $kantors = Kantor::orderBy('nama_cabang')->get();
        $jenisAplikasis = RoleAplikasiHelper::getAccessibleAplikasi();
        $produks = Produk::orderBy('kode_produk')->orderBy('nama_produk')->get();

        if ($request->ajax()) {
            return view('laporan.table', compact('laporans', 'permissions'))->render();
        }

        return view('laporan.index', compact('laporans', 'kantors', 'jenisAplikasis', 'produks', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * METHOD INI PUBLIC - TIDAK PERLU CEK PERMISSION
     */
    public function create()
    {
        // TIDAK ADA CEK PERMISSION DI SINI KARENA PUBLIC
        // Semua orang (termasuk guest) boleh mengakses halaman create laporan

        $kantors = Kantor::orderBy('nama_cabang')->get();
        $produks = Produk::orderBy('kode_produk')->orderBy('nama_produk')->get();

        // Cek apakah user login
        if (Auth::check()) {
            // User login - gunakan filter berdasarkan role
            $jenisAplikasis = RoleAplikasiHelper::getAccessibleAplikasi();
        } else {
            // Guest - tampilkan semua aplikasi
            $jenisAplikasis = RoleAplikasiHelper::getAllAplikasiForPublic();
            // Atau bisa juga langsung:
            // $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();
        }

        return view('laporan.create', compact('kantors', 'jenisAplikasis', 'produks'));
    }

    /**
     * Get products by application ID (for AJAX request)
     */
    public function getProductsByApplication($jenisAplikasiId)
    {
        try {
            $produks = Produk::where('jenis_aplikasi_id', $jenisAplikasiId)
                ->orderBy('kode_produk')
                ->orderBy('nama_produk')
                ->get(['id', 'kode_produk', 'nama_produk']);

            return response()->json([
                'status' => 'success',
                'products' => $produks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data produk'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * METHOD INI PUBLIC - TIDAK PERLU CEK PERMISSION
     */
    public function store(Request $request)
    {
        // TIDAK ADA CEK PERMISSION DI SINI KARENA PUBLIC
        // Tapi tetap perlu validasi untuk keamanan dasar

        $request->validate([
            'nama_pelapor' => 'required|string|max:50',
            'kantor_id' => 'required|exists:kantors,id',
            'jenis_aplikasi_id' => 'required|exists:jenis_aplikasis,id',
            'kode_produk_id' => 'required|exists:produks,id',
            'no_handphone' => 'required|string|max:20',
            'kronologi' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        // Untuk store, kita perlu validasi aplikasi hanya jika user LOGIN
        // Jika guest, tidak perlu validasi akses aplikasi karena dia bisa memilih semua
        if (Auth::check()) {
            // User login - validasi akses ke aplikasi
            $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
            if (!in_array($request->jenis_aplikasi_id, $allowedAplikasiIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk membuat laporan dengan aplikasi ini'
                ], 403);
            }

            // Validate product belongs to selected application
            $produk = Produk::find($request->kode_produk_id);
            if (!$produk || $produk->jenis_aplikasi_id != $request->jenis_aplikasi_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk tidak sesuai dengan aplikasi yang dipilih'
                ], 400);
            }
        }
        // Jika guest, tidak perlu validasi karena dia bisa memilih semua aplikasi

        try {
            $data = $request->all();

            // Generate nomor ticket otomatis dengan format baru
            $data['nomor_ticket'] = Laporan::generateNomorTicket(
                $request->jenis_aplikasi_id,
                $request->kode_produk_id
            );
            $data['tanggal_laporan'] = now();
            $data['status'] = 'open';

            // Upload multiple lampiran jika ada
            if ($request->hasFile('lampiran')) {
                $lampiranPaths = [];
                foreach ($request->file('lampiran') as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                        $filePath = $file->storeAs('lampiran', $fileName, 'public');
                        $lampiranPaths[] = $filePath;
                    }
                }
                $data['lampiran'] = json_encode($lampiranPaths);
            }

            $laporan = Laporan::create($data);

            // Load relations untuk logging
            $laporan->load(['kantor', 'jenisAplikasi', 'produk']);

            // Log activity hanya jika user login
            if (Auth::check()) {
                $this->logCreate(
                    'LAPORAN',
                    $laporan->id,
                    "Membuat laporan baru tiket {$laporan->nomor_ticket} - {$laporan->nama_pelapor}",
                    $laporan->toArray()
                );
            }

            // Kirim notifikasi ke Telegram
            $this->sendTelegramNotification($laporan);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Laporan berhasil dikirim!',
                    'data' => [
                        'ticket_number' => $data['nomor_ticket'],
                        'nama_pelapor' => $data['nama_pelapor'],
                        'tanggal' => now()->format('d M Y H:i')
                    ]
                ]);
            }

            return redirect()->route('laporan.create')
                ->with('success', 'Laporan berhasil dibuat! Nomor tiket Anda: ' . $data['nomor_ticket']);

        } catch (\Exception $e) {
            Log::error('Error storing laporan: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cek permission show untuk menu laporan
        $this->checkPermission('laporan', 'show');

        $laporan = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])->findOrFail($id);

        // Validasi akses berdasarkan role
        $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
        if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
            abort(403, 'Anda tidak memiliki izin untuk melihat laporan ini');
        }

        // Decode lampiran dari JSON ke array
        $laporan->lampiran = json_decode($laporan->lampiran, true) ?? [];

        return view('laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Cek permission edit untuk menu laporan
        $this->checkPermission('laporan', 'edit');

        $laporan = Laporan::with(['produk'])->findOrFail($id);

        // Validasi akses berdasarkan role
        $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
        if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit laporan ini');
        }

        // Decode lampiran dari JSON ke array
        $laporan->lampiran = json_decode($laporan->lampiran, true) ?? [];
        $kantors = Kantor::orderBy('nama_cabang')->get();

        // Filter aplikasi berdasarkan role
        $jenisAplikasis = RoleAplikasiHelper::getAccessibleAplikasi();

        // Get products for the current application
        $produks = Produk::where('jenis_aplikasi_id', $laporan->jenis_aplikasi_id)
            ->orderBy('kode_produk')
            ->orderBy('nama_produk')
            ->get();

        return view('laporan.edit', compact('laporan', 'kantors', 'jenisAplikasis', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit untuk menu laporan
        $this->checkPermission('laporan', 'edit');

        $laporan = Laporan::findOrFail($id);
        $oldData = $laporan->toArray();

        // Validasi akses berdasarkan role
        $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
        if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengupdate laporan ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengupdate laporan ini');
        }

        $request->validate([
            'nama_pelapor' => 'sometimes|required|string|max:50',
            'kantor_id' => 'sometimes|required|exists:kantors,id',
            'jenis_aplikasi_id' => 'sometimes|required|exists:jenis_aplikasis,id',
            'kode_produk_id' => 'sometimes|required|exists:produks,id',
            'no_handphone' => 'sometimes|required|string|max:20',
            'kronologi' => 'sometimes|required|string',
            'solusi' => 'nullable|string',
            'status' => 'required|in:open,process,done,reject,pending,escalate',
            'pending_deskripsi' => 'nullable|required_if:status,pending|string',
            'escalate_deskripsi' => 'nullable|required_if:status,escalate|string',
            'tanggal_selesai' => 'nullable|date',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        try {
            $data = $request->all();
            $oldStatus = $laporan->status;

            // Set tanggal selesai jika status done
            if ($request->status == 'done' && !$request->tanggal_selesai) {
                $data['tanggal_selesai'] = now();
            }

            // Clear deskripsi yang tidak sesuai dengan status
            if ($request->status != 'pending') {
                $data['pending_deskripsi'] = null;
            }

            if ($request->status != 'escalate') {
                $data['escalate_deskripsi'] = null;
            }

            // Jika hanya update status (tanpa file upload)
            if (!$request->hasFile('lampiran') && !$request->has('deleted_files')) {
                // Preserve existing lampiran
                $data['lampiran'] = $laporan->lampiran;
            } else {
                // Get existing lampiran
                $existingLampiran = json_decode($laporan->lampiran, true) ?? [];

                // Upload multiple lampiran baru jika ada
                if ($request->hasFile('lampiran')) {
                    $newLampiranPaths = [];
                    foreach ($request->file('lampiran') as $file) {
                        if ($file->isValid()) {
                            $fileName = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                            $filePath = $file->storeAs('lampiran', $fileName, 'public');
                            $newLampiranPaths[] = $filePath;
                        }
                    }
                    $data['lampiran'] = json_encode(array_merge($existingLampiran, $newLampiranPaths));
                } else {
                    $data['lampiran'] = json_encode($existingLampiran);
                }

                // Handle file yang dihapus
                if ($request->has('deleted_files')) {
                    $deletedFiles = json_decode($request->deleted_files, true) ?? [];
                    foreach ($deletedFiles as $file) {
                        Storage::disk('public')->delete($file);
                    }
                    $remainingFiles = array_diff($existingLampiran, $deletedFiles);
                    $data['lampiran'] = json_encode($remainingFiles);
                }
            }

            // Jika status berubah menjadi done, hapus cache reminder
            if ($request->status == 'done' && $oldStatus != 'done') {
                Cache::forget('reminder_' . $laporan->id);
                Cache::forget('reminder_count_' . $laporan->id);
            }

            $laporan->update($data);
            $laporan->fresh(['kantor', 'jenisAplikasi', 'produk']);
            $newData = $laporan->toArray();

            // Log activity
            $this->logUpdate(
                'LAPORAN',
                $laporan->id,
                "Mengupdate laporan tiket {$laporan->nomor_ticket}",
                $oldData,
                $newData
            );

            // Kirim notifikasi Telegram jika status berubah
            if ($oldStatus != $request->status) {
                $this->sendStatusUpdateNotification($laporan, $oldStatus);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Laporan berhasil diperbarui'
                ]);
            }

            return redirect()->route('laporan.index')
                ->with('success', 'Laporan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating laporan: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete untuk menu laporan
        $this->checkPermission('laporan', 'delete');

        try {
            $laporan = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])->findOrFail($id);
            $laporanData = $laporan->toArray();

            $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
            if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk menghapus laporan ini'
                ], 403);
            }

            // Hapus semua file lampiran jika ada
            $lampiran = json_decode($laporan->lampiran, true) ?? [];
            foreach ($lampiran as $file) {
                Storage::disk('public')->delete($file);
            }

            // Hapus cache reminder
            Cache::forget('reminder_' . $laporan->id);
            Cache::forget('reminder_count_' . $laporan->id);

            $laporan->delete();

            // Log activity
            $this->logDelete(
                'LAPORAN',
                $laporan->id,
                "Menghapus laporan tiket {$laporan->nomor_ticket} - {$laporan->nama_pelapor}",
                $laporanData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting laporan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status laporan (quick action)
     */
    public function updateStatus(Request $request, $id)
    {
        // Cek permission update_status untuk menu laporan
        $this->checkPermission('laporan', 'update_status');

        $request->validate([
            'status' => 'required|in:open,process,done,reject,pending,escalate',
        ]);

        try {
            $laporan = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])->findOrFail($id);
            $oldData = $laporan->toArray();
            $oldStatus = $laporan->status;

            // Validasi akses berdasarkan role
            $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
            if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengupdate status laporan ini'
                ], 403);
            }

            $data = ['status' => $request->status];

            if ($request->status == 'done') {
                $data['tanggal_selesai'] = now();
                // Hapus cache reminder jika status menjadi done
                Cache::forget('reminder_' . $laporan->id);
                Cache::forget('reminder_count_' . $laporan->id);
            }

            $laporan->update($data);
            $laporan->fresh(['kantor', 'jenisAplikasi', 'produk']);
            $newData = $laporan->toArray();

            // Log activity untuk update status
            $this->logUpdate(
                'LAPORAN',
                $laporan->id,
                "Mengubah status laporan tiket {$laporan->nomor_ticket} dari {$oldStatus} menjadi {$request->status}",
                $oldData,
                $newData
            );

            // Kirim notifikasi Telegram jika status berubah
            if ($oldStatus != $request->status) {
                $this->sendStatusUpdateNotification($laporan, $oldStatus);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status laporan berhasil diperbarui'
                ]);
            }

            return redirect()->route('laporan.index')
                ->with('success', 'Status laporan berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Generate WhatsApp message for completed report
     */
    public function generateWhatsappMessage($id)
    {
        // Cek permission wa untuk menu laporan
        $this->checkPermission('laporan', 'wa');

        try {
            $laporan = Laporan::with(['kantor', 'jenisAplikasi', 'produk'])->findOrFail($id);

            // Validasi akses berdasarkan role
            $allowedAplikasiIds = RoleAplikasiHelper::getAplikasiIdsByRole();
            if (!in_array($laporan->jenis_aplikasi_id, $allowedAplikasiIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengirim WhatsApp untuk laporan ini'
                ], 403);
            }

            // Validasi status harus done
            if ($laporan->status != 'done') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hanya laporan dengan status Done yang dapat dikirim via WhatsApp'
                ], 400);
            }

            // Format nomor handphone
            $phoneNumber = preg_replace('/[^0-9]/', '', $laporan->no_handphone);

            if (empty($phoneNumber)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor handphone tidak valid'
                ], 400);
            }

            if (substr($phoneNumber, 0, 1) == '0') {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            }

            // Log activity untuk WhatsApp
            $this->logActivity(
                'WHATSAPP',
                "Mengirim pesan WhatsApp untuk tiket {$laporan->nomor_ticket} ke nomor {$phoneNumber}",
                'LAPORAN',
                $laporan->id,
                null,
                ['nomor_tujuan' => $phoneNumber]
            );

            // Buat pesan WhatsApp
            $message = $this->formatWhatsAppMessage($laporan);

            // Kirim notifikasi ke Telegram
            $this->sendWhatsAppNotification($laporan, $phoneNumber);

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'phone_number' => $phoneNumber,
                'data' => [
                    'nomor_ticket' => $laporan->nomor_ticket,
                    'nama_pelapor' => $laporan->nama_pelapor,
                    'status' => $laporan->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses pesan WhatsApp: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to Telegram
     */
    private function sendTelegramNotification($laporan)
    {
        try {
            // Load relationships jika belum di-load
            $laporan->load(['kantor', 'jenisAplikasi', 'produk']);

            // Kirim pesan utama dengan format lengkap
            $message = $this->telegramService->formatLaporanMessage($laporan);
            $this->telegramService->sendMessage($message);

            // Kirim lampiran jika ada
            $lampiran = json_decode($laporan->lampiran, true) ?? [];

            foreach ($lampiran as $index => $filePath) {
                $fullPath = storage_path('app/public/' . $filePath);

                if (file_exists($fullPath)) {
                    $caption = "📎 Lampiran " . ($index + 1) . " untuk tiket: {$laporan->nomor_ticket}";

                    // Cek tipe file
                    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                        $this->telegramService->sendPhoto($fullPath, $caption);
                    } else {
                        $this->telegramService->sendDocument($fullPath, $caption);
                    }

                    // Delay kecil agar tidak kena rate limit
                    usleep(500000); // 0.5 detik
                }
            }

            Log::info('Telegram notification sent for ticket: ' . $laporan->nomor_ticket);

        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification: ' . $e->getMessage());
        }
    }

    /**
     * Send status update notification
     */
    private function sendStatusUpdateNotification($laporan, $oldStatus)
    {
        try {
            $statusEmoji = [
                'open' => '🟡',
                'process' => '🔵',
                'done' => '🟢',
                'reject' => '🔴',
                'pending' => '🟠',
                'escalate' => '🔴'
            ];

            $message = "<b>🔄 STATUS LAPORAN BERUBAH</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━\n";
            $message .= "<b>🆔 Tiket:</b> <code>{$laporan->nomor_ticket}</code>\n";
            $message .= "<b>📊 Status:</b> {$statusEmoji[$oldStatus]} {$oldStatus} → {$statusEmoji[$laporan->status]} <b>{$laporan->status}</b>\n";
            $message .= "<b>👤 Pelapor:</b> {$laporan->nama_pelapor}\n";
            $message .= "<b>📱 Aplikasi:</b> {$laporan->jenisAplikasi->jenis_aplikasi}\n";
            $message .= "<b>📦 Produk:</b> {$laporan->produk->nama_produk}\n";
            $message .= "<b>📅 Update:</b> " . now()->format('d/m/Y H:i') . "\n\n";

            // Tambahkan deskripsi berdasarkan status
            if ($laporan->status == 'pending' && $laporan->pending_deskripsi) {
                $message .= "<b>⏳ ALASAN PENDING:</b>\n";
                $message .= "└ " . wordwrap($laporan->pending_deskripsi, 50, "\n  ") . "\n\n";
            }

            if ($laporan->status == 'escalate' && $laporan->escalate_deskripsi) {
                $message .= "<b>⬆️ ALASAN ESCALATE:</b>\n";
                $message .= "└ " . wordwrap($laporan->escalate_deskripsi, 50, "\n  ") . "\n\n";
            }

            if ($laporan->status == 'done' && $laporan->solusi) {
                $message .= "<b>✅ SOLUSI:</b>\n";
                $message .= "└ " . wordwrap($laporan->solusi, 50, "\n  ") . "\n\n";
            }

            $message .= "<a href='" . route('laporan.show', $laporan->id) . "'>🔗 Lihat Detail</a>";

            $this->telegramService->sendMessage($message);

        } catch (\Exception $e) {
            Log::error('Failed to send status update: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp notification to Telegram
     */
    private function sendWhatsAppNotification($laporan, $phoneNumber)
    {
        try {
            // Load relationships jika belum di-load
            if (!$laporan->relationLoaded('kantor') || !$laporan->relationLoaded('jenisAplikasi')) {
                $laporan->load(['kantor', 'jenisAplikasi', 'produk']);
            }

            // Format tanggal
            $tanggalSelesai = $laporan->tanggal_selesai
                ? $laporan->tanggal_selesai->format('d/m/Y H:i')
                : now()->format('d/m/Y H:i');

            // Buat pesan notifikasi
            $message = "<b>📱 PESAN WHATSAPP TERKIRIM</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━\n\n";

            $message .= "<b>🆔 Tiket:</b> <code>{$laporan->nomor_ticket}</code>\n";
            $message .= "<b>📊 Status:</b> 🟢 DONE (Selesai)\n\n";

            $message .= "<b>👤 Data Pelapor:</b>\n";
            $message .= "└ Nama: {$laporan->nama_pelapor}\n";
            $message .= "└ No. HP Tujuan: <code>{$phoneNumber}</code>\n";
            $message .= "└ Kantor: {$laporan->kantor->nama_cabang}\n\n";

            $message .= "<b>📝 Detail Laporan:</b>\n";
            $message .= "└ Aplikasi: {$laporan->jenisAplikasi->jenis_aplikasi}\n";
            $message .= "└ Produk: {$laporan->produk->kode_produk} - {$laporan->produk->nama_produk}\n";
            $message .= "└ Tanggal Laporan: " . $laporan->tanggal_laporan->format('d/m/Y H:i') . "\n";
            $message .= "└ Tanggal Selesai: {$tanggalSelesai}\n\n";

            if (!empty($laporan->solusi)) {
                $message .= "<b>✅ Solusi:</b>\n";
                $message .= "└ " . wordwrap($laporan->solusi, 40, "\n  ") . "\n\n";
            }

            $message .= "<b>📱 Isi Pesan WhatsApp:</b>\n";
            $message .= "└ " . wordwrap($this->getWhatsAppPreview($laporan), 40, "\n  ") . "\n\n";

            $message .= "<b>📅 Waktu Kirim:</b> " . now()->format('d/m/Y H:i:s') . "\n\n";

            // Tambahkan link WhatsApp yang sudah dibuat
            $waLink = "https://wa.me/{$phoneNumber}?text=" . urlencode($this->formatWhatsAppMessage($laporan));
            $message .= "<a href='{$waLink}'>🔗 Link Pesan WhatsApp</a>\n\n";

            $message .= "━━━━━━━━━━━━━━━━━━━\n";
            $message .= "<i>Notifikasi pengiriman follow-up WhatsApp</i>\n";
            $message .= "<i>E-Ticketing System</i>";

            // Kirim pesan ke Telegram
            $result = $this->telegramService->sendMessage($message);

            if ($result) {
                Log::info('WhatsApp notification sent to Telegram for ticket: ' . $laporan->nomor_ticket);
            } else {
                Log::warning('Failed to send WhatsApp notification to Telegram for ticket: ' . $laporan->nomor_ticket);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    /**
     * Get WhatsApp message preview (short version)
     */
    private function getWhatsAppPreview($laporan)
    {
        $preview = "LAPORAN TELAH SELESAI - Tiket: {$laporan->nomor_ticket}\n";
        $preview .= "Kepada Yth. {$laporan->nama_pelapor},\n";
        $preview .= "Laporan Anda dengan tiket {$laporan->nomor_ticket} telah selesai diproses.";

        if (!empty($laporan->solusi)) {
            $preview .= " Solusi: " . substr($laporan->solusi, 0, 50) . (strlen($laporan->solusi) > 50 ? '...' : '');
        }

        return $preview;
    }

    /**
     * Format WhatsApp message - Bullet Point Style
     */
    private function formatWhatsAppMessage($laporan)
    {
        $message = "✅ *LAPORAN TELAH SELESAI* ✅\n";
        $message .= "========================================\n\n";

        $message .= "• *TICKET* \n";
        $message .= "  {$laporan->nomor_ticket}\n\n";

        $message .= "• *DATA PELAPOR* \n";
        $message .= "  👤 Nama    : {$laporan->nama_pelapor}\n";
        $message .= "  📱 No. HP  : {$laporan->no_handphone}\n";
        $message .= "  🏢 Kantor  : " . ($laporan->kantor->nama_cabang ?? 'N/A') . "\n\n";

        $message .= "• *DETAIL LAPORAN* \n";
        $message .= "  📱 Aplikasi : " . ($laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A') . "\n";
        $message .= "  📦 Produk   : " . ($laporan->produk->kode_produk ?? 'N/A') . "\n";
        $message .= "  📅 Tanggal  : " . $laporan->tanggal_laporan->format('d/m/Y H:i') . " WIT\n";
        $message .= "  ✅ Selesai  : " . ($laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d/m/Y H:i') : now()->format('d/m/Y H:i')) . " WIT\n\n";

        $message .= "• *KRONOLOGI* \n";
        $message .= "  {$laporan->kronologi}\n\n";

        if (!empty($laporan->solusi)) {
            $message .= "• *SOLUSI* \n";
            $message .= "  {$laporan->solusi}\n\n";
        }

        $message .= "• *STATUS* \n";
        $message .= "  ✅ SELESAI / DONE\n\n";

        $message .= "========================================\n";
        $message .= "🙏 Terima kasih\n";
        $message .= "✨ Tim IT BPR MODERN EXPRESS ✨";

        return $message;
    }
}

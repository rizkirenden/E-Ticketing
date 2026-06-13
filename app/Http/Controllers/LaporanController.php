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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanController extends Controller
{
    use LogsActivity, HasPermission;

    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
        // REMINDER TELAH DIPINDAHKAN KE SCHEDULER/COMMAND
        // Tidak ada lagi pemanggilan reminder di constructor
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
            $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();
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

        // ========== TAMBAHKAN HISTORY PERUBAHAN STATUS ==========
        if ($oldStatus != $request->status) {
            $description = null;
            if ($request->status == 'pending') {
                $description = $request->pending_deskripsi;
            } elseif ($request->status == 'escalate') {
                $description = $request->escalate_deskripsi;
            } elseif ($request->status == 'done') {
                $description = $request->solusi ?? $laporan->solusi;
            }

            $laporan->addHistory($oldStatus, $request->status, $description);
        }
        // ========== END TAMBAHAN HISTORY ==========

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
        'pending_deskripsi' => 'required_if:status,pending|nullable|string',
        'escalate_deskripsi' => 'required_if:status,escalate|nullable|string',
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

        // Handle deskripsi berdasarkan status
        if ($request->status == 'pending') {
            $data['pending_deskripsi'] = $request->pending_deskripsi;
            $data['escalate_deskripsi'] = null;
        } elseif ($request->status == 'escalate') {
            $data['escalate_deskripsi'] = $request->escalate_deskripsi;
            $data['pending_deskripsi'] = null;
        } else {
            $data['pending_deskripsi'] = null;
            $data['escalate_deskripsi'] = null;
        }

        if ($request->status == 'done') {
            $data['tanggal_selesai'] = now();
            // Hapus cache reminder jika status menjadi done
            Cache::forget('reminder_' . $laporan->id);
            Cache::forget('reminder_count_' . $laporan->id);
        }

        $laporan->update($data);
        $laporan->fresh(['kantor', 'jenisAplikasi', 'produk']);
        $newData = $laporan->toArray();

        // ========== TAMBAHKAN HISTORY PERUBAHAN STATUS ==========
        $description = null;
        if ($request->status == 'pending') {
            $description = $request->pending_deskripsi;
        } elseif ($request->status == 'escalate') {
            $description = $request->escalate_deskripsi;
        } elseif ($request->status == 'done') {
            $description = $laporan->solusi;
        }

        $laporan->addHistory($oldStatus, $request->status, $description);
        // ========== END TAMBAHAN HISTORY ==========

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
        $message .= "  📅 Tanggal  : " . $laporan->tanggal_laporan->format('d/m/Y H:i') . " WIB\n";
        $message .= "  ✅ Selesai  : " . ($laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d/m/Y H:i') : now()->format('d/m/Y H:i')) . " WIB\n\n";

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
/**
 * Export laporan ke Excel
 */
public function exportExcel(Request $request)
{
    $this->checkPermission('laporan', 'excel');

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

    // Hitung total laporan
    $totalLaporan = $laporans->count();

    // Hitung laporan per aplikasi
    $laporanPerAplikasi = [];
    foreach ($laporans as $laporan) {
        $namaAplikasi = $laporan->jenisAplikasi->jenis_aplikasi ?? 'Tidak Diketahui';
        if (!isset($laporanPerAplikasi[$namaAplikasi])) {
            $laporanPerAplikasi[$namaAplikasi] = 0;
        }
        $laporanPerAplikasi[$namaAplikasi]++;
    }

    // Hitung total aplikasi yang muncul (unique)
    $totalAplikasi = count($laporanPerAplikasi);

    // Buat Spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ============================================
    // HEADER SECTION (Logo di N-P, Judul di kiri)
    // ============================================

    // Judul Laporan (di kiri)
    $sheet->setCellValue('A1', 'LAPORAN TIKET DUKUNGAN');
    $sheet->mergeCells('A1:C1');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => '001D39']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ]);

    // Load logo di kolom N sampai P (tengah kanan)
    $logoPath = public_path('assets/logo4.png');
    if (file_exists($logoPath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath($logoPath);
        $drawing->setHeight(60);
        $drawing->setCoordinates('N1');
        $drawing->setWorksheet($sheet);

        // Merge kolom N, O, P untuk logo
        $sheet->mergeCells('N1:P1');
        $sheet->getStyle('N1:P1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // Informasi Filter (baris 2)
    $filterInfo = [];
    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $filterInfo[] = "Periode: " . date('d/m/Y', strtotime($request->tanggal_awal)) . " - " . date('d/m/Y', strtotime($request->tanggal_akhir));
    } elseif ($request->filled('tanggal_awal')) {
        $filterInfo[] = "Dari: " . date('d/m/Y', strtotime($request->tanggal_awal));
    } elseif ($request->filled('tanggal_akhir')) {
        $filterInfo[] = "Sampai: " . date('d/m/Y', strtotime($request->tanggal_akhir));
    }

    if ($request->filled('kantor')) {
        $kantor = \App\Models\Kantor::find($request->kantor);
        if ($kantor) $filterInfo[] = "Kantor: " . $kantor->nama_cabang;
    }

    if ($request->filled('aplikasi')) {
        $aplikasi = \App\Models\JenisAplikasi::find($request->aplikasi);
        if ($aplikasi) $filterInfo[] = "Aplikasi: " . $aplikasi->jenis_aplikasi;
    }

    if ($request->filled('status')) {
        $filterInfo[] = "Status: " . ucfirst($request->status);
    }

    if ($request->filled('search')) {
        $filterInfo[] = "Pencarian: " . $request->search;
    }

    $sheet->setCellValue('A2', 'Filter: ' . (count($filterInfo) > 0 ? implode(' | ', $filterInfo) : 'Semua Data'));
    $sheet->mergeCells('A2:P2');
    $sheet->getStyle('A2')->applyFromArray([
        'font' => [
            'italic' => true,
            'size' => 10
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F0F0F0']
        ]
    ]);

    // ============================================
    // TOTAL LAPORAN (HANYA DI KOLOM A)
    // ============================================
    $sheet->setCellValue('A3', 'Total Laporan: ' . $totalLaporan);
    $sheet->getStyle('A3')->applyFromArray([
        'font' => ['bold' => true, 'size' => 11]
    ]);

    // ============================================
    // TABEL LAPORAN PER APLIKASI (DIMULAI DARI KOLOM A)
    // ============================================
    $sheet->setCellValue('A4', 'RINCIAN LAPORAN PER APLIKASI:');
    $sheet->getStyle('A4')->applyFromArray([
        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '001D39']]
    ]);

    // Header tabel statistik
    $statRow = 5;
    $sheet->setCellValue('A' . $statRow, 'NO');
    $sheet->setCellValue('B' . $statRow, 'NAMA APLIKASI');
    $sheet->setCellValue('C' . $statRow, 'JUMLAH LAPORAN');
    $sheet->setCellValue('D' . $statRow, 'PERSENTASE');

    $sheet->getStyle('A' . $statRow . ':D' . $statRow)->applyFromArray([
        'font' => ['bold' => true, 'size' => 10],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D9E1F2']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC']
            ]
        ]
    ]);

    // Isi tabel statistik
    $noStat = 1;
    $statDataRow = $statRow + 1;
    foreach ($laporanPerAplikasi as $aplikasi => $jumlah) {
        $persentase = $totalLaporan > 0 ? round(($jumlah / $totalLaporan) * 100, 2) : 0;

        $sheet->setCellValue('A' . $statDataRow, $noStat);
        $sheet->setCellValue('B' . $statDataRow, $aplikasi);
        $sheet->setCellValue('C' . $statDataRow, $jumlah);
        $sheet->setCellValue('D' . $statDataRow, $persentase . '%');

        $sheet->getStyle('A' . $statDataRow . ':D' . $statDataRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        $statDataRow++;
        $noStat++;
    }

    // Total baris di statistik
    $sheet->setCellValue('B' . $statDataRow, 'TOTAL');
    $sheet->setCellValue('C' . $statDataRow, $totalLaporan);
    $sheet->setCellValue('D' . $statDataRow, '100%');
    $sheet->getStyle('B' . $statDataRow . ':D' . $statDataRow)->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E8E8E8']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC']
            ]
        ]
    ]);

    // Atur lebar kolom statistik (auto-fit)
    $sheet->getColumnDimension('A')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(18);
    $sheet->getColumnDimension('D')->setWidth(15);

    // Baris kosong sebelum tabel data utama
    $startRow = $statDataRow + 2;

    // ============================================
    // TABLE HEADER (DATA UTAMA)
    // ============================================

    // Header Style
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 11
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '001D39']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC']
            ]
        ]
    ];

    // Body Style
    $bodyStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC']
            ]
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_TOP
        ]
    ];

    // Status style mapping
    $statusColors = [
        'open' => 'FFD966',
        'process' => '9CC3E5',
        'done' => 'A9D08E',
        'reject' => 'F4B084',
        'pending' => 'F9CB9C',
        'escalate' => 'E6B8B7'
    ];

    // Header columns untuk data utama
    $headers = [
        'NO' => 'A',
        'NOMOR TICKET' => 'B',
        'TANGGAL LAPORAN' => 'C',
        'NAMA PELAPOR' => 'D',
        'NO. HANDPHONE' => 'E',
        'KANTOR' => 'F',
        'APLIKASI' => 'G',
        'PRODUK' => 'H',
        'KRONOLOGI' => 'I',
        'SOLUSI' => 'J',
        'STATUS' => 'K',
        'TANGGAL SELESAI' => 'L',
        'PENDING DESCRIPTION' => 'M',
        'ESCALATE DESCRIPTION' => 'N',
        'DIBUAT PADA' => 'O',
        'DIUPDATE PADA' => 'P'
    ];

    // Set header values dan style
    foreach ($headers as $header => $column) {
        $sheet->setCellValue($column . $startRow, $header);
        $sheet->getStyle($column . $startRow)->applyFromArray($headerStyle);
    }

    // ============================================
    // SET LEBAR KOLOM OTOMATIS (auto-fit)
    // ============================================

    // Kolom NO (A) - lebar 8
    $sheet->getColumnDimension('A')->setWidth(8);

    // Kolom NOMOR TICKET (B) - auto width
    $sheet->getColumnDimension('B')->setWidth(22);

    // Kolom TANGGAL LAPORAN (C) - auto width
    $sheet->getColumnDimension('C')->setWidth(18);

    // Kolom NAMA PELAPOR (D) - auto width
    $sheet->getColumnDimension('D')->setWidth(25);

    // Kolom NO. HANDPHONE (E) - auto width
    $sheet->getColumnDimension('E')->setWidth(18);

    // Kolom KANTOR (F) - auto width
    $sheet->getColumnDimension('F')->setWidth(25);

    // Kolom APLIKASI (G) - auto width
    $sheet->getColumnDimension('G')->setWidth(20);

    // Kolom PRODUK (H) - auto width
    $sheet->getColumnDimension('H')->setWidth(30);

    // Kolom KRONOLOGI (I) - lebar besar untuk wrap text
    $sheet->getColumnDimension('I')->setWidth(50);

    // Kolom SOLUSI (J) - lebar besar untuk wrap text
    $sheet->getColumnDimension('J')->setWidth(50);

    // Kolom STATUS (K) - auto width
    $sheet->getColumnDimension('K')->setWidth(12);

    // Kolom TANGGAL SELESAI (L) - auto width
    $sheet->getColumnDimension('L')->setWidth(18);

    // Kolom PENDING DESCRIPTION (M) - auto width
    $sheet->getColumnDimension('M')->setWidth(30);

    // Kolom ESCALATE DESCRIPTION (N) - auto width
    $sheet->getColumnDimension('N')->setWidth(35);

    // Kolom DIBUAT PADA (O) - auto width
    $sheet->getColumnDimension('O')->setWidth(18);

    // Kolom DIUPDATE PADA (P) - auto width
    $sheet->getColumnDimension('P')->setWidth(18);

    // ============================================
    // DATA TABLE (DATA UTAMA)
    // ============================================

    $row = $startRow + 1;
    $no = 1;

    foreach ($laporans as $laporan) {
        $status = $laporan->status;
        $statusText = ucfirst($status);

        $sheet->setCellValue('A' . $row, $no);
        $sheet->setCellValue('B' . $row, $laporan->nomor_ticket);
        $sheet->setCellValue('C' . $row, $laporan->tanggal_laporan ? $laporan->tanggal_laporan->format('d/m/Y H:i') : '');
        $sheet->setCellValue('D' . $row, $laporan->nama_pelapor);
        $sheet->setCellValue('E' . $row, $laporan->no_handphone);
        $sheet->setCellValue('F' . $row, $laporan->kantor->nama_cabang ?? '');
        $sheet->setCellValue('G' . $row, $laporan->jenisAplikasi->jenis_aplikasi ?? '');
        $sheet->setCellValue('H' . $row, ($laporan->produk->kode_produk ?? '') . ' - ' . ($laporan->produk->nama_produk ?? ''));
        $sheet->setCellValue('I' . $row, $laporan->kronologi);
        $sheet->setCellValue('J' . $row, $laporan->solusi ?? '');
        $sheet->setCellValue('K' . $row, $statusText);
        $sheet->setCellValue('L' . $row, $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d/m/Y H:i') : '');
        $sheet->setCellValue('M' . $row, $laporan->pending_deskripsi ?? '');
        $sheet->setCellValue('N' . $row, $laporan->escalate_deskripsi ?? '');
        $sheet->setCellValue('O' . $row, $laporan->created_at ? $laporan->created_at->format('d/m/Y H:i') : '');
        $sheet->setCellValue('P' . $row, $laporan->updated_at ? $laporan->updated_at->format('d/m/Y H:i') : '');

        $sheet->getStyle('A' . $row . ':P' . $row)->applyFromArray($bodyStyle);
        $sheet->getRowDimension($row)->setRowHeight(-1);

        $statusColor = isset($statusColors[$status]) ? $statusColors[$status] : 'FFFFFF';
        $sheet->getStyle('K' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB($statusColor);

        $row++;
        $no++;
    }

    // Wrap text untuk kolom tertentu
    $lastDataRow = $row - 1;
    if ($lastDataRow >= $startRow + 1) {
        $sheet->getStyle('I' . ($startRow + 1) . ':I' . $lastDataRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('J' . ($startRow + 1) . ':J' . $lastDataRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('M' . ($startRow + 1) . ':M' . $lastDataRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('N' . ($startRow + 1) . ':N' . $lastDataRow)->getAlignment()->setWrapText(true);
    }

    // ============================================
    // FOOTER SECTION (Tanggal cetak di bawah)
    // ============================================

    $footerRow = $lastDataRow + 2;

    // Total Laporan di footer (hanya di kolom A)
    $sheet->setCellValue('A' . $footerRow, 'Total Data: ' . $totalLaporan . ' Laporan');
    $sheet->getStyle('A' . $footerRow)->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E8E8E8']
        ]
    ]);

    // Tanggal cetak di footer (sebelah kanan)
    $sheet->setCellValue('P' . $footerRow, 'Tanggal Cetak: ' . date('d/m/Y H:i:s'));
    $sheet->getStyle('P' . $footerRow)->applyFromArray([
        'font' => ['italic' => true, 'size' => 9],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
    ]);

    // Tanda tangan
    $signRow = $footerRow + 2;
    $sheet->setCellValue('P' . $signRow, 'Dicetak oleh: ' . ($user->name ?? $user->username ?? 'System'));
    $sheet->getStyle('P' . $signRow)->applyFromArray([
        'font' => ['italic' => true, 'size' => 9],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
    ]);

    // ============================================
    // FREEZE PANE - Hanya membekukan baris header (tanpa freeze kolom)
    // ============================================
    $sheet->freezePane('A' . ($startRow + 1));
    $sheet->setSelectedCell('A1');

    // ============================================
    // GENERATE FILE
    // ============================================

    $filename = 'laporan_' . date('Y-m-d_His') . '.xlsx';

    $this->logActivity(
        'EXPORT_EXCEL',
        "Mengekspor data laporan ke Excel dengan filter: " . json_encode($request->all()),
        'LAPORAN',
        null,
        null,
        [
            'filters' => $request->all(),
            'total_data' => $totalLaporan,
            'total_aplikasi' => $totalAplikasi,
            'laporan_per_aplikasi' => $laporanPerAplikasi
        ]
    );

    // Clean output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
}

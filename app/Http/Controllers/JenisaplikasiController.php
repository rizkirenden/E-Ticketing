<?php

namespace App\Http\Controllers;

use App\Models\Jenisaplikasi;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Validator;

class JenisaplikasiController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu jenis-aplikasi
        if (!PermissionHelper::can('jenis-aplikasi', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $jenisAplikasis = Jenisaplikasi::when($request->search, function($query, $search) {
            return $query->where('kode_jenis_aplikasi', 'like', "%{$search}%")
                         ->orWhere('jenis_aplikasi', 'like', "%{$search}%")
                         ->orWhere('deskripsi', 'like', "%{$search}%");
        })->orderBy('jenis_aplikasi', 'asc')
          ->paginate(10);

        // Dapatkan permission buttons untuk menu jenis-aplikasi
        $permissions = [
            'can_create' => PermissionHelper::can('jenis-aplikasi', 'create'),
            'can_edit' => PermissionHelper::can('jenis-aplikasi', 'edit'),
            'can_delete' => PermissionHelper::can('jenis-aplikasi', 'delete'),
            'can_export' => PermissionHelper::can('jenis-aplikasi', 'export'),
            'can_import' => PermissionHelper::can('jenis-aplikasi', 'import')
        ];

        if ($request->ajax()) {
            return view('jenis_aplikasi.table', compact('jenisAplikasis', 'permissions'))->render();
        }

        return view('jenis_aplikasi.index', compact('jenisAplikasis', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('jenis-aplikasi', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'kode_jenis_aplikasi' => 'required|string|max:100|unique:jenis_aplikasis,kode_jenis_aplikasi',
            'jenis_aplikasi' => 'required|string|max:100|unique:jenis_aplikasis,jenis_aplikasi',
            'deskripsi' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $jenisAplikasi = Jenisaplikasi::create($request->all());

            // Log activity
            $this->logCreate(
                'JENIS_APLIKASI',
                $jenisAplikasi->id,
                "Menambahkan jenis aplikasi baru: {$jenisAplikasi->kode_jenis_aplikasi} - {$jenisAplikasi->jenis_aplikasi}",
                $jenisAplikasi->toArray()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data jenis aplikasi berhasil ditambahkan',
                'data' => $jenisAplikasi
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error storing jenis aplikasi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data jenis aplikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('jenis-aplikasi', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $jenisAplikasi = Jenisaplikasi::findOrFail($id);
        $oldData = $jenisAplikasi->toArray();

        $validator = Validator::make($request->all(), [
            'kode_jenis_aplikasi' => 'required|string|max:100|unique:jenis_aplikasis,kode_jenis_aplikasi,' . $id,
            'jenis_aplikasi' => 'required|string|max:100|unique:jenis_aplikasis,jenis_aplikasi,' . $id,
            'deskripsi' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $jenisAplikasi->update($request->all());
            $newData = $jenisAplikasi->fresh()->toArray();

            // Log activity
            $this->logUpdate(
                'JENIS_APLIKASI',
                $jenisAplikasi->id,
                "Mengupdate jenis aplikasi: {$jenisAplikasi->kode_jenis_aplikasi} - {$jenisAplikasi->jenis_aplikasi}",
                $oldData,
                $newData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data jenis aplikasi berhasil diperbarui',
                'data' => $jenisAplikasi
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating jenis aplikasi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data jenis aplikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('jenis-aplikasi', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        try {
            $jenisAplikasi = Jenisaplikasi::findOrFail($id);

            // Cek apakah jenis aplikasi digunakan di tabel lain
            if ($jenisAplikasi->laporans()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data jenis aplikasi tidak dapat dihapus karena masih digunakan di data laporan'
                ], 400);
            }

            if ($jenisAplikasi->produks()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data jenis aplikasi tidak dapat dihapus karena masih digunakan di data produk'
                ], 400);
            }

            $jenisAplikasiData = $jenisAplikasi->toArray();

            $jenisAplikasi->delete();

            // Log activity
            $this->logDelete(
                'JENIS_APLIKASI',
                $jenisAplikasi->id,
                "Menghapus jenis aplikasi: {$jenisAplikasi->kode_jenis_aplikasi} - {$jenisAplikasi->jenis_aplikasi}",
                $jenisAplikasiData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data jenis aplikasi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting jenis aplikasi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data jenis aplikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}

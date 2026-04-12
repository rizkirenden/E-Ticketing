<?php

namespace App\Http\Controllers;

use App\Models\Roleaplikasi;
use App\Models\Role;
use App\Models\Jenisaplikasi;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper; // Tambahkan ini

class RoleaplikasiController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu role-aplikasi
        if (!PermissionHelper::can('role-aplikasi', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $roleaplikasis = Roleaplikasi::with(['role', 'jenisAplikasi'])
            ->when($request->search, function($query, $search) {
                return $query->whereHas('role', function($q) use ($search) {
                        $q->where('nama_role', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jenisAplikasi', function($q) use ($search) {
                        $q->where('jenis_aplikasi', 'like', "%{$search}%");
                    });
            })->paginate(10);

        // Ambil data roles dan jenis aplikasi untuk dropdown
        $roles = Role::orderBy('nama_role')->get();
        $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();

        // Dapatkan permission buttons untuk menu role-aplikasi
        $permissions = [
            'can_view' => PermissionHelper::can('role-aplikasi', 'view'),
            'can_create' => PermissionHelper::can('role-aplikasi', 'create'),
            'can_edit' => PermissionHelper::can('role-aplikasi', 'edit'),
            'can_delete' => PermissionHelper::can('role-aplikasi', 'delete'),
            'can_export' => PermissionHelper::can('role-aplikasi', 'export'),
            'can_import' => PermissionHelper::can('role-aplikasi', 'import')
        ];

        if ($request->ajax()) {
            return view('role_aplikasi.table', compact('roleaplikasis', 'permissions'))->render();
        }

        return view('role_aplikasi.index', compact('roleaplikasis', 'roles', 'jenisAplikasis', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('role-aplikasi', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'jenis_aplikasi_id' => 'required|exists:jenis_aplikasis,id'
        ]);

        // Cek duplikasi
        $exists = Roleaplikasi::where('role_id', $request->role_id)
            ->where('jenis_aplikasi_id', $request->jenis_aplikasi_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role dan Jenis Aplikasi sudah terhubung'
            ], 422);
        }

        try {
            $roleaplikasi = Roleaplikasi::create($request->all());

            // Load relations untuk deskripsi yang lebih baik
            $roleaplikasi->load(['role', 'jenisAplikasi']);

            // Log activity
            $this->logCreate(
                'ROLE_APLIKASI',
                $roleaplikasi->id,
                "Menambahkan akses role {$roleaplikasi->role->nama_role} ke aplikasi {$roleaplikasi->jenisAplikasi->jenis_aplikasi}",
                $roleaplikasi->toArray()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data role aplikasi berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data role aplikasi'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('role-aplikasi', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $roleaplikasi = Roleaplikasi::findOrFail($id);
        $oldData = $roleaplikasi->toArray();

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'jenis_aplikasi_id' => 'required|exists:jenis_aplikasis,id'
        ]);

        // Cek duplikasi kecuali data yang sama
        $exists = Roleaplikasi::where('role_id', $request->role_id)
            ->where('jenis_aplikasi_id', $request->jenis_aplikasi_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role dan Jenis Aplikasi sudah terhubung'
            ], 422);
        }

        try {
            $roleaplikasi->update($request->all());
            $roleaplikasi->fresh(['role', 'jenisAplikasi']);
            $newData = $roleaplikasi->toArray();

            // Log activity
            $this->logUpdate(
                'ROLE_APLIKASI',
                $roleaplikasi->id,
                "Mengupdate akses role {$roleaplikasi->role->nama_role} ke aplikasi {$roleaplikasi->jenisAplikasi->jenis_aplikasi}",
                $oldData,
                $newData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data role aplikasi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data role aplikasi'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('role-aplikasi', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        try {
            $roleaplikasi = Roleaplikasi::with(['role', 'jenisAplikasi'])->findOrFail($id);
            $roleaplikasiData = $roleaplikasi->toArray();

            $roleaplikasi->delete();

            // Log activity
            $this->logDelete(
                'ROLE_APLIKASI',
                $roleaplikasi->id,
                "Menghapus akses role {$roleaplikasi->role->nama_role} dari aplikasi {$roleaplikasi->jenisAplikasi->jenis_aplikasi}",
                $roleaplikasiData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data role aplikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data role aplikasi'
            ], 500);
        }
    }

    /**
     * Export role aplikasi to Excel
     */
    public function export(Request $request)
    {
        // Cek permission export
        if (!PermissionHelper::can('role-aplikasi', 'export')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk export data'
            ], 403);
        }

        try {
            // Logika export Excel di sini

            return response()->json([
                'status' => 'success',
                'message' => 'Export data berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import role aplikasi from Excel
     */
    public function import(Request $request)
    {
        // Cek permission import
        if (!PermissionHelper::can('role-aplikasi', 'import')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk import data'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            // Logika import Excel di sini

            return response()->json([
                'status' => 'success',
                'message' => 'Import data berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }
}

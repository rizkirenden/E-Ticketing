<?php
// app/Http/Controllers/KantorController.php

namespace App\Http\Controllers;

use App\Models\Kantor;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper;

class KantorController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu kantor
        if (!PermissionHelper::can('kantor', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $kantors = Kantor::when($request->search, function($query, $search) {
            return $query->where('kode_cabang', 'like', "%{$search}%")
                         ->orWhere('nama_cabang', 'like', "%{$search}%")
                         ->orWhere('area', 'like', "%{$search}%");
        })->paginate(10);

        // Dapatkan permission buttons untuk menu kantor
        $permissions = [
            'can_create' => PermissionHelper::can('kantor', 'create'),
            'can_edit' => PermissionHelper::can('kantor', 'edit'),
            'can_delete' => PermissionHelper::can('kantor', 'delete'),
            'can_export' => PermissionHelper::can('kantor', 'export'),
            'can_import' => PermissionHelper::can('kantor', 'import')
        ];

        if ($request->ajax()) {
            return view('kantor.table', compact('kantors', 'permissions'))->render();
        }

        return view('kantor.index', compact('kantors', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('kantor', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $request->validate([
            'kode_cabang' => 'required|string|max:50|unique:kantors,kode_cabang',
            'nama_cabang' => 'required|string|max:50',
            'area' => 'required|string|max:50'
        ]);

        try {
            $kantor = Kantor::create($request->all());

            // Log activity
            $this->logCreate(
                'KANTOR',
                $kantor->id,
                "Menambahkan kantor baru: {$kantor->kode_cabang} - {$kantor->nama_cabang}",
                $kantor->toArray()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data kantor berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data kantor'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('kantor', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $kantor = Kantor::findOrFail($id);
        $oldData = $kantor->toArray();

        $request->validate([
            'kode_cabang' => 'required|string|max:50|unique:kantors,kode_cabang,' . $id,
            'nama_cabang' => 'required|string|max:50',
            'area' => 'required|string|max:50'
        ]);

        try {
            $kantor->update($request->all());
            $newData = $kantor->fresh()->toArray();

            // Log activity
            $this->logUpdate(
                'KANTOR',
                $kantor->id,
                "Mengupdate kantor: {$kantor->kode_cabang} - {$kantor->nama_cabang}",
                $oldData,
                $newData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data kantor berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data kantor'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('kantor', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        try {
            $kantor = Kantor::findOrFail($id);
            $kantorData = $kantor->toArray();

            $kantor->delete();

            // Log activity
            $this->logDelete(
                'KANTOR',
                $kantor->id,
                "Menghapus kantor: {$kantor->kode_cabang} - {$kantor->nama_cabang}",
                $kantorData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data kantor berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data kantor'
            ], 500);
        }
    }
}

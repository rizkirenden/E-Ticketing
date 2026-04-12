<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Jenisaplikasi;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu produk
        if (!PermissionHelper::can('produk', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $produks = Produk::with('jenisAplikasi')
            ->when($request->search, function($query, $search) {
                return $query->where('kode_produk', 'like', "%{$search}%")
                             ->orWhere('nama_produk', 'like', "%{$search}%")
                             ->orWhere('deskripsi', 'like', "%{$search}%")
                             ->orWhereHas('jenisAplikasi', function($q) use ($search) {
                                 $q->where('jenis_aplikasi', 'like', "%{$search}%"); // PERBAIKI: ganti 'nama' dengan 'jenis_aplikasi'
                             });
            })->paginate(10);

        // Dapatkan permission buttons untuk menu produk
        $permissions = [
            'can_create' => PermissionHelper::can('produk', 'create'),
            'can_edit' => PermissionHelper::can('produk', 'edit'),
            'can_delete' => PermissionHelper::can('produk', 'delete'),
            'can_export' => PermissionHelper::can('produk', 'export'),
            'can_import' => PermissionHelper::can('produk', 'import')
        ];

        $jenisAplikasis = Jenisaplikasi::orderBy('jenis_aplikasi')->get();

        if ($request->ajax()) {
            return view('produk.table', compact('produks', 'permissions', 'jenisAplikasis'))->render();
        }

        return view('produk.index', compact('produks', 'permissions', 'jenisAplikasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('produk', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'kode_produk' => 'required|string|max:100|unique:produks,kode_produk',
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jenis_aplikasi_id' => 'required|exists:jenis_aplikasis,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produk = Produk::create($request->all());

            // Log activity
            $this->logCreate(
                'PRODUK',
                $produk->id,
                "Menambahkan produk baru: {$produk->kode_produk} - {$produk->nama_produk}",
                $produk->toArray()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data produk berhasil ditambahkan',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error storing produk: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('produk', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $produk = Produk::findOrFail($id);
        $oldData = $produk->toArray();

        $validator = Validator::make($request->all(), [
            'kode_produk' => 'required|string|max:100|unique:produks,kode_produk,' . $id,
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jenis_aplikasi_id' => 'required|exists:jenis_aplikasis,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produk->update($request->all());
            $newData = $produk->fresh()->toArray();

            // Log activity
            $this->logUpdate(
                'PRODUK',
                $produk->id,
                "Mengupdate produk: {$produk->kode_produk} - {$produk->nama_produk}",
                $oldData,
                $newData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data produk berhasil diperbarui',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating produk: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('produk', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        try {
            $produk = Produk::findOrFail($id);
            $produkData = $produk->toArray();

            $produk->delete();

            // Log activity
            $this->logDelete(
                'PRODUK',
                $produk->id,
                "Menghapus produk: {$produk->kode_produk} - {$produk->nama_produk}",
                $produkData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting produk: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data produk: ' . $e->getMessage()
            ], 500);
        }
    }
}

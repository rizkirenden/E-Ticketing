<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper; // Tambahkan ini

class RoleController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu role
        if (!PermissionHelper::can('role', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $query = Role::query();

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_role', 'like', '%' . $request->search . '%');
        }

        // Dapatkan permission buttons untuk menu role
        $permissions = [
            'can_view' => PermissionHelper::can('role', 'view'),
            'can_create' => PermissionHelper::can('role', 'create'),
            'can_edit' => PermissionHelper::can('role', 'edit'),
            'can_delete' => PermissionHelper::can('role', 'delete'),
        ];

        // Cek apakah request dari AJAX
        if ($request->ajax()) {
            $roles = $query->orderBy('id', 'desc')->paginate(10);

            // Return view partial untuk tabel saja
            return view('role.table', compact('roles', 'permissions'))->render();
        }

        // Request biasa
        $roles = $query->orderBy('id', 'desc')->paginate(10);

        return view('role.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('role', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string|max:50|unique:roles,nama_role'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'nama_role' => $request->nama_role
        ]);

        // LOG ACTIVITY: CREATE
        $this->logCreate(
            'ROLE',                          // module
            $role->id,                       // moduleId
            "Menambahkan role baru: {$role->nama_role}", // description
            $role->toArray()                  // data
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil ditambahkan',
            'data' => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cek permission view
        if (!PermissionHelper::can('role', 'view')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk melihat data'
            ], 403);
        }

        $role = Role::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('role', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $role = Role::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('role', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string|max:50|unique:roles,nama_role,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::findOrFail($id);
        $oldData = $role->toArray(); // Simpan data lama sebelum update

        $role->update([
            'nama_role' => $request->nama_role
        ]);

        $newData = $role->toArray(); // Data setelah update

        // LOG ACTIVITY: UPDATE
        $this->logUpdate(
            'ROLE',                          // module
            $role->id,                       // moduleId
            'UPDATE',                        // action
            "Mengupdate role: {$role->nama_role}", // description
            $oldData,                        // oldData
            $newData                         // newData
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diupdate',
            'data' => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('role', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        $role = Role::findOrFail($id);
        $roleData = $role->toArray(); // Simpan data sebelum dihapus

        // Cek apakah role masih digunakan oleh user
        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role tidak dapat dihapus karena masih digunakan oleh user'
            ], 422);
        }

        $role->delete();

        // LOG ACTIVITY: DELETE
        $this->logDelete(
            'ROLE',                          // module
            $role->id,                       // moduleId
            "Menghapus role: {$roleData['nama_role']}", // description
            $roleData                        // data
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus'
        ]);
    }
}

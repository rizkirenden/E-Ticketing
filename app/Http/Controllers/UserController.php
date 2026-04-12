<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogsActivity;
use App\Helpers\PermissionHelper; // Tambahkan ini

class UserController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek permission view untuk menu user
        if (!PermissionHelper::can('user', 'view')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $users = User::with('role')
            ->when($request->search, function($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhereHas('role', function($q) use ($search) {
                        $q->where('nama_role', 'like', "%{$search}%");
                    });
            })->paginate(10);

        // Ambil data roles untuk dropdown
        $roles = Role::orderBy('nama_role')->get();

        // Dapatkan permission buttons untuk menu user
        $permissions = [
            'can_create' => PermissionHelper::can('user', 'create'),
            'can_edit' => PermissionHelper::can('user', 'edit'),
            'can_delete' => PermissionHelper::can('user', 'delete'),
            'can_export' => PermissionHelper::can('user', 'export'),
            'can_import' => PermissionHelper::can('user', 'import')
        ];

        if ($request->ajax()) {
            return view('user.table', compact('users', 'permissions'))->render();
        }

        return view('user.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek permission create
        if (!PermissionHelper::can('user', 'create')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah data'
            ], 403);
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::create([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id
            ]);

            // Log activity
            $this->logCreate(
                'USER',
                $user->id,
                "Menambahkan user baru: {$user->nama} ({$user->username})",
                $user->toArray()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data user berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data user'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek permission edit
        if (!PermissionHelper::can('user', 'edit')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit data'
            ], 403);
        }

        $user = User::findOrFail($id);
        $oldData = $user->toArray();

        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'username' => $request->username,
                'role_id' => $request->role_id
            ];

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $newData = $user->fresh()->toArray();

            // Log activity
            $this->logUpdate(
                'USER',
                $user->id,
                "Mengupdate user: {$user->nama} ({$user->username})",
                $oldData,
                $newData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data user berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data user'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek permission delete
        if (!PermissionHelper::can('user', 'delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data'
            ], 403);
        }

        try {
            $user = User::findOrFail($id);
            $userData = $user->toArray();

            // Cegah menghapus user sendiri
            if ($user->id == auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus user yang sedang login'
                ], 422);
            }

            $user->delete();

            // Log activity
            $this->logDelete(
                'USER',
                $user->id,
                "Menghapus user: {$user->nama} ({$user->username})",
                $userData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data user berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data user'
            ], 500);
        }
    }
}

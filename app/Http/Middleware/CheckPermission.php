<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $menu, $action = 'view')
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // SUPERADMIN selalu memiliki akses penuh
        if ($user->role && $user->role->nama_role === 'SUPERADMIN') {
            return $next($request);
        }

        // Cek permission
        $permission = $user->role->permissions()
            ->where('menu_name', $menu)
            ->first();

        $actionMap = [
            'view' => 'can_view',
            'create' => 'can_create',
            'edit' => 'can_edit',
            'delete' => 'can_delete',
            'export' => 'can_export',
            'import' => 'can_import',
            'wa' => 'can_wa',
            'show' => 'can_show',
            'update_status' => 'can_update_status',
        ];

        $permissionField = $actionMap[$action] ?? 'can_view';

        if (!$permission || !$permission->$permissionField) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini'
                ], 403);
            }

            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        return $next($request);
    }
}

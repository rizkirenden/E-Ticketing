<?php
// app/Helpers/PermissionHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function can($menu, $action = 'view')
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return false;
        }

        // SUPERADMIN selalu bisa
        if ($user->role->nama_role === 'SUPERADMIN') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('menu_name', $menu)
            ->first();

        if (!$permission) {
            return false;
        }

        $actionMap = [
            'view' => 'can_view',
            'create' => 'can_create',
            'edit' => 'can_edit',
            'delete' => 'can_delete',
            'export' => 'can_export',
            'import' => 'can_import',
            'wa' => 'can_wa',
           'excel' => 'can_excel',  // ← Pastikan mapping ini ada
            'show' => 'can_show',
            'update_status' => 'can_update_status',
        ];

        $permissionField = $actionMap[$action] ?? 'can_view';

        return $permission->$permissionField;
    }

    public static function getAccessibleMenus()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return [];
        }

        if ($user->role->nama_role === 'SUPERADMIN') {
            return ['*'];
        }

        return $user->role->permissions()
            ->where('can_view', true)
            ->pluck('menu_name')
            ->toArray();
    }

   public static function getButtonPermissions($menu)
{
    $result = [
        'can_create' => self::can($menu, 'create'),
        'can_edit' => self::can($menu, 'edit'),
        'can_delete' => self::can($menu, 'delete'),
        'can_export' => self::can($menu, 'export'),
        'can_import' => self::can($menu, 'import'),
        'can_wa' => self::can($menu, 'wa'),
        'can_excel' => self::can($menu, 'excel'),
        'can_show' => self::can($menu, 'show'),
        'can_update_status' => self::can($menu, 'update_status'),
    ];
    return $result;
}

    // Tambahkan method khusus untuk mengecek permission Excel
    public static function canExportExcel($menu = 'laporan')
    {
        return self::can($menu, 'excel');
    }
}

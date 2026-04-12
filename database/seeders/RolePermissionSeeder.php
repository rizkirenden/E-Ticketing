<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama supaya bisa seed ulang
        DB::table('role_permissions')->delete();

        // Daftar semua menu
        $menus = [
            'dashboard' => 'Dashboard',
            'laporan' => 'Ticketing',
            'kantor' => 'Kantor',
            'jenis-aplikasi' => 'Jenis Aplikasi',
            'user' => 'User Management',
            'role' => 'Role Management',
            'role-aplikasi' => 'Permission Role',
            'activity-logs' => 'Audit Log',
            'role-permissions' => 'Role Permissions',
        ];

        $defaultPermissions = [
            'can_view' => false,
            'can_create' => false,
            'can_edit' => false,
            'can_delete' => false,
            'can_export' => false,
            'can_import' => false,
            'can_wa' => false,
            'can_show' => false,
            'can_update_status' => false,
        ];

        /*
        |--------------------------------------------------------------------------
        | SUPERADMIN
        |--------------------------------------------------------------------------
        */

        $superadmin = Role::where('nama_role', 'SUPERADMIN')->first();

        if ($superadmin) {

            echo "Seeder: Menambahkan permissions untuk SUPERADMIN...\n";

            foreach ($menus as $menuName => $menuLabel) {

                RolePermission::updateOrCreate(
                    [
                        'role_id' => $superadmin->id,
                        'menu_name' => $menuName
                    ],
                    [
                        'can_view' => true,
                        'can_create' => true,
                        'can_edit' => true,
                        'can_delete' => true,
                        'can_export' => true,
                        'can_import' => true,
                        'can_wa' => true,
                        'can_show' => true,
                        'can_update_status' => true,
                    ]
                );

                echo " - {$menuName} (ALL ACCESS)\n";
            }

            echo "SUPERADMIN permissions selesai.\n\n";
        }
        echo "Seeder RolePermission selesai dijalankan.\n";
    }
}

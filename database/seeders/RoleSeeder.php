<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        DB::table('roles')->delete();

        // Buat role SUPERADMIN
        Role::updateOrCreate(
            ['nama_role' => 'SUPERADMIN'],
            ['nama_role' => 'SUPERADMIN']
        );

        echo "Role SUPERADMIN created/updated.\n";
        echo "RoleSeeder selesai dijalankan.\n";
    }
}

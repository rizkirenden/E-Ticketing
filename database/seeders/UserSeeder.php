<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        DB::table('users')->delete();

        // Ambil role SUPERADMIN
        $superadminRole = Role::where('nama_role', 'SUPERADMIN')->first();

        if ($superadminRole) {
            // Buat user SUPERADMIN
            User::updateOrCreate(
                ['username' => 'superadmin'],
                [
                    'nama' => 'Super Administrator',
                    'jabatan' => 'System Administrator',
                    'username' => 'superadmin',
                    'password' => Hash::make('password123'), // Ganti dengan password yang aman
                    'role_id' => $superadminRole->id,
                ]
            );

            echo "User SUPERADMIN created/updated.\n";
            echo "Username: superadmin\n";
            echo "Password: password123\n";
        } else {
            echo "Role SUPERADMIN not found. Please run RoleSeeder first.\n";
        }

        echo "UserSeeder selesai dijalankan.\n";
    }
}

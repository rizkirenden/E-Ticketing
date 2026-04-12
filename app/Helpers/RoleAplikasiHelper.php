<?php

namespace App\Helpers;

use App\Models\RoleAplikasi;
use App\Models\Role;
use App\Models\Jenisaplikasi;
use Illuminate\Support\Facades\Auth;

class RoleAplikasiHelper
{
    /**
     * Mendapatkan ID aplikasi yang diizinkan untuk role user saat ini
     */
    public static function getAplikasiIdsByRole()
    {
        $user = Auth::user();

        if (!$user) {
            // Untuk guest (tidak login), kembalikan SEMUA ID aplikasi
            // atau bisa juga return [] jika ingin guest tidak boleh lihat apa-apa
            return Jenisaplikasi::pluck('id')->toArray();
        }

        // Ambil role_id dari user
        $roleId = $user->role_id;

        // Cari semua aplikasi yang terikat dengan role ini
        $roleAplikasis = RoleAplikasi::where('role_id', $roleId)->pluck('jenis_aplikasi_id')->toArray();

        return $roleAplikasis;
    }

    /**
     * Mendapatkan semua role yang ada di database
     */
    public static function getAllRoles()
    {
        return Role::orderBy('nama_role')->get();
    }

    /**
     * Cek apakah user dapat mengakses aplikasi tertentu
     */
    public static function canAccessAplikasi($jenisAplikasiId)
    {
        $allowedAplikasiIds = self::getAplikasiIdsByRole();
        return in_array($jenisAplikasiId, $allowedAplikasiIds);
    }

    /**
     * Mendapatkan role user saat ini
     */
    public static function getCurrentRole()
    {
        $user = Auth::user();
        return $user ? $user->role : null;
    }

    /**
     * Mendapatkan nama role user saat ini
     */
    public static function getCurrentRoleName()
    {
        $user = Auth::user();
        return $user && $user->role ? $user->role->nama_role : 'guest';
    }

    /**
     * Cek apakah role tertentu memiliki akses ke semua aplikasi
     */
    public static function hasFullAccessToAllAplikasi()
    {
        $user = Auth::user();

        // Jika guest, return false (guest tidak punya full access)
        if (!$user) {
            return false;
        }

        // Atau jika guest boleh akses semua, return true
        // return true;

        // Berdasarkan nama role
        $fullAccessRoles = ['super', 'master', 'administrator', 'superadmin']; // Sesuaikan dengan database Anda
        return in_array($user->role->nama_role ?? '', $fullAccessRoles);
    }

    /**
     * Mendapatkan semua aplikasi yang bisa diakses user
     */
    public static function getAccessibleAplikasi()
    {
        $user = Auth::user();

        // Jika guest (tidak login), kembalikan SEMUA aplikasi
        if (!$user) {
            return Jenisaplikasi::orderBy('jenis_aplikasi')->get();
        }

        if (self::hasFullAccessToAllAplikasi()) {
            // Jika role punya akses penuh, kembalikan semua aplikasi
            return Jenisaplikasi::orderBy('jenis_aplikasi')->get();
        } else {
            // Jika tidak, kembalikan hanya aplikasi yang terikat dengan role
            $allowedIds = self::getAplikasiIdsByRole();
            return Jenisaplikasi::whereIn('id', $allowedIds)
                ->orderBy('jenis_aplikasi')
                ->get();
        }
    }

    /**
     * Method khusus untuk public routes - mengembalikan semua aplikasi
     */
    public static function getAllAplikasiForPublic()
    {
        return Jenisaplikasi::orderBy('jenis_aplikasi')->get();
    }

    /**
     * Method untuk mengecek apakah user adalah guest
     */
    public static function isGuest()
    {
        return Auth::check() === false;
    }
}

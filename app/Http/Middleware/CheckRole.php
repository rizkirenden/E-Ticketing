<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleName = $user->role->nama_role ?? '';

        // Jika roles yang diizinkan adalah 'superadmin-only' dan user adalah SUPERADMIN
        if (in_array('superadmin-only', $roles) && $roleName === 'SUPERADMIN') {
            return $next($request);
        }

        // Jika roles yang diizinkan adalah 'all-except-superadmin' dan user BUKAN SUPERADMIN
        if (in_array('all-except-superadmin', $roles) && $roleName !== 'SUPERADMIN') {
            return $next($request);
        }

        // Jika roles yang diizinkan adalah 'superadmin-and-others' dan user adalah SUPERADMIN atau role lain
        if (in_array('superadmin-and-others', $roles)) {
            return $next($request);
        }

        // Jika user memiliki role yang spesifik dalam daftar
        if (in_array($roleName, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, redirect ke dashboard dengan pesan error
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}

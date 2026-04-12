<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware untuk route tertentu
        if ($request->routeIs('login') || $request->is('login')) {
            return $next($request);
        }

        if (Auth::check()) {
            $lastActivity = session('last_activity', 0);
            $timeout = config('session.lifetime', 120) * 60;

            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                session()->flush();
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}

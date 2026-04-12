<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 15)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.',
                'seconds' => $seconds
            ], 429);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 401) {
            $this->limiter->hit($key, $decayMinutes * 60);
        }

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return sha1(implode('|', [
            $request->ip(),
            $request->userAgent(),
            'login'
        ]));
    }
}

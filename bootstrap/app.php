<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
             'check.role' => \App\Http\Middleware\CheckRole::class,
             'permission' => \App\Http\Middleware\CheckPermission::class,
            // 'check.activity' => \App\Http\Middleware\CheckUserActivity::class, // DIHAPUS
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

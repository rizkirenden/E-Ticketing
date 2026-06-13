<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

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
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Existing schedule untuk reminder
        $schedule->command('laporan:send-reminders')
            ->everyTwoMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/reminder-scheduler.log'));

        // ============================================
        // AUTO RECAP BULANAN KE PDF
        // ============================================

        // Kirim rekap bulanan setiap tanggal 1 pukul 08:00
        $schedule->command('laporan:monthly-recap --send-to-telegram')
            ->monthlyOn(1, '08:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/monthly-recap.log'));

        // Opsional: Kirim rekap juga di tanggal 15 (mid-month report)
        // $schedule->command('laporan:monthly-recap --send-to-telegram')
        //     ->monthlyOn(15, '08:00')
        //     ->withoutOverlapping()
        //     ->runInBackground()
        //     ->appendOutputTo(storage_path('logs/monthly-recap-mid.log'));

        // Hapus PDF lama setiap minggu (hari Minggu jam 01:00)
        $schedule->command('laporan:cleanup-old-pdfs')
            ->weeklyOn(7, '01:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/pdf-cleanup.log'));

        // Hapus atau comment callback logging jika tidak perlu
        // $schedule->call(function () {
        //     \Illuminate\Support\Facades\Log::info('Schedule runner executed at: ' . now());
        // })->everyMinute();
    })
    ->create();

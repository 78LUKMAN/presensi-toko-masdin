<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ─── TIME OVERRIDE UNTUK TESTING ────────────────────────────────────
        // Atur APP_FAKE_TIME di .env, contoh: APP_FAKE_TIME=2026-06-02 23:00:00
        // Kosongkan APP_FAKE_TIME untuk kembali ke waktu asli server.
        $fakeTime = env('APP_FAKE_TIME');
        if ($fakeTime) {
            \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse($fakeTime));
        } else {
            \Carbon\Carbon::setTestNow(null); // Pastikan reset ke waktu asli
        }

        // Bagikan timestamp server (dalam ms) ke semua view agar JS clock sinkron
        View::share('serverTimestampMs', \Carbon\Carbon::now()->timestamp * 1000);
    }
}

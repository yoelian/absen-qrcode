<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gunakan Bootstrap 5 untuk pagination agar tampil rapi
        Paginator::useBootstrapFive();

        // Atur timezone secara dinamis berdasarkan pengaturan di database
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $tz = \App\Models\Setting::get('timezone', config('app.timezone'));
                if ($tz) {
                    date_default_timezone_set($tz);
                    config(['app.timezone' => $tz]);
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika database belum siap (misal saat migrasi awal)
        }
    }
}

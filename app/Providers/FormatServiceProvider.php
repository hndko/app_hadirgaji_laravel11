<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Convert number to Rupiah format
        app()->bind('formatRupiah', function ($app, $params) {
            $number = $params[0] ?? 0;
            return 'Rp ' . number_format($number, 0, ',', '.');
        });

        // Convert date to Indonesian format (e.g. "14 Oktober 2024")
        app()->bind('formatTanggalIndo', function ($app, $params) {
            $tanggal = $params[0] ?? now();
            return Carbon::parse($tanggal)->translatedFormat('d F Y');
        });

        // Configure Carbon to use Indonesian language
        Carbon::setLocale('id');
    }
}

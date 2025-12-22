<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Fix mixed content when running behind a proxy/tunnel (ngrok, etc.)
        // ngrok sends: X-Forwarded-Proto: https
        $proto = request()->header('x-forwarded-proto');

        if ($proto === 'https') {
            URL::forceScheme('https');
        }
    }
}

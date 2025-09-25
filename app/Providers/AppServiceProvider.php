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
        // 🔹 Force HTTP/HTTPS sesuai environment
        if (app()->environment('local')) {
            URL::forceScheme('http');
        } elseif (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

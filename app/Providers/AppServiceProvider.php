<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        /*
        |--------------------------------------------------------------------------
        | Force HTTPS in production
        |--------------------------------------------------------------------------
        |
        | Render terminates HTTPS before forwarding the request to the
        | application container. For production we always generate HTTPS URLs
        | so route(), asset(), forms and redirects do not accidentally produce
        | mixed-content HTTP links.
        |
        */

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
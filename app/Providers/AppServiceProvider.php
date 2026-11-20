<?php

namespace App\Providers;

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
        config(['app.timezone' => 'America/Mexico_City']);
        date_default_timezone_set('America/Mexico_City');
        \Carbon\Carbon::setLocale('es');
    }
}

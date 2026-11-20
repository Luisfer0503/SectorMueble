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
        // Autocarga garantizada y directa de PhpSpreadsheet sin depender de la caché de Composer
        spl_autoload_register(function ($class) {
            $prefix = 'PhpOffice\\PhpSpreadsheet\\';
            $baseDir = base_path('vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/');
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        });
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

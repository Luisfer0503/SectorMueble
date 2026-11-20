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
        // Autocargador PSR-4 autónomo para librerías embebidas en app/Libraries
        // Garantiza que PhpSpreadsheet funcione en producción (cPanel/hosting) sin requerir Composer
        spl_autoload_register(function ($class) {
            $prefixes = [
                'PhpOffice\\PhpSpreadsheet\\' => app_path('Libraries/PhpSpreadsheet/'),
                'Composer\\Pcre\\'           => app_path('Libraries/Composer/Pcre/'),
                'Psr\\SimpleCache\\'         => app_path('Libraries/Psr/SimpleCache/'),
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) === 0) {
                    $relativeClass = substr($class, $len);
                    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require_once $file;
                        return;
                    }
                }
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

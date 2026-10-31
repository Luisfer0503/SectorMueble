<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use Carbon\Carbon;

echo "=== TEST PROGRAMACIÓN DE CAMPAÑA PROMOCIONAL ===\n\n";

$ahora = Carbon::now('America/Mexico_City');
$fechaInicio = Carbon::create(2026, 11, 1, 0, 0, 0, 'America/Mexico_City');

echo "Fecha y hora actual en México: " . $ahora->toDateTimeString() . "\n";
echo "Fecha y hora inicio de campaña: " . $fechaInicio->toDateTimeString() . "\n";

$activaAhora = Producto::isCampanaPromocionalActiva();
echo "¿La campaña está activa hoy (" . $ahora->toDateString() . ")?: " . ($activaAhora ? "SÍ (ACTIVA)" : "NO (INACTIVA - CORRECTO, HASTA EL 1 DE NOVIEMBRE)") . "\n\n";

// Probar un producto normal y un producto especial
$prodEspecial = Producto::where('promo_especial', 1)->first() ?? Producto::first();
$prodNormal = Producto::where('promo_especial', 0)->first() ?? Producto::find(1);

echo "1. ESTADO ACTUAL (31 DE OCTUBRE):\n";
echo "Producto: " . $prodEspecial->nombre . "\n";
echo " - Precio Original: $" . $prodEspecial->precio . "\n";
echo " - ¿Tiene Descuento visible hoy?: " . ($prodEspecial->tieneDescuento() ? "Sí (-{$prodEspecial->porcentaje_descuento}%)" : "No (precio normal)") . "\n";
echo " - Precio Efectivo a cobrar hoy: $" . $prodEspecial->precioEfectivo() . "\n\n";

// Simular forzando la campaña como activa
putenv('PROMO_NOVIEMBRE_ACTIVA=true');
$_ENV['PROMO_NOVIEMBRE_ACTIVA'] = 'true';

echo "2. ESTADO SIMULANDO 1 DE NOVIEMBRE (CAMPAÑA ACTIVA):\n";
echo "¿La campaña está activa?: " . (Producto::isCampanaPromocionalActiva() ? "SÍ (ACTIVA)" : "NO") . "\n";
echo "Producto Especial ({$prodEspecial->nombre}):\n";
echo " - Descuento Contado: " . $prodEspecial->porcentaje_descuento_contado . "%\n";
echo " - Precio Contado: $" . $prodEspecial->precio_contado . "\n";
echo " - Descuento MSI: " . $prodEspecial->porcentaje_descuento_msi . "%\n";
echo " - Precio MSI: $" . $prodEspecial->precio_msi . " (12 MSI de $" . $prodEspecial->getMensualidadMsi(12) . ")\n\n";

if ($prodNormal && $prodNormal->id !== $prodEspecial->id) {
    echo "Producto Normal ({$prodNormal->nombre}):\n";
    echo " - Descuento Contado: " . $prodNormal->porcentaje_descuento_contado . "%\n";
    echo " - Precio Contado: $" . $prodNormal->precio_contado . "\n";
    echo " - Descuento MSI: " . $prodNormal->porcentaje_descuento_msi . "%\n";
    echo " - Precio MSI: $" . $prodNormal->precio_msi . " (12 MSI de $" . $prodNormal->getMensualidadMsi(12) . ")\n\n";
}

// Resetear
putenv('PROMO_NOVIEMBRE_ACTIVA');
unset($_ENV['PROMO_NOVIEMBRE_ACTIVA']);

echo "3. ESTADO RESTAURADO A TIEMPO REAL:\n";
echo "¿Campaña activa?: " . (Producto::isCampanaPromocionalActiva() ? "SÍ" : "NO (Esperando automáticamente al 1 de noviembre 00:00:00)") . "\n";
echo "\n=== PRUEBA FINALIZADA CON ÉXITO ===\n";


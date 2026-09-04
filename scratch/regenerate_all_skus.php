<?php

if (!isset($app) || !is_object($app)) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    if (is_object($app) && method_exists($app, 'make')) {
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
    }
}

use App\Models\Producto;
use App\Models\ProductoDetalle;

$count = 0;
foreach (Producto::with('detalles')->get() as $p) {
    // Inferir tipo_mueble basado en nombre / descripción / categoría
    $nombreLower = mb_strtolower($p->nombre . ' ' . $p->descripcion . ' ' . $p->categoria);
    
    $tipo = '01'; // Default Silla
    if (str_contains($nombreLower, 'sillón comedor') || str_contains($nombreLower, 'sillon comedor')) {
        $tipo = '02';
    } elseif (str_contains($nombreLower, 'sillón') || str_contains($nombreLower, 'sillon')) {
        $tipo = '03';
    } elseif (str_contains($nombreLower, 'sala modular') || str_contains($nombreLower, 'sofa') || str_contains($nombreLower, 'sofá') || str_contains($nombreLower, 'modular')) {
        $tipo = '04';
    } elseif (str_contains($nombreLower, 'recamara') || str_contains($nombreLower, 'recámara')) {
        $tipo = '05';
    } elseif (str_contains($nombreLower, 'cama')) {
        $tipo = '06';
    } elseif (str_contains($nombreLower, 'taburete') || str_contains($nombreLower, 'banca')) {
        $tipo = '07';
    } elseif (str_contains($nombreLower, 'buro') || str_contains($nombreLower, 'buró')) {
        $tipo = '08';
    } elseif (str_contains($nombreLower, 'recibidor')) {
        $tipo = '09';
    } elseif (str_contains($nombreLower, 'mesa') || str_contains($nombreLower, 'comedor')) {
        $tipo = '10';
    } elseif (str_contains($nombreLower, 'silla')) {
        $tipo = '01';
    }

    $p->update([
        'tipo_mueble' => $tipo,
        'numero_piezas' => $p->numero_piezas ?? 1,
    ]);

    foreach ($p->detalles as $det) {
        $newSku = Producto::generarSkuFormateado($p, $det->nombre);
        $det->update(['sku' => $newSku]);
        $count++;
    }
}

echo "Successfully updated $count subarticle SKUs to the structured format CT-01KT01-TBTX-LO!\n";

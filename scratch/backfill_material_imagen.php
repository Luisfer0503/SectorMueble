<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\ProductoDetalle;

$count = 0;
$productos = Producto::with('detalles')->get();

foreach ($productos as $p) {
    $colores = $p->colores;
    if (is_array($colores)) {
        foreach ($p->detalles as $idx => $det) {
            $colData = $colores[$idx] ?? null;
            if ($colData && !empty($colData['material_imagen']) && empty($det->material_imagen)) {
                $det->material_imagen = $colData['material_imagen'];
                $det->save();
                $count++;
            }
        }
    }
}

echo "Backfilled material_imagen for {$count} sub-articles successfully.\n";

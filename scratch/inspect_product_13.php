<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\ProductoDetalle;

$det = ProductoDetalle::where('sku', 'like', '%0013%')->orWhere('producto_id', 13)->get();
echo "Detalles count: " . $det->count() . "\n";
foreach ($det as $d) {
    echo "ID: {$d->id}, SKU: {$d->sku}, Nombre: {$d->nombre}, Imagen: {$d->imagen}, MaterialImg: {$d->material_imagen}\n";
}

$p = Producto::find(13);
if ($p) {
    echo "Colores raw: " . json_encode($p->colores) . "\n";
}

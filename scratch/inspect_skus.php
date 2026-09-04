<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductoDetalle;

foreach (ProductoDetalle::with('producto')->take(15)->get() as $d) {
    echo sprintf(
        "ID: %d | Mueble: %-20s | Acabado: %-25s => SKU: %s\n",
        $d->producto_id,
        $d->producto->nombre ?? 'N/A',
        $d->nombre,
        $d->sku
    );
}

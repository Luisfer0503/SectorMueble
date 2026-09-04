<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

$count = 0;
foreach (Producto::with('detalles')->get() as $p) {
    $prefix = ($p->proveedor === 'Muebles Samar') ? 'MS' : 'CT';
    foreach ($p->detalles as $idx => $det) {
        $newSku = 'SKU-' . $prefix . '-' . sprintf('%04d', $p->id) . '-' . sprintf('%02d', $idx + 1);
        $det->update(['sku' => $newSku]);
        $count++;
    }
}

echo "Updated $count product subarticle SKUs with provider prefixes (CT / MS).\n";

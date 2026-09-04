<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

foreach (Producto::with('detalles')->get() as $p) {
    echo "ID: {$p->id} | Nombre: {$p->nombre} | Categoria: {$p->categoria}\n";
    foreach ($p->detalles as $d) {
        echo "   -> Subartículo ID: {$d->id} | SKU: {$d->sku} | Nombre: {$d->nombre} | Precio actual: \${$d->precio}\n";
    }
}

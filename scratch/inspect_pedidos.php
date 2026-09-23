<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pedido;

$pedidos = Pedido::with('detalles')->orderBy('id', 'desc')->take(10)->get();

echo "ÚLTIMOS 10 PEDIDOS EN LA BASE DE DATOS:\n";
echo str_repeat("=", 80) . "\n";

foreach ($pedidos as $p) {
    echo "ID: #{$p->id} | Usuario ID: " . ($p->usuario_id ?? 'GUEST') . " | Cliente: {$p->nombre_cliente} | Correo: {$p->correo_cliente} | Total: \${$p->total} | Estado: {$p->estado} | Factura: {$p->factura_estado} | Fecha: {$p->created_at}\n";
    foreach ($p->detalles as $d) {
        echo "   -> Producto: {$d->nombre_producto} x{$d->cantidad} (\${$d->precio})\n";
    }
    echo str_repeat("-", 80) . "\n";
}

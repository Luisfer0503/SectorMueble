<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$all = App\Models\Producto::with('detalles')->get();
foreach($all as $p) {
    echo "ID: {$p->id} | Nombre: {$p->nombre} | Cat: {$p->categoria} | Precio: {$p->precio} | Descuento: {$p->precio_descuento}\n";
}

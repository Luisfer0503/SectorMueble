<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$detalles = App\Models\ProductoDetalle::take(10)->get();
foreach($detalles as $d) {
    echo "ID: {$d->id} | ProdID: {$d->producto_id} | Nombre: {$d->nombre} | ImgRaw: {$d->imagen} | ImgUrl: {$d->imagen_url}\n";
}

<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Zapato;
use App\Models\Ropa;
use Illuminate\Http\Request;

// Create dummy Zapato if none
$zapato = Zapato::first();
if (!$zapato) {
    $zapato = Zapato::create([
        'categoria'  => 'BLUSAS',
        'estilo'     => '1001',
        'numero'     => '24.0',
        'color'      => 'ROJO',
        'material'   => 'PIEL',
        'cantidad'   => 5,
        'precio'     => 399.00,
        'imagen_url' => 'storage/zapatos/default.png',
    ]);
}

// Create dummy Ropa if none
$ropa = Ropa::first();
if (!$ropa) {
    $ropa = Ropa::create([
        'categoria'     => 'BLUSAS',
        'marca'         => 'ZARA',
        'talla'         => 'M',
        'estilo'        => 'CASUAL',
        'color'         => 'NEGRO',
        'codigo_barras' => '7501234567890',
        'cantidad'      => 12,
        'precio'        => 299.00,
        'imagen_url'    => 'storage/ropa/default.png',
    ]);
}

$controller = app(\App\Http\Controllers\AdminController::class);

echo "Testing Zapato sync via Controller endpoint...\n";
$req1 = Request::create('/admin/sicar/sincronizar-articulo', 'POST', [
    'tipo' => 'zapato',
    'id'   => $zapato->id,
]);
$req1->headers->set('Accept', 'application/json');
$res1 = $controller->sincronizarArticuloSicar($req1);
print_r($res1->getData());

echo "\nTesting Ropa sync via Controller endpoint...\n";
$req2 = Request::create('/admin/sicar/sincronizar-articulo', 'POST', [
    'tipo' => 'ropa',
    'id'   => $ropa->id,
]);
$req2->headers->set('Accept', 'application/json');
$res2 = $controller->sincronizarArticuloSicar($req2);
print_r($res2->getData());

echo "\nVerifying SICAR database table count:\n";
$totalArticulos = DB::connection('sicar')->table('articulo')->count();
echo "Total Articulos in SICAR: {$totalArticulos}\n";

<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SicarSyncService;

$service = new SicarSyncService();

echo "Testing SicarSyncService for ROPA...\n";
$res1 = $service->sincronizarArticulo([
    'id'           => 1,
    'departamento' => 'ROPA',
    'categoria'    => 'BLUSAS',
    'clave'        => 'blusas00001',
    'claveAlterna' => 'RBLUSA001',
    'descripcion'  => 'BLUSA DAMA ELEGANTE TALLA M',
    'precio1'      => 350.00,
    'precio2'      => 300.00,
    'precioCompra' => 200.00,
    'existencia'   => 15,
]);

print_r($res1);

echo "\nTesting SicarSyncService for ZAPATOS...\n";
$res2 = $service->sincronizarArticulo([
    'id'           => 1,
    'departamento' => 'ZAPATOS',
    'categoria'    => 'ZAPATO ESCOLAR',
    'clave'        => 'zapatoescolar00001',
    'claveAlterna' => 'M1124SINTETICONEGROT24.5',
    'descripcion'  => 'ZAPATO ESCOLAR NIÑO ESTILO 1124 NEGRO TALLA 24.5',
    'precio1'      => 490.00,
    'precio2'      => 450.00,
    'precioCompra' => 280.00,
    'existencia'   => 8,
]);

print_r($res2);

echo "\nChecking database records in sicar:\n";
$articuloRopa = DB::connection('sicar')->table('articulo')->where('clave', 'blusas00001')->first();
print_r($articuloRopa);

$articuloZapato = DB::connection('sicar')->table('articulo')->where('clave', 'zapatoescolar00001')->first();
print_r($articuloZapato);

$deptos = DB::connection('sicar')->table('departamento')->get();
echo "\nDEPARTAMENTOS:\n";
print_r($deptos);

$cats = DB::connection('sicar')->table('categoria')->get();
echo "\nCATEGORIAS:\n";
print_r($cats);

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$zapatos = App\Models\Zapato::all();
$grouped = [];

foreach ($zapatos as $z) {
    $c = $z->clave_alterna;
    $grouped[$c][] = [
        'id' => $z->id,
        'estilo' => $z->estilo,
        'material' => $z->material,
        'color' => $z->color,
        'bordado' => $z->bordado,
        'numero' => $z->numero,
        'cantidad' => $z->cantidad
    ];
}

$dups = array_filter($grouped, function($items) {
    return count($items) > 1;
});

echo "Total productos en DB: " . count($zapatos) . "\n";
echo "Total Claves Alternas duplicadas: " . count($dups) . "\n";
print_r($dups);

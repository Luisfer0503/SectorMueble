<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

$p = Producto::where('nombre', 'like', '%Kattia%')->first();
if ($p) {
    echo "Producto: " . $p->nombre . "\n";
    foreach (['Tabatex Liquid Otter', 'Dylan Latte', 'Napoli Oxford', 'Napoli Sand'] as $mat) {
        echo sprintf("%-25s => %s\n", $mat, Producto::generarSkuFormateado($p, $mat));
    }
}

<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===============================================================\n";
echo "EJECUTANDO ACTUALIZACIÓN COMPLETA DE SKUS Y PRECIOS EN PRODUCCIÓN\n";
echo "===============================================================\n\n";

echo "PASO 1: Regenerando SKUs con la estructura CT-01KT01-TBTX-LO...\n";
require __DIR__ . '/regenerate_all_skus.php';

echo "\nPASO 2: Actualizando precios desde el archivo Excel PRECIOS EN LINEA SM .xlsx...\n";
require __DIR__ . '/execute_price_updates.php';

echo "\n===============================================================\n";
echo "¡ACTUALIZACIÓN COMPLETADA CON ÉXITO EN PRODUCCIÓN!\n";
echo "===============================================================\n";

<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\ProductoDetalle;

$jsonFile = __DIR__ . '/excel_prices.json';
if (!file_exists($jsonFile)) {
    die("Error: excel_prices.json does not exist.\n");
}

$excelData = json_decode(file_get_contents($jsonFile), true);

function cleanText($text) {
    if (!$text) return '';
    $text = mb_strtoupper($text, 'UTF-8');
    // Replace accents
    $unwanted = [
        'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U', 'Ñ'=>'N', 'Ü'=>'U',
        'á'=>'A', 'é'=>'E', 'í'=>'I', 'ó'=>'O', 'ú'=>'U', 'ñ'=>'N', 'ü'=>'U'
    ];
    $text = strtr($text, $unwanted);
    $text = preg_replace('/[^A-Z0-9\s]/', ' ', $text);
    return trim(preg_replace('/\s+/', ' ', $text));
}

$products = Producto::with('detalles')->get();

$matched = 0;
$updatedDetailsCount = 0;
$unmatchedProducts = [];

echo "=========================================================\n";
echo "COMPARANDO PRODUCTOS DE BASE DE DATOS CON EXCEL DE PRECIOS\n";
echo "=========================================================\n\n";

foreach ($products as $p) {
    $pNameClean = cleanText($p->nombre);
    
    $bestMatch = null;
    $highestScore = 0;

    foreach ($excelData as $row) {
        $modelClean = cleanText($row['model']);
        $fullClean  = cleanText($row['clean_full']);
        
        if (empty($modelClean)) continue;

        // Check if model clean is contained in product clean name, or vice versa
        if (str_contains($pNameClean, $modelClean) || str_contains($modelClean, $pNameClean)) {
            $score = 10;
            
            // Words intersection
            $pWords = explode(' ', $pNameClean);
            $exWords = explode(' ', $fullClean);
            $common = array_intersect($pWords, $exWords);
            $score += count($common) * 5;

            // Specific variant checks
            if (str_contains($pNameClean, 'ANGOSTO') && str_contains($fullClean, 'ANGOSTO')) $score += 20;
            if (str_contains($pNameClean, 'ANCHO') && str_contains($fullClean, 'ANCHO')) $score += 20;
            if (str_contains($pNameClean, 'LOVE SEAT') && str_contains($fullClean, 'LOVE SEAT')) $score += 20;
            if (str_contains($pNameClean, 'CHAICE') && str_contains($fullClean, 'CHAICE')) $score += 20;
            if (str_contains($pNameClean, 'ESQUINERO') && str_contains($fullClean, 'ESQUINERO')) $score += 20;
            if (str_contains($pNameClean, 'SILLON') && str_contains($fullClean, 'SILLON')) $score += 15;
            if (str_contains($pNameClean, 'SOFA') && str_contains($fullClean, 'SOFA')) $score += 15;
            if (str_contains($pNameClean, 'MATRIMONIAL') && str_contains($fullClean, 'MATRIMONIAL')) $score += 20;
            if (str_contains($pNameClean, 'KING') || str_contains($pNameClean, 'KS')) {
                if (str_contains($fullClean, 'KS') || str_contains($fullClean, 'KING')) $score += 20;
            }
            if (str_contains($pNameClean, '4P') && str_contains($fullClean, '4P')) $score += 20;
            if (str_contains($pNameClean, '6P') && str_contains($fullClean, '6P')) $score += 20;
            if (str_contains($pNameClean, '8P') && str_contains($fullClean, '8P')) $score += 20;

            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMatch = $row;
            }
        }
    }

    if ($bestMatch && $highestScore >= 10) {
        $matched++;
        $newPrice = (float) $bestMatch['price'];
        
        echo sprintf("✅ DB ID %3d | %-40s => Excel [%s] -> Nuevo Precio: $%.2f\n", $p->id, $p->nombre, $bestMatch['clean_full'], $newPrice);

        // Update all subarticle details for this product to the new price
        foreach ($p->detalles as $det) {
            $det->update(['precio' => $newPrice]);
            $updatedDetailsCount++;
        }

        // If product has percentage discount active, update precio_descuento accordingly
        if ($p->porcentaje_descuento && $p->porcentaje_descuento > 0) {
            $nuevoPrecioDescuento = round($newPrice * (1 - $p->porcentaje_descuento / 100), 2);
            $p->update(['precio_descuento' => $nuevoPrecioDescuento]);
        }
    } else {
        $unmatchedProducts[] = $p;
    }
}

echo "\n=========================================================\n";
echo "RESUMEN DE ACTUALIZACIÓN DE PRECIOS\n";
echo "=========================================================\n";
echo "Productos enlazados y actualizados: {$matched} / " . count($products) . "\n";
echo "Subartículos actualizados: {$updatedDetailsCount}\n";
echo "Productos sin coincidencia exacta en Excel: " . count($unmatchedProducts) . "\n";

if (!empty($unmatchedProducts)) {
    echo "\nPRODUCTOS SIN MATCH DIRECTO:\n";
    foreach ($unmatchedProducts as $unm) {
        echo " - ID {$unm->id}: {$unm->nombre} (Categoría: {$unm->categoria})\n";
    }
}

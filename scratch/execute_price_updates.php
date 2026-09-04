<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\ProductoDetalle;

// Definición exacta de precios obtenidos del Excel "PRECIOS EN LINEA SM .xlsx"
$excelPricesMap = [
    // Sillas, Bancos y Sillones Comedor
    'Kattia' => 5990.00,
    'Serrano' => 6590.00,
    'Monna' => 6690.00,
    'Tina' => 6390.00,
    'Torento' => 6190.00,
    'Padua' => 5990.00,
    'Bimba' => 6390.00,
    'Olvera' => 11860.00,
    'Palermo' => 28990.00,
    'Zenit' => 8650.00,

    // Sala Modular West
    'West - Sillon Ind. Angosto' => 10240.00,
    'West - Sillon Ind. Ancho'   => 11370.00,
    'West - Love Seat'          => 15990.00,
    'West - Chaice'             => 16890.00,
    'West - Esquinero'          => 11660.00,
    'West - Taburete Cuadrado Grande'   => 8990.00,
    'West - Taburete Rectangular Grande' => 8190.00,
    'West - Taburete Cuadrado Mediano'  => 8490.00,
    'West - Brazos'             => 5290.00,

    // Sala Modular Virgo
    'Virgo - Taburete Cuadrado'    => 10890.00,
    'Virgo - Taburete Rectangular' => 16090.00,
    'Virgo - Esquinero'           => 14340.00,
    'Virgo - Respaldo'            => 4490.00,

    // Recamaras / Camas
    'Lucier'    => 19440.00,
    'Aura'      => 18190.00,
    'Isabella'  => 18190.00,
    'Duna'      => 18190.00,
    'Luna'      => 18190.00,
    'Pistacho'  => 18190.00,
    'Molina'    => 19440.00,

    // Taburetes
    'Patricio' => 5760.00,
    'Torres'   => 6440.00,
    'Del Rio'  => 6890.00,

    // Burós y Mesas Auxiliares
    'Risotto - Melamina'                => 6950.00,
    'Risotto - Con Cubierta De Mármol' => 9790.00,
    'Domingo'                           => 3770.00,
    'Noruega'                           => 7490.00,
    'Suecia'                            => 8190.00,
    'Romina'                            => 12490.00,
    'Julia'                             => 11590.00,

    // Pedestales Mesa
    'Diandra' => 16610.00,
    'Nina'    => 14690.00,
    'Otoño'   => 14990.00,
    'Romero'  => 14990.00,
    'Carol'   => 12890.00,

    // Cubiertas Sao Paulo
    'Sao Paulo - Cubierta Redonda 4 Personas' => 6890.00,
    'Sao Paulo - Cubierta Redonda 6 Personas' => 7560.00,
    'Sao Paulo - Cubierta Redonda 8 Personas' => 10890.00,

    // Cubiertas Atenas
    'Atenas - Cubierta Cuadrada 4 Personas' => 6640.00,
    'Atenas - Cubierta Cuadrada 8 Personas' => 10890.00,

    // Salas Completas / Piezas (Hoja 2)
    'Sala Venecia'  => 32490.00,
    'Sala Concherto' => 39900.00,
    'Yorkshire'     => 38890.00,
    'Cataluña'      => 38190.00,
    'Edimburgo'     => 41490.00,
    'Milán'         => 17820.00,
    'Bombay'        => 16490.00,
];

$productos = Producto::with('detalles')->get();

$totalActualizados = 0;
$detallesActualizados = 0;
$omitidos = [];

echo "===============================================================\n";
echo "EJECUTANDO ACTUALIZACIÓN DE PRECIOS SEGÚN EXCEL PRECIOS EN LINEA SM\n";
echo "===============================================================\n\n";

foreach ($productos as $p) {
    $pNombre = $p->nombre;
    $nuevoPrecio = null;

    // Buscar coincidencia en el mapa de precios
    foreach ($excelPricesMap as $key => $precioExcel) {
        if (mb_stripos($pNombre, $key) !== false) {
            $nuevoPrecio = $precioExcel;
            break;
        }
    }

    if ($nuevoPrecio !== null) {
        $totalActualizados++;
        echo sprintf("✅ Mueble ID %3d | %-45s => \$$precioExcel\n", $p->id, $pNombre);

        // Actualizar todos los subartículos de este producto
        foreach ($p->detalles as $det) {
            $det->update(['precio' => $nuevoPrecio]);
            $detallesActualizados++;
        }

        // Si el producto tiene un porcentaje de descuento activo, recalcular precio con descuento
        if ($p->porcentaje_descuento && $p->porcentaje_descuento > 0) {
            $precioDesc = round($nuevoPrecio * (1 - $p->porcentaje_descuento / 100), 2);
            $p->update(['precio_descuento' => $precioDesc]);
        }
    } else {
        $omitidos[] = "ID {$p->id}: {$pNombre}";
    }
}

echo "\n===============================================================\n";
echo "RESULTADO FINAL DE LA ACTUALIZACIÓN DE PRECIOS\n";
echo "===============================================================\n";
echo "Muebles principales actualizados: {$totalActualizados} / " . count($productos) . "\n";
echo "Subartículos (variantes) actualizados: {$detallesActualizados}\n";
echo "Muebles sin cambios (no especificados en Excel): " . count($omitidos) . "\n";

if (!empty($omitidos)) {
    echo "\nLista de Muebles mantenidos con su precio previo:\n";
    foreach ($omitidos as $om) {
        echo " - {$om}\n";
    }
}

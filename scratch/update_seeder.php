<?php

$file = 'c:/laragon/www/SectorMueble/database/seeders/ProductoSeeder.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $content = str_replace("'Salón'", "'Salas'", $content);
    $content = str_replace("'Muebles Auxiliares'", "'Salas'", $content);
    file_put_contents($file, $content);
    echo "ProductoSeeder.php actualizado con éxito.\n";
} else {
    echo "Archivo no encontrado.\n";
}

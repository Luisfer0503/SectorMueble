<?php
// fix_permissions.php - Corrector de permisos para cPanel / WNPower
// Sube este archivo a la carpeta raíz de tu sitio (public_html) y visítalo desde tu navegador: tudominio.com/fix_permissions.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$baseDir = __DIR__;
echo "<h2>🛠️ Corrector Automático de Permisos de Sector Mueble</h2>";
echo "<p>Procesando directorio: <code>" . htmlspecialchars($baseDir) . "</code></p>";

function fixPermissions($path) {
    if (!file_exists($path)) return 0;
    
    $count = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            @chmod($item->getPathname(), 0755);
        } else {
            @chmod($item->getPathname(), 0644);
        }
        $count++;
    }
    return $count;
}

if (is_dir($baseDir . '/vendor')) {
    @chmod($baseDir . '/vendor', 0755);
    $vCount = fixPermissions($baseDir . '/vendor');
    echo "<p>✅ Permisos de la carpeta <strong>vendor/</strong> corregidos ($vCount elementos actualizados a 755/644).</p>";
} else {
    echo "<p>⚠️ No se encontró la carpeta <code>vendor/</code>.</p>";
}

if (is_dir($baseDir . '/storage')) {
    @chmod($baseDir . '/storage', 0775);
    $sCount = fixPermissions($baseDir . '/storage');
    echo "<p>✅ Permisos de la carpeta <strong>storage/</strong> corregidos.</p>";
}

if (is_dir($baseDir . '/bootstrap/cache')) {
    @chmod($baseDir . '/bootstrap/cache', 0775);
    $bCount = fixPermissions($baseDir . '/bootstrap/cache');
    echo "<p>✅ Permisos de la carpeta <strong>bootstrap/cache/</strong> corregidos.</p>";
}

echo "<hr><p style='color: green; font-weight: bold;'>🎉 ¡Proceso finalizado! Ya puedes recargar la página principal de tu sitio web.</p>";
echo "<p style='color: red;'>⚠️ <strong>Importante por seguridad:</strong> Elimina el archivo <code>fix_permissions.php</code> de cPanel una vez que tu sitio funcione correctamente.</p>";

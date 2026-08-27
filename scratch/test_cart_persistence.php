<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Producto;

// 1. Obtener usuario o crear uno de prueba
$user = User::firstOrCreate(
    ['email' => 'cliente_test_cart@sectormueble.com'],
    [
        'name' => 'Cliente Test Carrito',
        'password' => bcrypt('password123'),
    ]
);

$producto = Producto::first();

if (!$producto) {
    echo "No hay productos en la base de datos para probar.\n";
    exit;
}

// 2. Simular carrito
$carritoSimulado = [
    $producto->id => [
        'nombre' => $producto->nombre,
        'precio' => (float) $producto->precio,
        'precio_original' => (float) $producto->precio,
        'con_descuento' => false,
        'imagen_url' => $producto->imagen_url,
        'cantidad' => 2,
        'categoria' => $producto->categoria,
        'stock_disponible' => $producto->stock
    ]
];

// 3. Simular guardado al cerrar sesión
$user->update(['carrito_guardado' => json_encode($carritoSimulado)]);

$userRefreshed = User::find($user->id);
$restaurado = json_decode($userRefreshed->carrito_guardado, true);

if (!empty($restaurado) && isset($restaurado[$producto->id])) {
    echo "¡ÉXITO! Carrito guardado correctamente en la cuenta del usuario: " . $restaurado[$producto->id]['nombre'] . " (Cantidad: " . $restaurado[$producto->id]['cantidad'] . ")\n";
} else {
    echo "ERROR al guardar carrito en cuenta del usuario.\n";
}

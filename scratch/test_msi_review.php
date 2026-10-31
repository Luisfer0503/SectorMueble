<?php

// Pruebas aisladas: no conecta a la base de datos ni a Stripe.
require __DIR__.'/../vendor/autoload.php';

use App\Models\Producto;
use App\Models\ProductoDetalle;
use App\Models\Pedido;

$passed = 0;
$failed = 0;
$check = function (string $name, bool $ok, string $detail = '') use (&$passed, &$failed) {
    $ok ? $passed++ : $failed++;
    echo ($ok ? 'PASS' : 'FAIL')." | {$name}".($detail ? " | {$detail}" : '').PHP_EOL;
};

foreach ([false, true] as $especial) {
    $producto = new Producto(['promo_especial' => $especial]);
    $detalle = new ProductoDetalle(['precio' => 12000, 'activo' => true]);
    $detalle->setRelation('producto', $producto);
    $producto->setRelation('detalles', collect([$detalle]));
    $label = $especial ? 'especial' : 'general';
    $contado = $especial ? 6000.0 : 7200.0;
    $msi = $especial ? 7800.0 : 8400.0;
    foreach ([$producto, $detalle] as $model) {
        $type = class_basename($model);
        $check("{$type} {$label}: contado", $model->precio_contado === $contado);
        $check("{$type} {$label}: MSI", $model->precio_msi === $msi);
        foreach ([3, 6, 9, 12] as $meses) {
            $check("{$type} {$label}: {$meses} cuotas", $model->getMensualidadMsi($meses) === round($msi / $meses, 2));
        }
    }
}

$pedido = new Pedido(['tipo_pago' => 'msi_6']);
$check('Pedido conserva modalidad y plazo', $pedido->tipo_pago === 'msi_6', 'obtenido: '.var_export($pedido->tipo_pago, true));

$controller = new App\Http\Controllers\PrincipalController;
$container = require __DIR__.'/../bootstrap/app.php';
$container->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$container['config']->set('session.driver', 'array');
$session = $container['session']->driver();
foreach ([3, 6, 9, 12] as $meses) {
    $request = Illuminate\Http\Request::create('/', 'POST', ['tipo_pago' => 'msi', 'meses_msi' => $meses]);
    $response = $controller->seleccionarTipoPago($request)->getData(true);
    $check("Persistencia seleccion {$meses}", $session->get('meses_msi_seleccionado') === $meses && $response['meses_msi'] === $meses);
}

echo "Resultado: {$passed} correctas, {$failed} fallidas".PHP_EOL;
exit($failed ? 1 : 0);

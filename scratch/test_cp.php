<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\PrincipalController;
use Illuminate\Http\Request;

$controller = new PrincipalController();

// Test 1: Covered CP (72760 - San Pedro Cholula)
$req1 = Request::create('/verificar-cobertura-cp', 'POST', ['codigo_postal' => '72760']);
$res1 = $controller->verificarCodigoPostal($req1);
echo "CP 72760 Result:\n" . $res1->getContent() . "\n\n";

// Test 2: Covered CP (72810 - San Andrés Cholula)
$req2 = Request::create('/verificar-cobertura-cp', 'POST', ['codigo_postal' => '72810']);
$res2 = $controller->verificarCodigoPostal($req2);
echo "CP 72810 Result:\n" . $res2->getContent() . "\n\n";

// Test 3: Not Covered CP (01000)
$req3 = Request::create('/verificar-cobertura-cp', 'POST', ['codigo_postal' => '01000']);
$res3 = $controller->verificarCodigoPostal($req3);
echo "CP 01000 Result:\n" . $res3->getContent() . "\n";

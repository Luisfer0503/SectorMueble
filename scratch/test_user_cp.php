<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$email = 'test_cp_' . time() . '@sectormueble.com';

$user = User::create([
    'name' => 'Usuario Test CP',
    'email' => $email,
    'codigo_postal' => '72760',
    'password' => bcrypt('password123'),
]);

if ($user && $user->codigo_postal === '72760') {
    echo "¡ÉXITO! El modelo User soporta y guarda correctamente el campo codigo_postal: " . $user->codigo_postal . "\n";
    $user->delete();
} else {
    echo "ERROR: El código postal no se guardó.\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiToken = config('services.whatsapp.api_token');
$apiUrl = config('services.whatsapp.api_url');
$numeroVentas = config('services.whatsapp.ventas_number');

// Test 1: hello_world template with en_US
$payload1 = [
    'messaging_product' => 'whatsapp',
    'recipient_type'    => 'individual',
    'to'                => $numeroVentas,
    'type'              => 'template',
    'template'          => [
        'name'     => 'hello_world',
        'language' => ['code' => 'en_US']
    ]
];

$res1 = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->post($apiUrl, $payload1);

echo "TEST hello_world (en_US) -> Status: " . $res1->status() . "\n";
echo "Response: " . $res1->body() . "\n\n";

// Test 2: Text message (if session open or sandbox allow)
$payload2 = [
    'messaging_product' => 'whatsapp',
    'recipient_type'    => 'individual',
    'to'                => $numeroVentas,
    'type'              => 'text',
    'text'              => [
        'body' => 'Hola, esto es una prueba desde Sector Mueble'
    ]
];

$res2 = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->post($apiUrl, $payload2);

echo "TEST text message -> Status: " . $res2->status() . "\n";
echo "Response: " . $res2->body() . "\n\n";

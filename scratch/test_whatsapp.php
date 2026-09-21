<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiUrl = config('services.whatsapp.api_url');
$apiToken = config('services.whatsapp.api_token');
$numeroVentas = config('services.whatsapp.ventas_number');
$templateName = config('services.whatsapp.template', env('WHATSAPP_TEMPLATE_NAME', 'aviso_nuevo_contacto'));

echo "API URL: {$apiUrl}\n";
echo "NUMERO VENTAS: {$numeroVentas}\n";
echo "TEMPLATE: {$templateName}\n\n";

$payload = [
    'messaging_product' => 'whatsapp',
    'recipient_type'    => 'individual',
    'to'                => $numeroVentas,
    'type'              => 'template',
    'template'          => [
        'name'     => $templateName,
        'language' => [
            'code' => 'es'
        ],
        'components' => [
            [
                'type'       => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => 'Juan Pérez'],
                    ['type' => 'text', 'text' => '+522226702641'],
                    ['type' => 'text', 'text' => 'Calle Lago de Chapala 105, San Pedro Cholula, C.P. 72760'],
                    ['type' => 'text', 'text' => "• 1x Sala Chesterfield ($24,500.00 MXN)\n• 2x Cojín Decorativo ($800.00 MXN)"],
                    ['type' => 'text', 'text' => '$25,300.00 MXN'],
                ]
            ]
        ]
    ]
];

echo "PAYLOAD PROBADO:\n" . json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

$response = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->post($apiUrl, $payload);

echo "HTTP STATUS: " . $response->status() . "\n";
echo "RESPONSE BODY:\n" . $response->body() . "\n";

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiToken = config('services.whatsapp.api_token');
$apiUrl = config('services.whatsapp.api_url');
$numeroVentas = config('services.whatsapp.ventas_number');

$languages = [
    'es', 'es_MX', 'es_ES', 'es_AR', 'es_CO', 'es_CL', 'es_PE', 
    'es_LA', 'es_US', 'es_VE', 'es_GT', 'es_EC', 'es_CR', 'es_DO',
    'spanish'
];

$templateNames = [
    'aviso_nuevo_contacto',
    'aviso_nuevo_contacto_1',
    'aviso_nuevo_contacto_admin',
    'aviso_nuevo_contacto_ventas'
];

foreach ($templateNames as $tpl) {
    foreach ($languages as $lang) {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $numeroVentas,
            'type'              => 'template',
            'template'          => [
                'name'     => $tpl,
                'language' => ['code' => $lang],
                'components' => [
                    [
                        'type'       => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => 'Prueba'],
                            ['type' => 'text', 'text' => '2226702641'],
                            ['type' => 'text', 'text' => 'Direccion'],
                            ['type' => 'text', 'text' => 'Productos'],
                            ['type' => 'text', 'text' => '$100.00']
                        ]
                    ]
                ]
            ]
        ];

        $res = Illuminate\Support\Facades\Http::withoutVerifying()
            ->withToken($apiToken)
            ->post($apiUrl, $payload);

        $status = $res->status();
        $body = $res->body();
        if ($status === 200) {
            echo "SUCCESS! Template: '{$tpl}', Language: '{$lang}' -> Status 200\n";
            echo "Response: {$body}\n";
            exit(0);
        } else {
            // Check if error is something OTHER than template not found
            $json = $res->json();
            $code = $json['error']['code'] ?? null;
            $msg = $json['error']['message'] ?? '';
            if ($code !== 132001) {
                echo "DIFFERENT ERROR for Template: '{$tpl}', Lang: '{$lang}' -> Code {$code}: {$msg}\n";
            }
        }
    }
}

echo "Finished testing all combinations.\n";

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiToken = config('services.whatsapp.api_token');
$apiUrl = config('services.whatsapp.api_url');
$numeroVentas = config('services.whatsapp.ventas_number');

$languages = ['es', 'es_MX', 'es_ES'];

// Test variations of parameters count from 0 to 6
for ($paramCount = 0; $paramCount <= 6; $paramCount++) {
    foreach ($languages as $lang) {
        $components = [];
        if ($paramCount > 0) {
            $params = [];
            for ($i = 1; $i <= $paramCount; $i++) {
                $params[] = ['type' => 'text', 'text' => "Dato{$i}"];
            }
            $components[] = [
                'type' => 'body',
                'parameters' => $params
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $numeroVentas,
            'type'              => 'template',
            'template'          => [
                'name'     => 'aviso_nuevo_contacto',
                'language' => ['code' => $lang]
            ]
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        $res = Illuminate\Support\Facades\Http::withoutVerifying()
            ->withToken($apiToken)
            ->post($apiUrl, $payload);

        $status = $res->status();
        $body = $res->body();
        $json = $res->json();
        $code = $json['error']['code'] ?? null;
        $details = $json['error']['error_data']['details'] ?? $json['error']['message'] ?? '';

        echo "Lang: {$lang} | Params: {$paramCount} => HTTP {$status} | Error Code: {$code} | Details: {$details}\n";

        if ($status === 200) {
            echo "SUCCESS FOR LANG {$lang} AND {$paramCount} PARAMS!\n";
            echo "RESPONSE: {$body}\n";
            exit(0);
        }
    }
}

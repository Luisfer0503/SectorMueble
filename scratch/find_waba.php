<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiToken = config('services.whatsapp.api_token');
$phoneId = '1366213626565139';

$res = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->get("https://graph.facebook.com/v25.0/{$phoneId}?metadata=1");

echo "Metadata Status: " . $res->status() . "\n";
echo "Metadata Body:\n" . json_encode($res->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

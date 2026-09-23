<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiToken = config('services.whatsapp.api_token');
$apiUrl = config('services.whatsapp.api_url');

preg_match('/graph\.facebook\.com\/v[0-9.]+\/([0-9]+)\/messages/', $apiUrl, $matches);
$phoneId = $matches[1] ?? '1366213626565139';

// Check entity details
$res = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->get("https://graph.facebook.com/v25.0/{$phoneId}");
echo "Entity Details Status: " . $res->status() . "\n";
echo "Response: " . $res->body() . "\n\n";

// Check debug token / me
$resMe = Illuminate\Support\Facades\Http::withoutVerifying()
    ->withToken($apiToken)
    ->get("https://graph.facebook.com/v25.0/me");
echo "ME Status: " . $resMe->status() . "\n";
echo "Response: " . $resMe->body() . "\n\n";

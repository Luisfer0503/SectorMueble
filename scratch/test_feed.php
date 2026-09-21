<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $res = app(App\Http\Controllers\FeedController::class)->xml(request());
    echo "STATUS: " . $res->getStatusCode() . "\n";
    echo "CONTENT-TYPE: " . $res->headers->get('Content-Type') . "\n";
    echo "SIZE: " . strlen($res->getContent()) . " bytes\n";
    echo "PREVIEW:\n" . substr($res->getContent(), 0, 500) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
}

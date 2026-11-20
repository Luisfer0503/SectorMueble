<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::find(1);
\Illuminate\Support\Facades\Auth::login($user);

$request = \Illuminate\Http\Request::create('/admin/ropa/excel', 'GET', [
    'categoria' => 'TODAS',
    'formato'   => 'xls'
]);

// Set session for request
$session = $app->make('session')->driver();
$session->setId('test-session-id');
$session->start();
$session->put(\Illuminate\Support\Facades\Auth::getName(), $user->getAuthIdentifier());
$session->put('login_web_' . sha1(\Illuminate\Auth\SessionGuard::class), $user->getAuthIdentifier());
$request->setLaravelSession($session);

try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    echo "Content-Disposition: " . $response->headers->get('Content-Disposition') . "\n";
    if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        echo "Length: " . strlen($content) . "\n";
        echo "Hex header: " . bin2hex(substr($content, 0, 8)) . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// Bootstrap the application so service providers (DB, Auth, etc.) are registered
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// find a support user (type = 2)
$user = App\Models\User::where('type', '2')->first();
if (!$user) {
    echo "No user with type=2 found.\n";
    exit(1);
}

// login the user in this process
Auth::login($user);

// create request to root
$request = Request::create('/', 'GET');
try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . PHP_EOL;
    echo "Headers:\n";
    foreach ($response->headers->all() as $k => $v) {
        echo "  $k: " . implode(', ', $v) . PHP_EOL;
    }
    $content = (string) $response->getContent();
    echo "Body (first 1000 chars):\n" . substr($content, 0, 1000) . PHP_EOL;
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

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

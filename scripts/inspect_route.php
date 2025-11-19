<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$router = $app->make(Illuminate\Routing\Router::class);
$request = Illuminate\Http\Request::create('/','GET');
try {
    $route = $router->getRoutes()->match($request);
    echo "Route uri: " . $route->uri() . PHP_EOL;
    echo "Action: " . (is_string($route->getActionName()) ? $route->getActionName() : 'closure') . PHP_EOL;
    echo "Middleware list:\n";
    $middleware = $route->gatherMiddleware();
    var_export($middleware);
    echo PHP_EOL;
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
}

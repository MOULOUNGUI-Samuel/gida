<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
echo 'Kernel class: ' . get_class($kernel) . PHP_EOL;
$r = new ReflectionClass(get_class($kernel));
echo 'Kernel file: ' . $r->getFileName() . PHP_EOL;
$defaults = $r->getDefaultProperties();
echo "Default routeMiddleware:\n";
var_export($defaults['routeMiddleware'] ?? null);
echo PHP_EOL;
$p = $r->getProperty('routeMiddleware');
$p->setAccessible(true);
echo "Current routeMiddleware on instance:\n";
var_export($p->getValue($kernel));
echo PHP_EOL;

<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$applicationPath = __DIR__ . '/laravel_app';

if (file_exists($maintenance = $applicationPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $applicationPath . '/vendor/autoload.php';

$app = require_once $applicationPath . '/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

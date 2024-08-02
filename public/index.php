<?php

use App\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

$kernel = new Kernel($env, $debug);
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

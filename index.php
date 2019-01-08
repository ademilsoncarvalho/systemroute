<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';

$path_info = $_SERVER['PATH_INFO'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';


$router = new App\Router\Router($request_method, $path_info);

$router->get('/', function () {
    return 'oi';
});

$result  = $router->run();
print_r($result['callback']());
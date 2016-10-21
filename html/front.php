<?php

require dirname(__DIR__).'/bootstrap/start.php';

// Set up the router.
$pages_dir = dirname(__DIR__).'/pages';
$router = new Mlaphp\Router($pages_dir);

// Match against the url path.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $router->match($path);

// Require the page script.
require $route;

if (isset($controller)) {
    $response = $controller->__invoke();
    $response->send();
}
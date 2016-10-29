<?php

require dirname(__DIR__).'/bootstrap/start.php';
require dirname(__DIR__).'/bootstrap/services.php';

// Set up the router.
$router = $di->get('router');

// Match against the url path.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $router->match($path);

// Container service, or page script?
if ($di->has($route)) {
    // Create a new controller instance.
    $controller = $di->newInstance($route);
    $response = $controller->__invoke();
    $response->send();
} else {
    // Require the page script.
    require $route;
}

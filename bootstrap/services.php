<?php

$di = new Mlaphp\Di($GLOBALS);

$di->set('database', function() {
    return new OceanCrest\DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
});

$di->set('response', function() {
    return new \Mlaphp\Response(__DIR__.'/../views');
});

$di->set('router', function() {
    $router = new Mlaphp\Router(dirname(__DIR__).'/pages');
    $router->setRoutes([
        '/calendar.php' => 'OceanCrest\Controllers\CalendarController',
        '/calendar' => 'OceanCrest\Controllers\CalendarController',
        '/add_event.php' => 'OceanCrest\Controllers\CreateEventController',
        '/details.php' => 'OceanCrest\Controllers\ReservedEventController',
    ]);
    return $router;
});

$di->set('OceanCrest\Controllers\CalendarController', function() use ($di) {
    $di->get('middleware');
    return new OceanCrest\Controllers\CalendarController(
        $di->request,
        $di->newInstance('OceanCrest\EventGateway'),
        $di->newInstance('response')
    );
});

$di->set('OceanCrest\Controllers\CreateEventController', function() use ($di) {
    $di->get('middleware');
    return new OceanCrest\Controllers\CreateEventController(
        $di->request,
        $di->newInstance('OceanCrest\EventGateway'),
        $di->newInstance('response')
    );
});

$di->set('OceanCrest\Controllers\ReservedEventController', function() use ($di) {
    $di->get('middleware');
    return new OceanCrest\Controllers\ReservedEventController(
        $di->request,
        $di->newInstance('OceanCrest\EventGateway'),
        $di->newInstance('response')
    );
});

$di->set('OceanCrest\EventGateway', function() use ($di) {
    return new OceanCrest\EventGateway($di->get('database'));
});

$di->set('OceanCrest\UserGateway', function() use ($di) {
    return new OceanCrest\UserGateway($di->get('database'));
});

$di->set('OceanCrest\AuthGateway', function() use ($di) {
    return new OceanCrest\AuthGateway($di->get('database'));
});

$di->set('OceanCrest\UserTransactions', function() use ($di) {
    return new OceanCrest\UserTransactions($di->get('OceanCrest\UserGateway'));
});

$di->set('OceanCrest\AuthTransactions', function() use ($di) {
    return new OceanCrest\AuthTransactions($di->get('OceanCrest\AuthGateway'), $di->request);
});


$di->set('middleware', function() use ($di) {
    // MAKE SURE THEY ARE LOGGED IN
    if (! isset($di->request->session['name'])) {
        // Start defining the URL.
        $url = 'http://' . $di->request->server['HTTP_HOST'] . dirname($di->request->server['PHP_SELF']);
        // Check for a trailing slash.
        if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
            $url = substr ($url, 0, -1); // Chop off the slash.
        }
        // Add the page.
        $url .= '/index.php';

        ob_end_clean(); // Delete the buffer.
        header("Location: $url");
        exit(); // Quit the script.
    }
});
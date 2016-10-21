<?php

require '../bootstrap/start.php';

$request = new Mlaphp\Request($GLOBALS);
$response = new Mlaphp\Response(__DIR__.'/../views');
$controller = new OceanCrest\Controllers\NotFound($request, $response);

$response = $controller->__invoke();

$response->send();
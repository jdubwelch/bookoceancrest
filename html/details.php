<?php 

use Mlaphp\Response;
use OceanCrest\DB;
use OceanCrest\EventGateway;
use OceanCrest\Controllers\EventController;

require_once("../bootstrap/start.php");

$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$eventGateway = new EventGateway($db);
$response = new Response(__DIR__.'/../views'); 
$controller = new EventController($request, $eventGateway, $response);

$response = $controller->__invoke();
$response->send();

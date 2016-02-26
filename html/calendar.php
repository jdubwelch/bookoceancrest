<?php

use OceanCrest\DB;
use OceanCrest\EventGateway;
use OceanCrest\Controllers\CalendarController;

require_once("../bootstrap/start.php"); 

// MAKE SURE THEY ARE LOGGED IN
if (! isset($request->session['name'])) {
	// Start defining the URL.
	$url = 'http://' . $request->server['HTTP_HOST'] . dirname($request->server['PHP_SELF']);
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

$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$eventGateway = new EventGateway($db);
$response = new \Mlaphp\Response(__DIR__.'/../views'); 
$controller = new CalendarController($request, $eventGateway, $response);
$response = $controller->__invoke();
$response->send();
?>
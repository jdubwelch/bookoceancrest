<?php 

use OceanCrest\DB;
use OceanCrest\EventGateway;

require_once("../bootstrap/start.php"); 

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

if($request->post['action'] == "add"){
	
    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$eventGateway = new EventGateway($db);
    $eventGateway->reserve(
        $request->session['name'], 
        $request->post['date'], 
        $request->post['staying']
    );

    $dateArray = explode("/", $request->post['date']);
    $mo = $dateArray[1];
    $yr = $dateArray[2];

	// Return to Calendar Page
	header("Location: calendar.php?month=$mo&year=$yr");
}

$da = $request->get['day'];
$da = explode ('/', $da);

$day = $da[0];
$month = $da[1];
$year = $da[2];

$response = new \Mlaphp\Response(__DIR__.'/../views'); 
$response->setView('add_event.php'); 
$response->setVars([
    'request' => $request,
    'name' => $request->session['name'],
    'action' => $request->server['PHP_SELF'],
    'day' => $request->get['day'],
    'arrival_date' => "$month/$day/$year",
]);
$response->send();

?>
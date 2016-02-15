<?php 

use OceanCrest\DB;
use OceanCrest\EventGateway;

require_once("../bootstrap/start.php"); 

// Check that we have a date parameter in the URL, if none redirect back to calendar page
if(strlen($request->get['day']) < 1){
	header("Location: index.php");
	exit();
}

// MAKE SURE THEY ARE LOGGED IN
if (isset($request->session['name'])) {
    $name = $request->session['name'];
} else {
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

// Create Timestamps to read in all events on given day
$date = $request->get['day'];

$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$eventGateway = new EventGateway($db);
$records = $eventGateway->details($date);

$dateArray = explode("/",$date);
$month = $dateArray[1];
$day = $dateArray[0];
$year = $dateArray[2];

if (isset($request->post['delete'])) {

	// CHECK TO MAKE SURE THE RIGHT PERSON IS ACCESSING THIS PAGE
	if ($name == $records[0]['family']) {
		
		$id = $request->post['id'];
		$result = $eventGateway->cancel($id);
		
		if ($result) {
			header("Location: calendar.php?month=$month&year=$year");
			exit();
		}
	} 
}

$response = new \Mlaphp\Response(__DIR__.'/../views'); 
$response->setView('details.php'); 
$response->setVars([
    'request' => $request,
    'name' => $request->session['name'],
    'event' => [
        'family' => $records[0]['family'],
        'id' => $records[0]['id'],
        'date' => "$month/$day/$year",
        'owned' => ($request->session['name'] == $records[0]['family'])
    ]   
]);
$response->send();

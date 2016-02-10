<?php

use OceanCrest\DB;
use OceanCrest\EventGateway;

require_once("../bootstrap/start.php"); 


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> CALENDAR';

// MAKE SURE THEY ARE LOGGED IN
if (isset($request->session['name'])) {

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

//  Set the date to display
if (isset($request->post['submit'])) {

    $submit = $request->post['submit'];
    $month_now = $request->post['month'];
    $year_now = $request->post['year'];

	// Subtract one from month for prev and add 1 for next
	if ($submit == "Prev") {
		$month_now--;
	} else {
		$month_now++;
	} 
	
	$date = getdate(mktime(0,0,0,$month_now, 1, $year_now));

} elseif (isset($request->get['month'])) {

	$date = getdate(mktime(0,0,0, $request->get['month'], 1, $request->get['year']));
		
} else {
	$date = getdate();
}

$month = $date["mon"];
$monthName = $date["month"];
$year = $date["year"];

$db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$eventGateway = new EventGateway($db);

$eventData = $eventGateway->monthlyEvents($month, $year);

$response = new \Mlaphp\Response(__DIR__.'/../views'); 
$response->setView('calendar.php'); 
$response->setVars([
    'request' => $request,
    'name' => $request->session['name'],
    'month' => $month,
    'year' => $year,
    'monthName' => $monthName,
    'eventData' => $eventData
]);
$response->send();
?>
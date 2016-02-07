<?php 

use OceanCrest\DB;
use OceanCrest\EventGateway;
include ('./includes/header.php');
require_once("../cgi-bin/oc/dbConnection.php"); ?>

<?php
// Check that we have a date parameter in the URL, if none redirect back to calendar page
if(strlen($request->get['day']) < 1){
	header("Location: index.php");
	exit();
}

// MAKE SURE THEY ARE LOGGED IN
if (isset($request->session['name'])) {
	echo "<p>$request->session[name]</p>";
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
		
	} else {
		echo "it's not.";
	}
}

?>

<h3><?php echo $records[0]['family'] . " have reserved the cabin on $month/$day/$year"; ?></h3>
  
  <p>&nbsp;</p>
  <?php 
  if(strlen($records[0]['family']) > 0) { 

// CHECK TO MAKE SURE THE RIGHT PERSON IS ACCESSING THIS PAGE
$id = $records[0]['id'];

if ($name == $records[0]['family']) { 
	echo "<form action=\"\" method=\"post\">
		<input name=\"id\" type=\"hidden\" value=\"$id\">
		<input name=\"delete\" type=\"submit\" value=\" click if not staying anymore \">
	</form>";
} 
	
	
} else { ?>
  <p align='center'><h3>No Current Records</h3></p>
  <?php } ?>
  <p>&nbsp;</p>
  <p><a href="calendar.php">Return to Calendar</a> </p>
<?

include ('./includes/footer.php');

?>
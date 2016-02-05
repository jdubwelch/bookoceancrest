<?php 
include ('./includes/header.php');
require_once("../cgi-bin/oc/dbConnection.php"); ?>

<?php
// Check that we have a date parameter in the URL, if none redirect back to calendar page
if(strlen($_GET['day']) < 1){
	header("Location: index.php");
	exit();
}

// MAKE SURE THEY ARE LOGGED IN
if (isset($_SESSION['name'])) {
	echo "<p>$_SESSION[name]</p>";
} else {
	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
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
$date = $_GET['day'];
$dateArray = explode("/",$date);
$startTimestamp = mktime(0,0,0,$dateArray[1],$dateArray[0],$dateArray[2]);
$endTimestamp = mktime(23,59,00,$dateArray[1],$dateArray[0],$dateArray[2]);

// Create SQL to read in database records
$sql  = "SELECT id,family, UNIX_TIMESTAMP(dateField) AS timestampField  ";
$sql .= "FROM ocCalendar ";
$sql .= "WHERE UNIX_TIMESTAMP(dateField) >= " . $startTimestamp . " AND UNIX_TIMESTAMP(dateField) <= " . $endTimestamp . " ";
$sql .= "ORDER BY UNIX_TIMESTAMP(dateField) ASC";


// Read in Records from Database  
$dbResult = mysql_query($sql)
  or die ("MySQL Error: " . mysql_error() );
$numRecords = mysql_num_rows($dbResult);
for($i=0;$i < $numRecords;$i++){
	$temp = mysql_fetch_assoc($dbResult);
	if (!get_magic_quotes_gpc()) {
		$temp['family'] = stripslashes($temp['family']);
	}
	$records[] = $temp;
}

$month = $dateArray[1];
$day = $dateArray[0];
$year = $dateArray[2];


if (isset($_POST['delete'])) {
	
	// CHECK TO MAKE SURE THE RIGHT PERSON IS ACCESSING THIS PAGE
	if ($name == $records[0]['family']) {
		
		$id = $_POST['id'];
		
		$query = "DELETE FROM `ocCalendar` WHERE `id` = '$id' LIMIT 1";
		$result = mysql_query($query);
		
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
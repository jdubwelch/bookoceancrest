<?php 

require_once("../cgi-bin/oc/dbConnection.php");


include ('./includes/header.php');

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

if($_POST['action'] == "add"){

	// Read data to insert into Database
	if (!get_magic_quotes_gpc()) {
		$date = addslashes($_POST['date']);
		$family = $_SESSION['name'];
		$event = '';
		$staying = addslashes($_POST['staying']);
	} else {
		$date = $_POST['date'];
		$family = $_SESSION['name'];
		$event = '';
		$staying = $_POST['staying'];
	}
	
	// Create Date Time Field
	$time = "00:00:00";
	$dateArray = explode("/",$date);
	$d = $dateArray[0];
	$yr = $dateArray[2];
	$mo = $dateArray[1];
	
	if ($staying > 1) {
		for ($i = 0; $i < $staying; $i++) {
			$reserveDates = $d + $i;
			$datetime = "$yr-$mo-$reserveDates $time";
			$sql = "INSERT INTO ocCalendar (dateField,family,event) VALUES ('" . $datetime . "','" . $family . "','" . $event . "')";
			mysql_query($sql);
			
		}
	} else {
		$datetime = "$yr-$mo-$d $time";
		$sql = "INSERT INTO ocCalendar (dateField,family,event) VALUES ('" . $datetime . "','" . $family . "','" . $event . "')";
		mysql_query($sql);
	}
			
	// Close Database Connection  
	mysql_close();
	
	// Return to Calendar Page
	header("Location: calendar.php?month=$mo&year=$yr");
}

$da = $_GET['day'];

$da = explode ('/', $da);

$day = $da[0];
$month = $da[1];
$year = $da[2];

$dayofarrival = "$month/$day/$year";




?>

<h3>Add a Calendar Event</h3>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table width="450" border="0" cellpadding="3" cellspacing="0" id="addEvent">  
<tr>
	<td width="100" align="right">Date of Arrival:<input name="date" type="hidden" value="<?php echo $_GET['day']; ?>"></td>
	<td align="left"><?php echo $dayofarrival; ?></td>
  </tr>

  <tr>
	<td width="100" align="right">No. of days staying** </td>
	<td align="left"><select name="staying">
	  <option value="1">1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  <option value="4">4</option>
	  <option value="5">5</option>
	  <option value="6">6</option>
	  <option value="7">7</option>
	</select></td>
  </tr>
  <tr>
	<td width="100" align="right"><input name="action" type="hidden" id="action" value="add"></td>
	<td align="center"><input type="submit" name="Submit" value="Add Event"></td>
  </tr>
</table>
<p>**note: the number of days does not carry over to the next month </p>
</form>

<?php
include ('./includes/footer.php');
?>

<?php 

use OceanCrest\DB;
use OceanCrest\EventGateway;

require_once("../cgi-bin/oc/dbConnection.php");

include ('./includes/header.php');

if (isset($request->session['name'])) {
	echo "<p>{$request->session['name']}</p>";
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

if($request->post['action'] == "add"){
	
    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$eventGateway = new EventGateway($db);
    $eventGateway->reserve(
        $request->session['family'], 
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

$dayofarrival = "$month/$day/$year";

?>

<h3>Add a Calendar Event</h3>
<form name="form1" method="post" action="<?php echo $request->server['PHP_SELF']; ?>">
<table width="450" border="0" cellpadding="3" cellspacing="0" id="addEvent">  
<tr>
	<td width="100" align="right">Date of Arrival:<input name="date" type="hidden" value="<?php echo $request->get['day']; ?>"></td>
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

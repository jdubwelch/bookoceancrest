<?php # Script 13.7 - activate.php

use OceanCrest\DB;
use OceanCrest\UserGateway;
// This page activates the user's account.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Activate Your Account';
include ('./includes/header.php');

// MAKE SURE IT'S AN ADMINISTRATOR
if ($_SESSION['user_id'] != '1') {	// it's not an ADMIN
	
	echo "<h1 class=\"error\">I'm sorry, you don't have the proper access to view this page</h1>";
	include('includes/footer.php');
	exit();
}

// Validate $_GET['x'] and $_GET['y'].
if (isset($_GET['x'])) {
	$x = (int) $_GET['x'];
} else {
	$x = 0;
}

// If $x and $y aren't correct, redirect the user.
if ($x > 0) {

	require_once("../cgi-bin/oc/dbConnection.php");

    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $userGateway = new UserGateway($db);
    $activated = $userGateway->activate($x);
	
	// Print a customized message.
	if ($activated) {
		echo "<h3>Your account is now active. You may now log in.</h3>";
	} else {
		echo '<p><font color="red" size="+1">Your account could not be activated. Please re-check the link or contact the system administrator.</font></p>'; 
	}
	
	$email = $userGateway->email($x);
	
	$body = "You're bookoceancrest account has now been activated.\n\n
	Please login at http://www.bookoceancrest.com/login.php using your email and the password you set up.\n\n
	This was an automatically generated email, please do not reply.\n\n
	With questions about the website contact Jason Welch at jason@jwelchdesign.com\n\n";
	
	$subject = "Ocean Crest Acount";
	
	mail($email, $subject, $body, 'From: info@bookoceancrest.com');

} else { // Redirect.

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	// Add the page.
	$url .= '/index2.php';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

} // End of main IF-ELSE.

include ('./includes/footer.php');
?>
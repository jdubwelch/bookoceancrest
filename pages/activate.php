<?php # Script 13.7 - activate.php

use OceanCrest\DB;
use OceanCrest\UserGateway;
use OceanCrest\UserTransactions;
// This page activates the user's account.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Activate Your Account';
include (__DIR__.'/includes/header.php');

// MAKE SURE IT'S AN ADMINISTRATOR
if ($request->session['user_id'] != '1') {	// it's not an ADMIN
	
	echo "<h1 class=\"error\">I'm sorry, you don't have the proper access to view this page</h1>";
	include(__DIR__.'includes/footer.php');
	exit();
}

// Validate $request->get['x'] and $request->get['y'].
if (isset($request->get['x'])) {
	$x = (int) $request->get['x'];
} else {
	$x = 0;
}

// If $x and $y aren't correct, redirect the user.
if ($x > 0) {

	require_once(__DIR__.'/../cgi-bin/config/database.php');

    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $userGateway = new UserGateway($db);
    $userTransactions = new UserTransactions($userGateway);

    $activated = $userTransactions->activate($x);
	
	// Print a customized message.
	if ($activated) {
		echo "<h3>Your account is now active. You may now log in.</h3>";
	} else {
		echo '<p><font color="red" size="+1">'.implode('<br />', $userTransactions->getErrors()).'</font></p>'; 
	}
	

} else { // Redirect.

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

} // End of main IF-ELSE.

include (__DIR__.'/includes/footer.php');
?>
<?php 

# Script 13.11 - change_password.php
// This page allows a logged-in user to change their password.

use OceanCrest\DB;
use OceanCrest\UserGateway;
use OceanCrest\UserTransactions;

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Change Your Password';
include ('./includes/header.php');

// If no first_name variable exists, redirect the user.
if (!isset($request->session['name'])) {

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

if (isset($request->post['submitted'])) { // Handle the form.

	require_once("../cgi-bin/config/database.php"); // Connect to the database.

    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $userGateway = new UserGateway($db);
    $userTransactions = new UserTransactions($userGateway);

    $result = $userTransactions->changePassword(
        $request->session['user_id'], 
        $request->post['password1'],
        $request->post['password2']
    );

    if ($result) {
        echo '<h3>Your password has been changed.</h3>';
        include ('./includes/footer.php'); // Include the HTML footer.
        exit();
    }

    if (count($userTransactions->getErrors())) {
        echo '<p><font color="red" size="+1">'.implode('<br />', $userTransactions->getErrors()).'</font></p>';
    }

} // End of the main Submit conditional.

?>

<h1>Change Your Password</h1>
<form action="change_password.php" method="post">
	<fieldset>
	<p><b>New Password:</b> <input type="password" name="password1" size="20" maxlength="20" /> <small>Use only letters and numbers. Must be between 4 and 20 characters long.</small></p>
	<p><b>Confirm New Password:</b> <input type="password" name="password2" size="20" maxlength="20" /></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Change My Password" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('./includes/footer.php');
?>
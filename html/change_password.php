<?php 

# Script 13.11 - change_password.php
// This page allows a logged-in user to change their password.

use OceanCrest\DB;
use OceanCrest\UserGateway;

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
	
} else {

	if (isset($request->post['submitted'])) { // Handle the form.
	
		require_once("../cgi-bin/oc/dbConnection.php"); // Connect to the database.

        $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		// Check for a new password and match against the confirmed password.
		if (eregi ('^[[:alnum:]]{4,20}$', stripslashes(trim($request->post['password1'])))) {
			if ($request->post['password1'] == $request->post['password2']) {
				$p = $db->escape_data($request->post['password1']);
			} else {
				$p = FALSE;
				echo '<p><font color="red" size="+1">Your password did not match the confirmed password!</font></p>';
			}
		} else {
			$p = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid password!</font></p>';
		}
		
		if ($p) { // If everything's OK.


            $userGateway = new UserGateway($db);

            $updated = $userGateway->updatePassword($request->session['user_id'], $p);
	
			if ($updated) { // If it ran OK.
			
				// Send an email, if desired.
				echo '<h3>Your password has been changed.</h3>';
				include ('./includes/footer.php'); // Include the HTML footer.
				exit();				
				
			} else { // If it did not run OK.
			
				// Send a message to the error log, if desired.
				echo '<p><font color="red" size="+1">Your password could not be changed due to a system error. We apologize for any inconvenience.</font></p>'; 

			}		
	
		} else { // Failed the validation test.
			echo '<p><font color="red" size="+1">Please try again.</font></p>';		
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
} // End of the !isset($request->session['first_name']) ELSE.
include ('./includes/footer.php');
?>
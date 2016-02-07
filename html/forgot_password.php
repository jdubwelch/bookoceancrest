<?php 

use OceanCrest\DB;
use OceanCrest\UserGateway;

# Script 13.10 - forgot_password.php 
// This page allows a user to reset their password, if forgotten.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Forgot Your Password';
include ('./includes/header.php');

if (isset($request->post['submitted'])) { // Handle the form.

	require_once("../cgi-bin/oc/dbConnection.php"); // Connect to the database.
    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $userGateway = new UserGateway($db);

	if (empty($request->post['email'])) { // Validate the email address.
		$uid = FALSE;
		echo '<p><font color="red" size="+1">You forgot to enter your email address!</font></p>';
	} else {

		// Check for the existence of that email address.
        $uid = $userGateway->getUserByEmail($request->post['email']);
	}

    echo "<pre style='color:cyan; background:#212121; padding:2em;'>";
    echo '#### uid'."\n";
    var_dump($uid); 
    echo "</pre>";
    die();
	
	if ($uid) { // If everything's OK.

		// Create a new, random password.
		$p = substr ( md5(uniqid(rand(),1)), 3, 10);

		// Make the query.
        $result = $userGateway->updatePassword($uid, $p);
		if ($result) { // If it ran OK.
		
			// Send an email.
			$body = "Your password to log into bookoceancrest.com has been temporarily changed to '$p'. Please log in using this password and your username. At that time you may change your password to something more familiar.";
			mail ($request->post['email'], 'Your temporary password.', $body, 'From: info@bookoceancrest.com');
			echo '<h3>Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it by clicking on the "Change Password" link.</h3>';
			include ('./includes/footer.php'); // Include the HTML footer.
			exit();				
			
		} else { // If it did not run OK.
		
			echo '<p><font color="red" size="+1">Your password could not be changed due to a system error. We apologize for any inconvenience.</font></p>'; 

		}		

	} else { // Failed the validation test.
		echo '<p><font color="red" size="+1">Please try again.</font></p>';		
	}

} // End of the main Submit conditional.

?>

<h1>Reset Your Password</h1>
<p>Enter your email address below and your password will be reset.</p> 
<form action="forgot_password.php" method="post">
	<fieldset>
	<p><b>Email Address:</b> <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" /></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Reset My Password" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('./includes/footer.php');
?>
<?php # Script 13.8 - login.php

use OceanCrest\DB;
use OceanCrest\AuthGateway;

// This is the login page for the site.

// Set the page title and include the HTML header.
$page_title = 'Login';
include ('./includes/header.php');

if (isset($_POST['submitted'])) { // Check if the form has been submitted.

	require_once("../cgi-bin/oc/dbConnection.php"); // Connect to the database.
    
    $db = new DB($dbc);

	// Validate the email address.	
	if (!empty($_POST['email'])) {
		$e = $db->escape_data($_POST['email']);
	} else {
		echo '<p><font color="red" size="+1">You forgot to enter your email address!</font></p>';
		$e = FALSE;
	}
	
	// Validate the password.
	if (!empty($_POST['pass'])) {
		$p = $db->escape_data($_POST['pass']);
	} else {
		$p = FALSE;
		echo '<p><font color="red" size="+1">You forgot to enter your password!</font></p>';
	}
	
	if ($e && $p) { // If everything's OK.
	
		// Query the database.
        $credentials = [
            'email' => $e,
            'password' => $p
        ];

        $authGateway = new AuthGateway($dbc);
		if ($user = $authGateway->attempt($credentials)) { // A match was made.

			
			$_SESSION['name'] = $user->name;
			$_SESSION['user_id'] = $user->id;
			$_SESSION['side'] = $user->side;
							
			// Start defining the URL.
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
			// Check for a trailing slash.
			if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
				$url = substr ($url, 0, -1); // Chop off the slash.
			}
			// Add the page.
			$url .= '/calendar.php';
			
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No match was made.
			echo '<p><font color="red" size="+1">That was the wrong email or your account has not been activated.</font></p>'; 
		}
		
	} else { // If everything wasn't OK.
		echo '<p><font color="red" size="+1">Please try again.</font></p>';		
	}
	
} // End of SUBMIT conditional.
?>

<h1>Login-Update</h1>
<p>Your browser must allow cookies in order to log in.</p>
<form action="login.php" method="post">
	<fieldset>
	<p><b>Email Address:</b> <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></p>
	<p><b>Password:</b> <input type="password" name="pass" size="20" maxlength="20" /></p>
	<p><a href="forgot_password.php">forget password? </a></p>
	<div align="center"><input type="submit" name="submit" value="Login" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>

<?php // Include the HTML footer.
include ('./includes/footer.php');
?>
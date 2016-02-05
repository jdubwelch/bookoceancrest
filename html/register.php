<?php # Script 13.6 - register.php
// This is the registration page for the site.

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> REGISTER';
include ('./includes/header.php');

// Instantiate the AYAH object.
require_once("./includes/ayah.php");
$ayah = new AYAH();


if (isset($_POST['submitted'])) { // Handle the form.
	
	// Use the AYAH object to get the score.
	$score = $ayah->scoreResult();

	// Check the score to determine what to do.
	if ($score)
	{
		// Add code to process the form.
		require_once("../cgi-bin/oc/dbConnection.php"); // Connect to the database.

		// Check for a first name.
		if (stripslashes(trim($_POST['name']))) {
			$name = escape_data($_POST['name']);
		} else {
			$name = FALSE;
			echo '<p><font color="red" size="+1">Please enter your name.</font></p>';
		}

		if ($_POST['side'] != "0" ) {
			$side = $_POST['side'];
		} else {
			$side = FALSE;
			echo '<p><font color="red" size="+1">Please your side of the family.</font></p>';
		}


		// Check for an email address.
		if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
			$e = escape_data($_POST['email']);
		} else {
			$e = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid email address.</font></p>';
		}

		// Check for a password and match against the confirmed password.
		if (eregi ('^[[:alnum:]]{4,20}$', stripslashes(trim($_POST['password1'])))) {
			if ($_POST['password1'] == $_POST['password2']) {
				$p = escape_data($_POST['password1']);
			} else {
				$p = FALSE;
				echo '<p><font color="red" size="+1">Your password did not match the confirmed password!</font></p>';
			}
		} else {
			$p = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid password!</font></p>';
		}

		if ($name && $side && $e && $p) { // If everything's OK.

			// Make sure the email address is available.
			$query = "SELECT user_id FROM ocUsers WHERE email='$e'";		
			$result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());

			if (mysql_num_rows($result) == 0) { // Available.


				// Add the user.
				$query = "INSERT INTO `ocUsers` (`user_id`, `name`, `email`, `password`, `side`, `activated`) 
				VALUES ('', '$name', '$e', PASSWORD('$p'), '$side', '0');";		
				$result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());

				if (mysql_affected_rows() == 1) { // If it ran OK.

					// Send the email.
					$body = "Name: $name\n\nEmail: $e\n\nSide: $side\n\n";
					$body .= "http://www.bookoceancrest.com/activate.php?x=" . mysql_insert_id();
					mail('jason@jwelchdesign.com', 'bookoceancrest.com', $body, 'From: info@bookoceancrest.com');

					// Finish the page.
					echo '<h1>Thank you for registering! </h1>
					<p>You will receive and email once your account is activated.  It could take up to 24 hours to be activated.</p>';
					include ('./includes/footer.php'); // Include the HTML footer.
					exit();				

				} else { // If it did not run OK.
					echo '<p><font color="red" size="+1">You could not be registered due to a system error. We apologize for any inconvenience.</font></p>'; 
				}		

			} else { // The email address is not available.
				echo '<p><font color="red" size="+1">That email address has already been registered. If you have forgotten your password, use the link to have your password sent to you.</font></p>'; 
			}

		} else { // If one of the data tests failed.
			echo '<p><font color="red" size="+1">Please try again.</font></p>';		
		}

		mysql_close(); // Close the database connection.
	}
	// you are NOT a human
	else
	{
		echo '<p><font color="red" size="+1">Human verification failed. Please try again.</font></p>';	
	}
	

} // End of the main Submit conditional.
?>
	
<h1>Register</h1>
<form action="register.php" method="post">
	<fieldset>
	
	<p><b>Name:</b> 
    <input name="name" type="text" id="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" size="15" maxlength="15" />
    <small>(for example: Jason &amp; Deena) </small></p>
	
	<p><b>Side:</b> 
	  <select name="side" id="side">
	    <option value="0">-----------</option>
	    <option value="Schumacher">Schumacher</option>
	    <option value="Welch">Welch</option>
      </select>
	</p>
	
	<p><b>Email Address:</b> <input type="text" name="email" size="40" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /> </p>
		
	<p><b>Password:</b> <input type="password" name="password1" size="20" maxlength="20" /> <small>Use only letters and numbers. Must be between 4 and 20 characters long.</small></p>
	
	<p><b>Confirm Password:</b> <input type="password" name="password2" size="20" maxlength="20" /></p>
	</fieldset>
	
	<?php
		// Use the AYAH object to get the HTML code needed to
		// load and run the PlayThru.
		echo $ayah->getPublisherHTML();
	?>
		
	<div align="center"><input type="submit" name="submit" value="Register" /></div>
	<input type="hidden" name="submitted" value="TRUE" />

</form>

<?php // Include the HTML footer.
include ('./includes/footer.php');
?>
<?php # Script 13.10 - forgot_password.php
// This page allows a user to reset their password, if forgotten.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Forgot Your Password';
include ('./includes/header.php');

if (isset($_POST['submitted'])) { // Handle the form.

	require_once("../../cgi-bin/oc/dbConnection.php"); // Connect to the database.

	if (empty($_POST['email'])) { // Validate the email address.
		$uid = FALSE;
		echo '<p><font color="red" size="+1">You forgot to enter your email address!</font></p>';
	} else {

		// Check for the existence of that email address.
		$query = "SELECT user_id FROM ocUsers WHERE email='".  escape_data($_POST['email']) . "'";		
		$result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());
		if (mysql_num_rows($result) == 1) {

			// Retrieve the user ID.
			list($uid) = mysql_fetch_array ($result, MYSQL_NUM); 

		} else {
			echo '<p><font color="red" size="+1">The submitted email address does not match those on file!</font></p>';
			$uid = FALSE;
		}
		
	}
	
	if ($uid) { // If everything's OK.

		
		// Make the query.
		$query = "SELECT PASSWORD(password) FROM ocUsers WHERE user_id = $uid";	
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			echo"<pre>"; print_r($row); echo"</pre>";
		}	
						
			
			

	} else { // Failed the validation test.
		echo '<p><font color="red" size="+1">Please try again.</font></p>';		
	}

	mysql_close(); // Close the database connection.

} // End of the main Submit conditional.

?>

<h1>Reset Your Password</h1>
<p>Enter your email address below and your password will be reset.</p> 
<form action="test.php" method="post">
	<fieldset>
	<p><b>Email Address:</b> <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></p>
	</fieldset>
	<div align="center"><input type="submit" name="submit" value="Reset My Password" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('./includes/footer.php');
?>
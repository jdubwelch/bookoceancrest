<?php # Script 13.6 - register.php

use OceanCrest\DB;
use OceanCrest\UserGateway;
use OceanCrest\UserTransactions;
// This is the registration page for the site.

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> REGISTER';
include ('./includes/header.php');

// Instantiate the AYAH object.
$ayah = new AYAH();

if (isset($request->post['submitted'])) { // Handle the form.
	
	// Use the AYAH object to get the score.
	$score = $ayah->scoreResult();

	// Check the score to determine what to do.
	if ($score)
	{
		// Add code to process the form.
		require_once("../cgi-bin/oc/dbConnection.php"); // Connect to the database.
        $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $userGateway = new UserGateway($db);
        $userTransactions = new UserTransactions($userGateway);

        $registered = $userTransactions->register([
            'name' => $request->post['name'],
            'side' => $request->post['side'],
            'email' => $request->post['email'],
            'password' => $request->post['password1'],
            'password_confirm' => $request->post['password2']
        ]);

        if ($registered) {
            echo '<h1>Thank you for registering! </h1>
            <p>You will receive and email once your account is activated.  It could take up to 24 hours to be activated.</p>';
        }
	}
	// you are NOT a human
	else
	{
        echo '<p><font color="red" size="+1">Human verification failed. Please try again.</font></p>';  
    }
    
		echo '<p><font color="red" size="+1">Human verification failed. Please try again.</font></p>';	

} // End of the main Submit conditional.
?>
	
<h1>Register</h1>
<form action="register.php" method="post">
	<fieldset>
	
	<p><b>Name:</b> 
    <input name="name" type="text" id="name" value="<?php if (isset($request->post['name'])) echo $request->post['name']; ?>" size="15" maxlength="15" />
    <small>(for example: Jason &amp; Deena) </small></p>
	
	<p><b>Side:</b> 
	  <select name="side" id="side">
	    <option value="0">-----------</option>
	    <option value="Schumacher">Schumacher</option>
	    <option value="Welch">Welch</option>
      </select>
	</p>
	
	<p><b>Email Address:</b> <input type="text" name="email" size="40" maxlength="40" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" /> </p>
		
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
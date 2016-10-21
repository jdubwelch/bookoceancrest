<?php # Script 13.8 - login.php

use OceanCrest\AuthGateway;
use OceanCrest\AuthTransactions;
use OceanCrest\DB;

// This is the login page for the site.

// Set the page title and include the HTML header.
$page_title = 'Login';
include (__DIR__.'/includes/header.php');

if (isset($request->post['submitted'])) { // Check if the form has been submitted.

	require_once(__DIR__."/../cgi-bin/config/database.php"); // Connect to the database.
    $db = new DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $authGateway = new AuthGateway($db);
    $authTransactions = new AuthTransactions($authGateway, $request);

    if ($authTransactions->attempt([
        'email' => $request->post['email'],
        'password' => $request->post['pass']
    ])) {

        // Start defining the URL.
        $url = 'http://' . $request->server['HTTP_HOST'] . dirname($request->server['PHP_SELF']);
        // Check for a trailing slash.
        if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
            $url = substr ($url, 0, -1); // Chop off the slash.
        }
        // Add the page.
        $url .= '/calendar.php';
        
        ob_end_clean(); // Delete the buffer.
        header("Location: $url");
        exit(); // Quit the script.
    }

    // Failure
    echo '<p><font color="red" size="+1">'.implode('<br />', $authTransactions->getErrors()).'</font></p>';
	
} // End of SUBMIT conditional.
?>

<h1>Login-Update</h1>
<p>Your browser must allow cookies in order to log in.</p>
<form action="login.php" method="post">
	<fieldset>
	<p><b>Email Address:</b> <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" /></p>
	<p><b>Password:</b> <input type="password" name="pass" size="20" maxlength="20" /></p>
	<p><a href="forgot_password.php">forget password? </a></p>
	<div align="center"><input type="submit" name="submit" value="Login" /></div>
	<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>

<?php // Include the HTML footer.
include (__DIR__.'/includes/footer.php');
?>
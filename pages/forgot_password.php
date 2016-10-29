<?php 

// This page allows a user to reset their password, if forgotten.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Forgot Your Password';
include (__DIR__.'/includes/header.php');

if (isset($request->post['submitted'])) { // Handle the form.
    $userTransactions = $di->newInstance('OceanCrest\UserTransactions');
	if ($userTransactions->resetPassword($request->post['email'])) {
        echo '<h3>Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it by clicking on the "Change Password" link.</h3>';
        include ('./includes/footer.php'); // Include the HTML footer.
        exit();             
    }

    // Failed
    echo '<p><font color="red" size="+1">'.implode('<br />', $userTransactions->getErrors()).'</font></p>'; 

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
include (__DIR__.'/includes/footer.php');
?>
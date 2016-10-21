<?php 

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Change Your Password';
include (__DIR__.'/includes/header.php');

$di->get('middleware');

if (isset($request->post['submitted'])) { // Handle the form.

    $userTransactions = $di->newInstance('OceanCrest\UserTransactions');

    $result = $userTransactions->changePassword(
        $request->session['user_id'], 
        $request->post['password1'],
        $request->post['password2']
    );

    if ($result) {
        echo '<h3>Your password has been changed.</h3>';
        include (__DIR__.'/includes/footer.php'); // Include the HTML footer.
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
include (__DIR__.'/includes/footer.php');
?>
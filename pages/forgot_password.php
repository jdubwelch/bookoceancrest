<?php

// This page allows a user to reset their password, if forgotten.


// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Forgot Your Password';
include (__DIR__.'/includes/header.php');
?>
<div class="page-header">
    <h1>Reset Your Password</h1>
</div>

<?php

if (isset($request->post['submitted'])) { // Handle the form.
    $userTransactions = $di->newInstance('OceanCrest\UserTransactions');
    if ($userTransactions->resetPassword($request->post['email'])) {
        echo '<p>Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it by clicking on the "Change Password" link.</p>';
        include (__DIR__.'/includes/footer.php');
        exit();
    }

    // Failed
    echo '<p><font color="red" size="+1">'.implode('<br />', $userTransactions->getErrors()).'</font></p>';

} // End of the main Submit conditional.
?>

<p>Enter your email address below and your password will be reset.</p>

<form action="forgot_password.php" method="post" class="form-horizontal">
	<input type="hidden" name="submitted" value="TRUE" />

    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email Address</label>
        <div class="col-sm-4">
            <input type="text" name="email" size="20" maxlength="40" class="form-control" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <button type="submit" name="submit" class="btn btn-primary">Reset My Password</button>
        </div>
    </div>
</form>

<?php
include (__DIR__.'/includes/footer.php');
?>
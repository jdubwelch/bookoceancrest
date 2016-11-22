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
<form action="change_password.php" method="post" class="form-horizontal">
	<input type="hidden" name="submitted" value="TRUE" />

    <div class="form-group">
        <label for="newPassword" class="col-sm-2 control-label">New Password</label>
        <div class="col-sm-4">
            <input type="password" name="password1" class="form-control" id="newPassword" value="" />
            <span id="helpBlock" class="help-block">
                Use only letters and numbers. Must be between 4 and 20 characters long.
            </span>
        </div>
    </div>

    <div class="form-group">
        <label for="newPassword2" class="col-sm-2 control-label">Confirm New Password</label>
        <div class="col-sm-4">
            <input type="password" name="password2" class="form-control" id="newPassword2" value="" />
            <span id="helpBlock" class="help-block">
                Use only letters and numbers. Must be between 4 and 20 characters long.
            </span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
        </div>
    </div>

</form>

<?php
include (__DIR__.'/includes/footer.php');
?>
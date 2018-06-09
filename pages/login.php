<?php # Script 13.8 - login.php


// This is the login page for the site.

// Set the page title and include the HTML header.
$page_title = 'Login';
include (__DIR__.'/includes/header.php');

if (isset($request->post['submitted'])) { // Check if the form has been submitted.

    $authTransactions = $di->newInstance('OceanCrest\AuthTransactions');

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

<div class="page-header">
    <h1>Login</h1>
</div>

<form action="login.php" method="post" class="form-horizontal">
    <input type="hidden" name="submitted" value="TRUE" />

    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email Address</label>
        <div class="col-sm-4">
            <input type="text" name="email" size="20" maxlength="40" class="form-control" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" />
            <div class="help-block">
                <!-- <small><i>Your browser must allow cookies in order to log in.</i></small> -->
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-4">
            <input type="password" name="pass" class="form-control" id="password" value="" />
            <span id="helpBlock" class="help-block">
                <a href="forgot_password.php">Forgot password?</a>
            </span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
        </div>
    </div>
</form>

<?php // Include the HTML footer.
include (__DIR__.'/includes/footer.php');
?>
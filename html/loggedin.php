<?php #loggedin.php
# user is redirected here from login.php
session_name ('YourVisitID');
session_start();	// start the session

// if no session value is present, redirec the user
if (!isset($_COOKIE['user_id'])) {
	
	// Start defining the url
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	// Check for a trailing slash
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1);	// chop off the slash
	}
			
	// Add the page
	$url .= '/index.php';
			
	header ("Location: $url");
	exit();	// quit the script
}

$page_title = 'Logged In!';
include ('./includes/header.html');

?>

<h1>You are now logged in</h1>
<a href="logout.php">log out</a>

<?php
include ('./includes/footer.html');
?>



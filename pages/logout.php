<?php # Script 13.9 - logout.php
// This is the logout page for the site.

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Logout';
include (__DIR__.'/includes/header.php');

$di->get('middleware');

unset($request->session);
session_destroy(); // Destroy the session itself.
setcookie (session_name(), '', time()-300, '/', '', 0); // Destroy the cookie.

// Print a customized message.
echo "<h1>You are now logged out.</h1>";

include (__DIR__.'/includes/footer.php');
?>
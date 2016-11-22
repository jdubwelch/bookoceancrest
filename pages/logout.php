<?php # Script 13.9 - logout.php
// This is the logout page for the site.

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> Logout';
include (__DIR__.'/includes/header.php');

$di->get('middleware');

unset($request->session);
session_destroy(); // Destroy the session itself.
setcookie (session_name(), '', time()-300, '/', '', 0); // Destroy the cookie.


// Start defining the URL.
$url = 'http://' . $di->request->server['HTTP_HOST'] . dirname($di->request->server['PHP_SELF']);
// Check for a trailing slash.
if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
    $url = substr ($url, 0, -1); // Chop off the slash.
}
// Add the page.
$url .= '/index.php';

ob_end_clean(); // Delete the buffer.
header("Location: $url");
exit(); // Quit the script.
?>
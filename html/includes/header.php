<?php # Script 13.1 - header.html
// This page begins the HTML header for the site.

// Start output buffering.
ob_start();
// Initialize a session.
session_start();

// Check for a $page_title value.
if (!isset($page_title)) {
	$page_title = 'O C E A N  C R E S T';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $page_title; ?></title>
<style type="text/css" media="screen">@import "./css/first.css";</style>
</head>

<body>
<div id="container">
	<div id="header">
	  <h1>Ocean Crest </h1>
	  <ul>
		<li><a href="index.php">home</a></li>
		<?php
		// SHOW LOGIN LINKS IF USER IS NOT LOGGED IN
		if (isset($_SESSION['name']) AND (substr($_SERVER['PHP_SELF'], -10) != 'logout.php')) {
			echo "<li><a href=\"calendar.php\">calendar</a></li>\n";
			echo "<li><a href=\"logout.php\">logout</a></li>\n";
			echo "<li><a href=\"change_password.php\">change password</a></li>\n";
			
			
		} else {	// they're not logged in
			echo "<li><a href=\"login.php\">login</a></li>\n";
			echo "<li><a href=\"register.php\">register</a></li>\n";
		}
		
		$page = $_SERVER['PHP_SELF'];
		$page = substr($page, 1);
		
		?>
	  </ul>
  </div>
	<div id="content">
	<!-- END OF THE HEADER FILE -->
<?php # Script 13.5 - index.php
// This is the main page for the site.



// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T';
include ('./includes/header.php');

// Welcome the user (by name if they are logged in).
echo '<h1>Welcome';
if (isset($_SESSION['name'])) {
	echo ", {$_SESSION['name']}!";
}
echo '</h1>';
?>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>

<?php // Include the HTML footer file.
include ('./includes/footer.php');
?>
<?php 	#view_users.php

$page_title = "View the Current Users";
include ('./includes/header.html');

// Page header
echo '<h1 id="mainHead">Registered Users</h1>';

require_once ('../cgi-bin/mysql_connect.php');	// connect to the database

// Number of records to show per page
$display = 5;

// Determine how many pages there are
if (isset($_GET['np'])) {	// Already been determined

	$num_pages = $_GET['np'];
	
} else {	// Need to determin

	// Cound the number of records in the db
	$query = "SELECT COUNT(*) FROM ocUsers ORDER BY user_id ASC";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result, MYSQL_NUM);
	$num_records = $row[0];
	
	// Calculate the number of pages
	if ($num_records > $display) {	// more than 1 page
		$num_pages = ceil ($num_records/$display);
	} else {
		$num_pages = 1;
	}

} // End of np IF.

// Determine where in the db to start returning results.
if (isset($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Default column links
$link1 = "{$_SERVER['PHP_SELF']}?sort=lna";
$link2 = "{$_SERVER['PHP_SELF']}?sort=fna";
$link3 = "{$_SERVER['PHP_SELF']}?sort=dra";

// Determin the sorting order
if (isset($_GET['sort'])) {

	// Use existing sorting order
	switch ($_GET['sort']) {
		case 'lna':
			$order_by = 'lastName ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=lnd";
			break;
		case 'lnd':
			$order_by = 'lastName DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=lna";
			break;
		case 'fna':
			$order_by = 'firstName ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=fnd";
			break;
		case 'fnd':
			$order_by = 'firstName DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=fna";
			break;
		case 'dra':
			$order_by = 'registrationDate ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=drd";
			break;
		case 'drd':
			$order_by = 'registrationDate DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=dra";
			break;
		default:
			$order_by = 'registrationDate DESC';
			break;
	}
	
	// $sort will  be appended to the pagination links
	$sort = $_GET['sort'];

} else {	// Use the default sorting order
	$order_by = 'registrationDate ASC';
	$sort = 'rdd';
}

$query = "SELECT name, user_id FROM ocUsers ORDER BY $order_by LIMIT $start, $display";

$result = @mysql_query($query);		// run the query


// table header
echo '<table align="center" cellspacing="0" cellpadding="5">
		<tr>
			<td align="left"><b>Edit</b></td>
			<td align="left"><b>Delete</b></td>
			<td align="left"><b><a href="' . $link1 . '">Name</a></b></td>
		</tr>
		';
	
// Fetch and print all the records
$bg = '#eeeeee';	// set the background color
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
	echo '<tr bgcolor="' . $bg . '">
			<td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
			<td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
			<td align="left">' . $row['name'] . '</td>
		</tr>
		';
}

echo '</table>';
	
mysql_free_result($result); 	// free up the resources

mysql_close();

if ($num_pages > 1) {
	
	echo '<br /><p>';
	
	// Determine what page the script is on
	$current_page = ($start/$display) + 1;
	
	// if it's not the first page, make a previous button
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a next button
	if ($current_page != $num_pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>';

} // End link section

include('./includes/footer.html');
?>
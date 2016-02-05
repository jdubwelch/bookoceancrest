<?php

// Set the page title and include the HTML header.
$page_title = 'O C E A N  C R E S T >> CALENDAR';
include ('./includes/header.php');

// MAKE SURE THEY ARE LOGGED IN
if (isset($_SESSION['name'])) {
	echo "<p>$_SESSION[name]</p>";
} else {
	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	// Add the page.
	$url .= '/index.php';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

// define their name
$name = $_SESSION['name'];
$uid = $_SESSION['user_id'];
$side = $_SESSION['side'];


function readCalendarData ($month, $year) {

	require_once("../../cgi-bin/oc/dbConnection.php");
	
	// CREATE TIMESTAMPS TO SEARCH WITH
	$firstDayTimestamp = mktime (0,0,0, $month, 1, $year);
	$daysInMonth = date ("t", $firstDayTimestamp);
	$lastDayTimestamp = mktime (23, 59, 59, $month, $daysInMonth, $year);
	
	// CREATE SQL
	$sql = "SELECT id, UNIX_TIMESTAMP(dateField) AS timestampField, family ";
	$sql .= "FROM ocCalendar ";
	$sql .= "WHERE UNIX_TIMESTAMP(dateField) >= " . $firstDayTimestamp . " AND UNIX_TIMESTAMP(dateField) <= " . $lastDayTimestamp . " ";
	$sql .= "ORDER BY timestampField ASC";
	
	// READ IN DATA
	$dbResult = mysql_query($sql) or die ("MYSQL Error: " . mysql_error() );
	$numRecords = mysql_num_rows ($dbResult);
	$eventsArray[] = "";
	
	for ($i=0; $i < $numRecords; $i++) {
		$row = mysql_fetch_assoc ($dbResult);
		$day = date ("j", $row['timestampField']);
		$family = $row['family'];
				
		// CHECK DATE ISN'T ALREADY IN $eventsArray
		if(!in_array($day, $eventsArray)) {
			$eventData[$day] = $family;			
		} 
	}
	

	// CLOSE DB CONNECTION
	mysql_close($dbc);
	
	// RETURN eventsArray TO CODE THAT CALLED FUNCTION 
	return $eventData;
}

if ($submit) {
	
	// Subtract one from month for prev and add 1 for next
	if ($submit == "submit") {
		
		$month_now = $_POST['month'];
		$year_now = $_POST['year'];
			
	} elseif ($submit == "Prev") {
		$month_now--;
	} else {
		$month_now++;
	} 
	
	$date = getdate(mktime(0,0,0,$month_now, 1, $year_now));

} elseif (isset($_GET['month'])) {

	$date = getdate(mktime(0,0,0, $_GET['month'], 1, $_GET['year']));
		
} else {

	
	$date = getdate();
	

}


$month = $date["mon"];
$monthName = $date["month"];
$year = $date["year"];

$eventData = readCalendarData($month, $year);
$eventsArray = @array_keys($eventData);

$firstDay = mktime (0,1,0, $month, 1, $year);
$firstDay = date ("w", $firstDay);
$daysInMonth = date("t", mktime(0,0,0,$month,1,$year));

// set the year for the year of the day it is right now
$todaysYear = getdate();
$todaysYear = $todaysYear[year];

echo "
<div id=\"beachcal\">
<form id=\"calendar\" name=\"calendar\" method=\"post\" action=\"calendartest.php\">

<table width=\"600\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
<tr><input type=\"hidden\" name=\"month_now\" value=\"$month\" /><input type=\"hidden\" name=\"year_now\" value=\"$year\" />
    <td colspan=\"7\" class=\"headRow\"><div id=\"welch\">Welch</div>
  <div id=\"schu\">Schumacher</div><input type=\"submit\" name=\"submit\" value=\"Prev\" /><span class=\"theMonth\">$monthName $year</span><input type=\"submit\" name=\"submit\" value=\"Next\" /></td>
</tr>
<tr class=\"headerDays\">
	<td>sun</td>
	<td>mon</td>
	<td>tue</td>
	<td>wed</td>
	<td>thur</td>
	<td>fri</td>
	<td>sat</td>
</tr>\n";

#CALCULATE NUMBER OF ROWS
$totalCells = $firstDay + $daysInMonth;
if ($totalCells < 36) {
	$rowNumber = 5;
} else {
	$rowNumber = 6;
}		
$dayNumber = 1;

for ($currentRow=1; $currentRow <= $rowNumber; $currentRow++) {
	
	if ($currentRow == 1) {
		
		#CREATE FIRST ROW
		echo "<tr>\n";
		for ($currentCell = 0; $currentCell < 7; $currentCell++) {
			
			$weekRow = date("W", mktime(0,0,0, $month, $dayNumber+3, $year));
			$array[] = $weekRow;
						
			// welch's get 2 weeks at the beginning of 2008 to offset
			if (($year % 2 == 0) && $weekRow >= 03) {
				
				if ($weekRow % 2 == 0) {
					$row = "oddrow";
					$sid = 1; // sid = side id 
					$familyweek = "S";
				} else {
					$row = "evenrow";
					$sid = 0;
					$familyweek = "W";
				}
				
				
			}else {
			
				if ($weekRow % 2 == 0) {
					$row = "evenrow";
					$sid = 0; // sid = side id 
					$familyweek = "W";
				} else {
					$row = "oddrow";
					$sid = 1;
					$familyweek = "S";
				}
			
			}
			
			if ($row == "evenrow") {
				$reserved = "#D8FFDC";
			} else {
				$reserved = "#DAE7FF";
			}
			
			// CHECK IF IT'S THE FIRST DAY OF THE MONTH
			if ($currentCell == $firstDay) {
				$day = $dayNumber . "/" . $month . "/" . $year;
				$link = "<a href=\"details.php?day=$day\">$dayNumber</a>";
				$alink = "<a href=\"add_event.php?day=$day\">$dayNumber</a>";
				
				if (@in_array($dayNumber, $eventsArray)) {
					$fam = $eventData[$dayNumber];
					echo "<td bgcolor=\"$reserved\" class=\"$row\"><div id=\"day\">$link</div><div id=\"event\">$fam</div></td>\n";
				} else {
					echo "<td class=\"$row\"><div id=\"day\">$alink</div><div id=\"event\"></div></td>\n";
				}
				$dayNumber++;
			} else {
				
				// IF THE FIRST DAY IS PASSED OUTPUT THE DATE
				if ($dayNumber > 1) { 
					$day = $dayNumber . "/" . $month . "/" . $year;
					$link = "<a href=\"details.php?day=$day\">$dayNumber</a>";
					$alink = "<a href=\"add_event.php?day=$day&sid=$sid\">$dayNumber</a>";
				
					if (@in_array($dayNumber, $eventsArray)) {
						$fam = $eventData[$dayNumber];
						echo "<td bgcolor=\"$reserved\" class=\"$row\"><div id=\"day\">$link</div><div id=\"event\">$fam</div></td>\n";
					} else {
						echo "<td class=\"$row\"><div id=\"day\">$alink</div><div id=\"event\"></div></td>\n";
					}
					$dayNumber++;
				} else {	// FIRST DAY NOT REACHED SO DISPLAY A BLANK CELL
					echo "<td class=\"otherMonth\">&nbsp;</td>\n";
				}
			}
		}
		echo "</tr>\n";
	} else {
	
		#CREATE THE REMAINING ROWS
		echo "<tr>\n";
		for ($currentCell=0; $currentCell < 7; $currentCell++) {
			$dayName = date("l", mktime(0,0,0,$month, $dayNumber, $year));
	
			$weekRow = date("W", mktime(0,0,0, $month, $dayNumber+3, $year));
			
			

		
			// welch's get 2 weeks at the beginning of 2008 to offset
			if (($year % 2 == 0) && $weekRow >= 03) {
				
				if ($weekRow % 2 == 0) {
					$row = "oddrow";
					$sid = 1; // sid = side id 
					$familyweek = "S";
				} else {
					$row = "evenrow";
					$sid = 0;
					$familyweek = "W";
				}
				
				
			}else {
			
				if ($weekRow % 2 == 0) {
					$row = "evenrow";
					$sid = 0; // sid = side id 
					$familyweek = "W";
				} else {
					$row = "oddrow";
					$sid = 1;
					$familyweek = "S";
				}
			
			}
			
			if ($row == "evenrow") {
				$reserved = "#D8FFDC";
			} else {
				$reserved = "#DAE7FF";
			}
			
			$day = $dayNumber . "/" . $month . "/" . $year;
			$link = "<a href=\"details.php?day=$day\">$dayNumber</a>";
			$alink = "<a href=\"add_event.php?day=$day&sid=$sid\">$dayNumber</a>";
			
			// IF THE DAYS IN THE MONTH ARE EXCEEDED DISPLAY A BLANK CELL
			if ($dayNumber > $daysInMonth) {
				echo "<td class=\"otherMonth\">&nbsp;</td>\n";
			} else {
				if (@in_array($dayNumber, $eventsArray)) {
					$fam = $eventData[$dayNumber];
					echo "<td bgcolor=\"$reserved\" class=\"$row\"><div id=\"day\">$link</div><div id=\"event\">$fam</div></td>\n";
				} else {
					echo "<td class=\"$row\"><div id=\"day\">$alink</div><div id=\"event\"></div></td>\n";
				}
				$dayNumber++;
			}
		}
		echo "</tr>\n";
	}
		
}
echo "</table>";
$month_array = array("January" => 1, "February" => 2, "March" => 3, "April" => 4, "May" => 5, "June" => 6, "July" => 7, "August" => 8, "September" => 9, "October" => 10, "November" => 11, "December" => 12);

echo "<select name=\"month\">";

foreach ($month_array as $m => $key) {
	if ($monthName == $m) {
		echo "<option value=\"$key\" selected=\"selected\">$m</option>\n";
	} else {
		echo "<option value=\"$key\">$m</option>\n";
	}
	
}
echo "</select>";

echo "<select name=\"year\">";
for ($i=$year; $i<=$year+2; $i++) {
	echo "<option value=\"$i\">$i</option>\n";
}
echo "</select>
<input type=\"submit\" name=\"submit\" value=\"submit\" />
</form><h2>" . $_COOKIE['lastMonthVisited'] . "</h2>
</div>";





include ('./includes/footer.php');

?>	

		
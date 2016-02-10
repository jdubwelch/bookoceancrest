<?php 
include (__DIR__.'/../views/partials/header.php');

$firstDay = mktime (0,1,0, $month, 1, $year);
$firstDay = date ("w", $firstDay);
$daysInMonth = date("t", mktime(0,0,0,$month,1,$year));

#CALCULATE NUMBER OF ROWS
$totalCells = $firstDay + $daysInMonth;
if ($totalCells < 36) {
    $rowNumber = 5;
} else {
    $rowNumber = 6;
}       
$dayNumber = 1;

$eventsArray = @array_keys($eventData);

echo "<p>{$name}</p>";
echo '
<div id="beachcal">
<form id="calendar" name="calendar" method="post" action="">

<table width="600" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <input type="hidden" name="month_now" value="'.$month.'" />
        <input type="hidden" name="year_now" value="'.$year.'" />
        <td colspan="7" class="headRow">
            <div id="welch">Welch</div>
            <div id="schu">Schumacher</div>
            <input type="submit" name="submit" value="Prev" />
            <span class="theMonth">'.$monthName.' '.$year.'</span>
            <input type="submit" name="submit" value="Next" />
        </td>
    </tr>
    <tr class="headerDays">
        <td>sun</td>
        <td>mon</td>
        <td>tue</td>
        <td>wed</td>
        <td>thur</td>
        <td>fri</td>
        <td>sat</td>
    </tr>
';
for ($currentRow=1; $currentRow <= $rowNumber; $currentRow++) {
    
    if ($currentRow == 1) {
        
        #CREATE FIRST ROW
        echo "<tr>\n";
        for ($currentCell = 0; $currentCell < 7; $currentCell++) {
            
            // Get the number of the week
            $weekRow = date("W", mktime(0,0,0, $month, $dayNumber+3, $year));
                        
            if ($weekRow % 2 == 0) {
                $row = "evenrow";
                $sid = 0; // sid = side id 
                $familyweek = "W";
            } else {
                $row = "oddrow";
                $sid = 1;
                $familyweek = "S";
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
                } else {    // FIRST DAY NOT REACHED SO DISPLAY A BLANK CELL
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
            
            if ($weekRow % 2 == 0) {
                $row = "evenrow";
                $sid = 0; // sid = side id 
                $familyweek = "W";
            } else {
                $row = "oddrow";
                $sid = 1;
                $familyweek = "S";
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


echo "<select name=\"month\">";



$month_array = array("January" => 1, "February" => 2, "March" => 3, "April" => 4, "May" => 5, "June" => 6, "July" => 7, "August" => 8, "September" => 9, "October" => 10, "November" => 11, "December" => 12);

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
</form>
</div>";

include (__DIR__.'/../views/partials/footer.php');
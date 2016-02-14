<?php 
include (__DIR__.'/../views/partials/header.php');

$presenter = new OceanCrest\CalendarPresenter($month, $year);

// What day of the week the month starts on 0-6 (Sunday-Saturday)
$firstDay = date ("w", mktime (0,1,0, $month, 1, $year));

// Total days in the month
$daysInMonth = date("t", mktime(0,0,0,$month,1,$year));

// Determine the number of rows we'll need.
$totalCells = $firstDay + $daysInMonth;
$numberOfRows = ($totalCells < 36) ? 5 : 6;
     


$eventsArray = @array_keys($eventData);

echo "<pre>month: $month\nyear: $year\nfirst day: $firstDay\ndays in month: $daysInMonth\ntotal cells: $totalCells\nRows: $numberOfRows</pre>";

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

$ownershipOriginal = new OceanCrest\WeekOwnershipOriginal;
$ownership = new OceanCrest\WeekOwnershipSwap;

$dayNumber = 1;
for ($currentRow=1; $currentRow <= $numberOfRows; $currentRow++) {
    
    if ($currentRow == 1) {
        
        #CREATE FIRST ROW
        echo "<tr>\n";
        for ($currentCell = 0; $currentCell < 7; $currentCell++) {
            
            // set week ownership
            $familyOriginal = $ownershipOriginal->determine($dayNumber, $month, $year);
            $family = $ownership->determine($dayNumber, $month, $year);
            
            // CHECK IF IT'S THE FIRST DAY OF THE MONTH
            if ($currentCell == $firstDay) {

                if (@in_array($dayNumber, $eventsArray)) {
                    echo $presenter->day($dayNumber, $family, $familyOriginal, $eventData[$dayNumber]);
                } else {
                    echo $presenter->day($dayNumber, $family, $familyOriginal);
                }
                $dayNumber++;
            } else {
                
                // IF THE FIRST DAY IS PASSED OUTPUT THE DATE
                if ($dayNumber > 1) { 
                    if (@in_array($dayNumber, $eventsArray)) {
                        echo $presenter->day($dayNumber, $family, $familyOriginal, $eventData[$dayNumber]);
                    } else {
                        echo $presenter->day($dayNumber, $family, $familyOriginal);
                    }
                    $dayNumber++;
                } else {    // FIRST DAY NOT REACHED SO DISPLAY A BLANK CELL
                    echo $presenter->off_day();
                }
            }
        }
        echo '</tr>'."\n";
    } else {
    
        #CREATE THE REMAINING ROWS
        echo '<tr>'."\n";
        for ($currentCell = 0; $currentCell < 7; $currentCell++) {
            
            // Week Ownership
            $familyOriginal = $ownershipOriginal->determine($dayNumber, $month, $year);
            $family = $ownership->determine($dayNumber, $month, $year);
            
            // IF THE DAYS IN THE MONTH ARE EXCEEDED DISPLAY A BLANK CELL
            if ($dayNumber > $daysInMonth) {
                echo $presenter->off_day();
            } else {
                if (@in_array($dayNumber, $eventsArray)) {
                    echo $presenter->day($dayNumber, $family, $familyOriginal, $eventData[$dayNumber]);
                } else {
                    echo $presenter->day($dayNumber, $family, $familyOriginal);
                }
                $dayNumber++;
            }
        }
        echo '</tr>'."\n";
    }
        
}
echo '</table>';


echo '<select name="month">';



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
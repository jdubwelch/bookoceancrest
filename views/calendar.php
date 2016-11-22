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

// Debugggin stuff
// echo "<pre>month: $month\nyear: $year\nfirst day: $firstDay\ndays in month: $daysInMonth\ntotal cells: $totalCells\nRows: $numberOfRows</pre>";
?>
<div id="beachcal">
    <div id="welch">Welch</div>
    <div id="schu">Schumacher</div>
    <div class="row">
            <div class="calendar-nav col-md-2">
                <a href="?year=<?=$year?>&month=<?=$month-1?>" class="btn btn-primary btn-large btn-block">Last Month</a>
            </div>
            <div class="col-md-8">
                <h1 class="calendar-header"><?=$monthName.' '.$year?></h1>
            </div>
            <div class="calendar-nav col-md-2">
                <a href="?year=<?=$year?>&month=<?=$month+1?>" class="btn btn-primary btn-large btn-block">Next Month</a>
            </div>
        </form>
    </div>

    <table class="calendar">
        <thead>
            <tr class="headerDays">
                <td>sun</td>
                <td>mon</td>
                <td>tue</td>
                <td>wed</td>
                <td>thur</td>
                <td>fri</td>
                <td>sat</td>
            </tr>
        </thead>
        <tbody>

<?php
$ownership = new OceanCrest\WeekOwnershipSwap;

$dayNumber = 1;
for ($currentRow=1; $currentRow <= $numberOfRows; $currentRow++) {

    if ($currentRow == 1) {

        #CREATE FIRST ROW
        echo "<tr>\n";
        for ($currentCell = 0; $currentCell < 7; $currentCell++) {

            // set week ownership
            $family = $ownership->determine($dayNumber, $month, $year);

            // CHECK IF IT'S THE FIRST DAY OF THE MONTH
            if ($currentCell == $firstDay) {

                if (@in_array($dayNumber, $eventsArray)) {
                    echo $presenter->day($dayNumber, $family, $eventData[$dayNumber]);
                } else {
                    echo $presenter->day($dayNumber, $family);
                }
                $dayNumber++;
            } else {

                // IF THE FIRST DAY IS PASSED OUTPUT THE DATE
                if ($dayNumber > 1) {
                    if (@in_array($dayNumber, $eventsArray)) {
                        echo $presenter->day($dayNumber, $family, $eventData[$dayNumber]);
                    } else {
                        echo $presenter->day($dayNumber, $family);
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
            $family = $ownership->determine($dayNumber, $month, $year);

            // IF THE DAYS IN THE MONTH ARE EXCEEDED DISPLAY A BLANK CELL
            if ($dayNumber > $daysInMonth) {
                echo $presenter->off_day();
            } else {
                if (@in_array($dayNumber, $eventsArray)) {
                    echo $presenter->day($dayNumber, $family, $eventData[$dayNumber]);
                } else {
                    echo $presenter->day($dayNumber, $family);
                }
                $dayNumber++;
            }
        }
        echo '</tr>'."\n";
    }
}
?>
        </tbody>
    </table>

    <form name="calendar" method="get" action="" class="form-inline">
        <div class="form-group">
            <label for="month" class="sr-only">Month</label>
            <select name="month" id="month" class="form-control">
<?php
$month_array = array(
    "January"   => 1,
    "February"  => 2,
    "March"     => 3,
    "April"     => 4,
    "May"       => 5,
    "June"      => 6,
    "July"      => 7,
    "August"    => 8,
    "September" => 9,
    "October"   => 10,
    "November"  => 11,
    "December"  => 12
);

foreach ($month_array as $m => $key) {
    $selected = ($monthName == $m) ? 'selected="selected" ' : '';
    echo "<option value=\"$key\" $selected>$m</option>\n";
}
?>
            </select>
        </div>
        <div class="form-group">
            <label for="year" class="sr-only">Year</label>
            <select name="year" id="year" class="form-control">
<?php
for ($i=$year; $i<=$year+2; $i++) {
    echo "<option value=\"$i\">$i</option>\n";
}
?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View Month</button>
    </form>
</div>

<?php
include (__DIR__.'/../views/partials/footer.php');
<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

$ownershipOld = new OceanCrest\WeekOwnershipOld;
$ownership = new OceanCrest\WeekOwnership;

foreach(range(2016, 2025) as $year) {
    $date = Carbon::createFromDate($year, 1, 1);
    $date->setWeekStartsAt(Carbon::FRIDAY);
    $date->setWeekEndsAt(Carbon::THURSDAY);

    echo "---------------------------------------------------------------------------\n";
    echo 'Year | Week # | Week Start | Week End | Family | Family (old) | Holiday'."\n";
    echo "---------------------------------------------------------------------------\n";
    
    display($date);

    $totalWeeks = (date("W", mktime(0,0,0, 12, 31+3, $year)) == 53) ? 53 : 52;

    for ($count = 2; $count <= $totalWeeks; $count++) {
        $date = $date->addWeek();
        display($date);
    }
}

function display($date)
{
    global $ownership, $ownershipOld;
    $week = $date->copy()->addDays(3)->format('W');
    // $week = date("W", mktime(0,0,0, $date->month, $date->day+3, $date->year));
    // echo $date->format('M d, Y | l |')." ";
    
    echo "$date->year | $week | ";
    echo $date->copy()->startOfWeek()->format('m/d').' | '.$date->copy()->endOfWeek()->format('m/d');

    echo ' | '.$ownership->determine($date->day, $date->month, $date->year);

    echo ' | ('.$ownershipOld->determine($date->day, $date->month, $date->year).')';

    if ($date->isSameDay(Carbon::createFromDate($date->year, 1, 1))) {
        echo " | New Years Day -> ".$date->format('m/d l');
    }

    if ($week == $ownership->fourthOfJulyWeek($date->year)) {
        $fourth = Carbon::createFromDate($date->year, 7, 4);
        echo " | July 4th Week -> ".$fourth->format('m/d l');
    }

    if ($week == $ownership->memorialDayWeek($date->year)) {
        echo " | Memorial Day Week -> ".$date->copy()->lastOfMonth(Carbon::MONDAY)->format('m/d l');
    }

    if ($week == $ownership->laborDayWeek($date->year)) {
        echo " | Labor Day Week ->  ".$date->copy()->firstOfMonth(Carbon::MONDAY)->format('m/d l');
    }


    echo "\n";
}

<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require __DIR__ . '/vendor/autoload.php';


$ownershipOld = new OceanCrest\WeekOwnershipOld;
$ownership = new OceanCrest\WeekOwnership;

/*
|--------------------------------------------------------------------------
| Holidays
|--------------------------------------------------------------------------
|
| 
|
*/
$stats = [
    'new years' => [],
    'memorial' => [],
    'july 4th' => [],
    'labor' => []
];

foreach (range(2017, 2026) as $year) {
    echo " YEAR: $year\n";

    $weeks =  (date("W", mktime(0,0,0, 12, 31+3, $year)) == 53) ? 53 : 52;
    echo " TOTAL WEEKS: $weeks\n";

    $newYears = strtotime("first day of january $year");
    $whoOld = $ownershipOld->determine(date('d', $newYears), date('m', $newYears), $year);
    $who = $ownership->determine(date('d', $newYears), date('m', $newYears), $year);
    echo " NEW YEARS\n";
    echo "         day: ".date('m/d l', $newYears)."\n";
    echo "      week #: ".date("W", mktime(0,0,0, 1, date('d', $newYears)+3, $year))."\n";
    echo '   who (old): '.$whoOld."\n";
    echo '         who: '.$who."\n";
    $stats['new years']['old'][$year] = $whoOld;
    $stats['new years']['new'][$year] = $who;

    $memorial = strtotime("last monday of may $year"); 
    $whoOld = $ownershipOld->determine(date('d', $memorial), date('m', $memorial), $year);
    $who = $ownership->determine(date('d', $memorial), date('m', $memorial), $year);
    echo " MEMORIAL DAY\n";
    echo "         day: ".date('m/d l', $memorial)."\n";
    echo "      week #: ".date("W", mktime(0,0,0, date('m', $memorial), date('d', $memorial)+3, $year))."\n";
    echo '   who (old): '.$whoOld."\n";
    echo '         who: '.$who."\n";
    $stats['memorial']['old'][$year] = $whoOld;
    $stats['memorial']['new'][$year] = $who;

    $fourth = strtotime("july 4 $year");
    $whoOld = $ownershipOld->determine(date('d', $fourth), date('m', $fourth), $year);
    $who = $ownership->determine(date('d', $fourth), date('m', $fourth), $year);
    echo " INDEPENDENCE DAY\n";
    echo "         day: ".date('m/d l', $fourth)."\n";
    echo "      week #: ".date("W", mktime(0,0,0, date('m', $fourth), date('d', $fourth)+3, $year))."\n";
    echo '   who (old): '.$whoOld."\n";
    echo '         who: '.$who."\n";
    $stats['july 4th']['old'][$year] = $whoOld;
    $stats['july 4th']['new'][$year] = $who;

    $laborDay = strtotime("first monday of september $year");
    $whoOld = $ownershipOld->determine(date('d', $laborDay), date('m', $laborDay), $year);
    $who = $ownership->determine(date('d', $laborDay), date('m', $laborDay), $year);
    echo " LABOR DAY\n";
    echo "         day: ".date('m/d l', $laborDay)."\n";
    echo "      week #: ".date("W", mktime(0,0,0, date('m', $laborDay), date('d', $laborDay)+3, $year))."\n";
    echo '   who (old): '.$whoOld."\n";
    echo '         who: '.$who."\n";
    $stats['labor']['old'][$year] = $whoOld;
    $stats['labor']['new'][$year] = $who;

    // echo "labor day in $year : ".date('m/d l', strtotime("may $year last monday"))."\n";
    echo "=====================================\n";
}

print_r($stats);

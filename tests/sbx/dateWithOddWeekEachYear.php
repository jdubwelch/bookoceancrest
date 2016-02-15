<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
echo "looking for date with an even week each year\n\n";
foreach (range(2017, 2026) as $year) {
    $date = strtotime("10/22/$year");
    $week = date("W", mktime(0,0,0, date('m', $date), date('d', $date)+3, $year));

    echo date("m-d-y :: ", $date).$week."\n";
}


?>
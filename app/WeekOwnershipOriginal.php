<?php namespace OceanCrest;

class WeekOwnershipOriginal {

    public function determine($day, $month, $year)
    {
        // Week Ownership
        $weekRow = date("W", mktime(0,0,0, $month, $day+3, $year));
        
        if ($weekRow % 2 == 0) {
            $family = "welch";
        } else {
            $family = "schu";
        }

        return $family;
    }

}
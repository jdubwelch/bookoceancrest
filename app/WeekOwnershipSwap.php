<?php namespace OceanCrest;

use Carbon\Carbon;

class WeekOwnershipSwap extends WeekOwnership {

    public function determine($day, $month, $year)
    {
        $standardFamily = parent::determine($day, $month, $year);
        $date = Carbon::createFromDate($year, $month, $day);

        if ($this->previousWeekSwapped($date)) {

            if ($standardFamily == 'welch') {
                return 'schu';
            }
            return 'welch';
        }

        return $standardFamily;
    }

    private function previousWeekSwapped(Carbon $date)
    {
        // we want to check who had and should have add the cabin last week
        $date->subWeek();

        $defaultFamily = $this->standard($date->day, $date->month, $date->year);
        $family = parent::determine($date->day, $date->month, $date->year);

        if ($defaultFamily != $family) {
            return true;
        }

        return false;
    }

    private function standard($day, $month, $year)
    {
        $weekNumber = $this->getWeekNumber($day, $month, $year);

        // Along with alternating weeks each year
        // Schu's get new years on even years
        if ($year % 2 == 0) {

            // Schu's get ood weeks on even years so 
            if ($weekNumber % 2 != 0) {
                return 'schu';
            } else {
                return 'welch';
            }

        // odd year
        } else {
            
            // welch's get odd weeks on odd years
            if ($weekNumber % 2 != 0) {
                return 'welch';
            } else {
                return 'schu';
            }
        }
    }

}
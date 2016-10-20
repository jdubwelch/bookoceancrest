<?php namespace OceanCrest;

/*
|--------------------------------------------------------------------------
| Week Ownership Swap
|--------------------------------------------------------------------------
|
| This class adds the additional functionality of swapping the week after
| a normal week was stolen due to a holiday. This prevents a three-week
| chunk for one side and creates two two-week chunks.
|
*/

use Carbon\Carbon;

class WeekOwnershipSwap extends WeekOwnership {

    /**
     * Determine which side is staying based on a given date.
     * 
     * @param  int $day   
     * @param  int $month 
     * @param  int $year  
     * @return string
     */
    public function determine($day, $month, $year)
    {
        // With the default standard rules, who will get the cabin
        $standardFamily = parent::determine($day, $month, $year);
        
        $date = Carbon::createFromDate($year, $month, $day);
        if ($this->previousWeekSwapped($date)) {

            // swap sides
            return ($standardFamily != 'welch') ? 'welch' : 'schu';
        }

        return $standardFamily;
    }

    /**
     * Determine if the previous week was swapped due to a holiday.
     * @param  Carbon $date 
     * @return boolean       
     */
    private function previousWeekSwapped(Carbon $date)
    {
        $thisYear = $date->year;

        // we want to check who had and should have add the cabin last week
        $lastWeek = $date->subWeek();

        $lastWeekNumber = $this->getWeekNumber($lastWeek->day, $lastWeek->month, $lastWeek->year);

        // If subtracted to week 1, and the year's are different, no swap occurs.
        // An example of this is the beginning of 2017.
        if ($lastWeekNumber == 1 && ($lastWeek->year != $thisYear)) {
            return false;
        }

        // Normal rules (even/odd) which side would get the cabin
        $defaultFamily = $this->evenOddAssignment(
            $lastWeekNumber,
            $lastWeek->year
        );
        
        // With the holiday rules, who was the previous week given too.
        $family = parent::determine($lastWeek->day, $lastWeek->month, $lastWeek->year);

        // If they are different then they were swapped
        return ($defaultFamily != $family);
    }

    

}
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
            if ($standardFamily != 'welch') {
                return 'welch';
            }
            
            return 'schu';
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
        // we want to check who had and should have add the cabin last week
        $date->subWeek();

        // Normal rules (even/odd) which side would get the cabin
        $defaultFamily = $this->evenOddAssignment(
            $this->getWeekNumber($date->day, $date->month, $date->year),
            $date->year
        );
        
        // With the holiday rules, who was the week given too
        $family = parent::determine($date->day, $date->month, $date->year);

        // If they are different then they were swapped
        if ($defaultFamily != $family) {
            return true;
        }

        return false;
    }

    

}
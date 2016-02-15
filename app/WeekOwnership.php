<?php namespace OceanCrest;

/*
|--------------------------------------------------------------------------
| Week Ownership
|--------------------------------------------------------------------------
|
| This class is responsible for determining the ownership for the cabin
| for given dates. The rules are as follows:
| * Welch's get even weeks during even years. 
| * Schu's get odd weeks during event years.
| * Each each odd/even weeks swap sides. 
| ** This means usually, side that ends the year, starts the year.
| ** So a side gets two weeks there, but that will rotate each year.
| * The side that had New Year's gets Memorial Day Weekend.
| * The side that didn't get New Year's gets Fourth of July.
| * The side that didn't get New Year's gets Labor Day Weekend.
|
*/

class WeekOwnership {

    /**
     * Given a date, get what week in the year it's in.
     * 
     * @param  int $day   
     * @param  int $month 
     * @param  int $year  
     * @return int        
     */
    public function getWeekNumber($day, $month, $year)
    {
        return date("W", mktime(0,0,0, $month, $day+3, $year));
    }

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
        // Week number drives everything
        $weekNumber = $this->getWeekNumber($day, $month, $year);
       
        /*
        |--------------------------------------------------------------------------
        | Fourth of July
        |--------------------------------------------------------------------------
        |
        | Whoever didn't have New Year's gets Fouth of July.
        | Welch's get Fourth of July on even years.
        | Schu's  get Fourth of July on odd years.
        |
        */
        if ($this->isFourthOfJulyWeek($weekNumber, $year)) {
            if ($this->even($year)) {
                return 'welch';
            } else {
                return 'schu';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Memorial Day Weekend - Last Monday in May
        |--------------------------------------------------------------------------
        |
        | Whoever had New Year's, gets Memorial Day Weekend.
        | Welch's get New Year's during odd years.
        | Schu's  get New Year's during even years.
        |
        */
        if ($this->isMemorialDayWeek($weekNumber, $year)) {

            if ($this->odd($year)) {
                return 'welch';
            } 
            
            return 'schu';
        }

        /*
        |--------------------------------------------------------------------------
        | Labor Day Weekend - First Monday in September
        |--------------------------------------------------------------------------
        |
        | Whoever didn't have New Year's gets Labor Day Weekend.
        | Welch's get New Year's during odd years.
        | Schu's  get New Year's during even years.
        |
        */
        if ($this->isLaborDayWeek($weekNumber, $year)) {

            if ($this->even($year)) {
                return 'welch';
            } 
            
            return 'schu';
        }

        /**
         * The last week of December could be the first week of the year. 
         * If that is the case the even/odd year stuff needs to be for the year of that week.
         * If we detect week 1 in December, then increase the year (even/odd) for the year of the week.
         */
        if ($weekNumber == 1 && $month == 12) {
            $year++;
        }

        return $this->evenOddAssignment($weekNumber, $year);
    }

    /**
     * Alternate weeks each year by assigning weeks to sides based on the 
     * even/odd value of the current year.
     * 
     * @param  int $weekNumber 
     * @param  int $year       
     * @return string
     */
    public function evenOddAssignment($weekNumber, $year)
    {
        // odd years, welch's get odd weeks
        if ($this->odd($year) && $this->odd($weekNumber)) {
            return 'welch';
        }

        // even year, welch's get even weeks
        if ($this->even($year) && $this->even($weekNumber)) {
            return 'welch';
        }

        // odd  year, schu's get even weeks
        // even year, schu's get odd weeks
        return 'schu';
    }

    /**
     * Whether a number is even or not.
     * 
     * @param  int $number
     * @return boolean
     */
    private function even($number)
    {
        return ($number % 2 == 0);
    }

    /**
     * Whether a number is odd or not.
     * 
     * @param  int $number
     * @return boolean
     */
    private function odd($number)
    {
        return ! $this->even($number);
    }

    /**
     * Is the provided week a Fourth of July Week
     * 
     * @param  int  $weekNumber 
     * @param  int  $year       
     * @return boolean             [description]
     */
    public function isFourthOfJulyWeek($weekNumber, $year)
    {
        return ($weekNumber == $this->fourthOfJulyWeek($year));
    }

    /**
     * Week number for the Fouth of July.
     * 
     * @param  int $year 
     * @return int
     */
    public function fourthOfJulyWeek($year)
    {
        $fourth = strtotime("july 4 $year");
        return date("W", mktime(0,0,0, date('m', $fourth), date('d', $fourth)+3, $year));
    }

    /**
     * Is the provided week a Memorial Day Week
     * 
     * @param  int  $weekNumber 
     * @param  int  $year       
     * @return boolean
     */
    public function isMemorialDayWeek($weekNumber, $year)
    {
        return ($weekNumber == $this->memorialDayWeek($year));
    }

    /**
     * Week number Memorial Day is in.
     * 
     * @param  int $year 
     * @return int       
     */
    public function memorialDayWeek($year)
    {
        return $this->getWeekNumberFromTimestamp(strtotime("last monday of may $year"));
    }

    /**
     * Is the provided week a Labor Day Week
     * 
     * @param  int  $weekNumber 
     * @param  int  $year       
     * @return boolean
     */
    public function isLaborDayWeek($weekNumber, $year)
    {
        return ($weekNumber == $this->laborDayWeek($year));
    }

    /**
     * Week number Labor Day is in.
     * 
     * @param  int $year 
     * @return int       
     */
    public function laborDayWeek($year)
    {
        return $this->getWeekNumberFromTimestamp(strtotime("first monday of september $year"));
    }

    /**
     * Week number for a given timestamp.
     * @param  int $timestamp 
     * @return int            
     */
    private function getWeekNumberFromTimestamp($timestamp)
    {
        return date(
            "W", 
            mktime(0,0,0, date('m', $timestamp), date('d', $timestamp)+3, date('Y', $timestamp))
        );
    }



}
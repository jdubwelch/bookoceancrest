<?php namespace OceanCrest;

class WeekOwnership {

    protected $newYearsEven = 'schu';
    protected $evenYearEvenWeek = 'welch';
    protected $oddYearOddWeek = 'welch';
    protected $oddYearEvenWeek = 'schu';

    public function determine($day, $month, $year)
    {
        // Week Ownership
        $weekNumber = date("W", mktime(0,0,0, $month, $day+3, $year));
       
        // Welch's get fourth of july on even years
        $fourthOfJulyWeek = $this->fourthOfJulyWeek($year);
        if ($weekNumber == $fourthOfJulyWeek) {
            if ($this->evenYear($year)) {
                return 'welch';
            } else {
                return 'schu';
            }
        }

        // memorial day goes to the same side that had new years
        // new years for welch == odd year
        // new years for schu  == even year
        $memorialDayWeek = $this->memorialDayWeek($year);
        if ($weekNumber == $memorialDayWeek) {
            if ($this->oddYear($year)) {
                return 'welch';
            } else {
                return 'schu';
            }
        }

        // labor day goes to the opposite side that had new years
        // new years for welch == odd year
        // new years for schu  == even year 
        $laborDayWeek = $this->laborDayWeek($year);
        if ($weekNumber == $laborDayWeek) {
            if ($this->evenYear($year)) {
                return 'welch';
            } else {
                return 'schu';
            }
        }

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

    private function evenYear($year)
    {
        return ($year % 2 == 0);
    }

    private function oddYear($year)
    {
        return ! $this->evenYear($year);
    }

    public function fourthOfJulyWeek($year)
    {
        $fourth = strtotime("july 4 $year");
        return date("W", mktime(0,0,0, date('m', $fourth), date('d', $fourth)+3, $year));
    }

    public function memorialDayWeek($year)
    {
        return $this->getWeekNumber(strtotime("last monday of may $year"));
    }

    public function laborDayWeek($year)
    {
        return $this->getWeekNumber(strtotime("first monday of september $year"));
    }

    private function getWeekNumber($timestamp)
    {
        return date("W", mktime(0,0,0, date('m', $timestamp), date('d', $timestamp)+3, date('Y', $timestamp)));
    }



}
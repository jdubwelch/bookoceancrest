<?php namespace OceanCrest;

class CalendarPresenter {

    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function make_date($day)
    {
        return $day . "/" . $this->month . "/" . $this->year;
    }

    public function link_to_event($day)
    {
        return '<a href="details.php?day='.$this->make_date($day).'">'.$day.'</a>';
    }

    public function link_to_add_event($day)
    {
        return '<a href="add_event.php?day='.$this->make_date($day).'">'.$day.'</a>';
    }
    






}
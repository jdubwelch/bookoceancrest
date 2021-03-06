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
        return $this->month . "/" . $day . "/" . $this->year;
    }

    public function link_to_event($day)
    {
        return '<a href="details.php?day='.$this->make_date($day).'">'.$day.'</a>';
    }

    public function link_to_add_event($day)
    {
        return '<a href="add_event.php?day='.$this->make_date($day).'">'.$day.'</a>';
    }

    public function day($day, $family, $guest = false)
    {
        $classes = [
            // day of the week (tuesday, wednesday, etc)
            strtolower(date("l", mktime(0,0,0,$this->month, $day, $this->year))),
            $family.'_week'
        ];

        if ($guest) {
            $link = $this->link_to_event($day);
            $classes[] = 'reserved';
        } else {
            $link = $this->link_to_add_event($day);
        }

        return '<td class="'.implode(' ', $classes).'"><div class="day">'.$link.'</div><div class="event">'.$guest.'</div></td>';
    }

    public function off_day()
    {
        return '<td class="otherMonth">&nbsp;</td>';
    }

}
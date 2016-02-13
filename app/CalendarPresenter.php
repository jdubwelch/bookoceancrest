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

    public function day($day, $family, $guest = false)
    {
        $classes = [$family.'_week'];

        if ($guest) {
            $link = $this->link_to_event($day);
            $classes[] = 'reserved';
        } else {
            $link = $this->link_to_add_event($day);
        }

        $dayName = date("l", mktime(0,0,0,$this->month, $day, $this->year));

        return '<td class="'.implode(' ', $classes).'"><div class="day '.$dayName.'">'.$link.'</div><div id="event">'.$guest.'</div></td>';
    }

    public function off_day()
    {
        return '<td class="otherMonth">&nbsp;</td>';
    }
    






}
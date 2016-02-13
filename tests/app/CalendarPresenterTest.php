<?php

use OceanCrest\CalendarPresenter;

class CalendarPresenterTest extends \PHPUnit_Framework_TestCase
{
    protected $presenter;

    function setUp()
    {
        $this->presenter = new CalendarPresenter('2', '2016');
    }

    /**
     * @test
     */
    function it_makes_a_link_to_an_event_page()
    {
        $link = $this->presenter->link_to_event(4);
        $expected = '<a href="details.php?day=4/2/2016">4</a>';

        $this->assertSame($expected, $link);
    }

    /**
     * @test
     */
    function it_makes_a_link_to_the_create_event_page()
    {
        $expected = '<a href="add_event.php?day=4/2/2016">4</a>';
        $link = $this->presenter->link_to_add_event(4);

        $this->assertSame($expected, $link);
    }

    /**
     * @test
     */
    function it_creates_the_markup_for_a_cell_with_an_event()
    {
        $link = $this->presenter->link_to_event(4);
        $expected = '<td class="thursday welch_week reserved"><div class="day">'.$link.'</div><div id="event">Jason & Deena</div></td>';

        $html = $this->presenter->day(4, 'welch', 'Jason & Deena');

        $this->assertSame($expected, $html);
    }

    /**
     * @test
     */
    function it_creates_the_markup_for_a_cell_with_no_event()
    {
        $link = $this->presenter->link_to_add_event(5);
        $expected = '<td class="friday welch_week"><div class="day">'.$link.'</div><div id="event"></div></td>';

        $html = $this->presenter->day(5, 'welch');

        $this->assertSame($expected, $html);
    }

    /**
     * @test
     */
    function it_creates_days_not_in_the_current_month()
    {
        $expected = '<td class="otherMonth">&nbsp;</td>';

        $html = $this->presenter->off_day();

        $this->assertSame($expected, $html);
    }
}
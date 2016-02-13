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
}
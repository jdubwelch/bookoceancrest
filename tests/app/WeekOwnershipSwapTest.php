<?php

use OceanCrest\WeekOwnershipSwap;

class WeekOwnershipSwapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_swaps_weeks_if_a_holiday_was_stolen_based_on_the_rules()
    {
        // 05/27/2006
        // This is originally a welch week
        // but because it's a even year, the schu's get new years and memorial day
        // this then creates 3 straight week for them. 
        // the week after needs to go to the welches
        $ownership = new WeekOwnershipSwap;

        // confirm the schu's get memorial day week
        $who = $ownership->determine(27, 5, 2016);
        $this->assertSame('schu', $who);

        // the next week should be swapped to the other family
        $nextWho = $ownership->determine(3, 6, 2016);
        $this->assertSame('welch', $nextWho);
    }

    /** @test */
    function jan_6th_2017_should_be_a_schu_day()
    {
        $ownership = new WeekOwnershipSwap;
        $this->assertSame('schu', $ownership->determine(6, 1, 2017));
    }

}

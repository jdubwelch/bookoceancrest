<?php

use OceanCrest\WeekOwnership;

class WeekOwnershipTest extends \PHPUnit_Framework_TestCase
{
    /*
    |--------------------------------------------------------------------------
    | Ownership Rules
    |--------------------------------------------------------------------------
    |
    | Each year there are 4 holidays that we want 
    |
    */
    
    /**
     * @test
     */
    function it_assigns_schu_to_odd_weeks_in_odd_years()
    {
        $expected = [
            2017 => 'schu',
            2019 => 'schu',
            2021 => 'schu',
            2023 => 'schu',
            2025 => 'schu',
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(array_keys($expected) as $year) {
            $date = dayInOddWeekEachYear($year);
            $actual[$year] = $ownership->determine($date->day, $date->month, $date->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_welch_to_even_weeks_in_odd_years()
    {
        // Odd years
        $expected = [
            2017 => 'welch',
            2019 => 'welch',
            2021 => 'welch',
            2023 => 'welch',
            2025 => 'welch',
        ];

        $ownership = new WeekOwnership;

        // even weeks
        $actual = [];
        foreach(array_keys($expected) as $year) {
            $date = dayInEvenWeekEachYear($year);
            $actual[$year] = $ownership->determine($date->day, $date->month, $date->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_schu_to_even_weeks_in_even_years()
    {
        // even years
        $expected = [
            2018 => 'schu',
            2020 => 'schu',
            2022 => 'schu',
            2024 => 'schu',
            2026 => 'schu'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(array_keys($expected) as $year) {
            $date = dayInEvenWeekEachYear($year);
            $actual[$year] = $ownership->determine($date->day, $date->month, $date->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_welch_to_odd_weeks_in_even_years()
    {
        // even years
        $expected = [
            2018 => 'welch',
            2020 => 'welch',
            2022 => 'welch',
            2024 => 'welch',
            2026 => 'welch'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(array_keys($expected) as $year) {
            $date = dayInOddWeekEachYear($year);
            $actual[$year] = $ownership->determine($date->day, $date->month, $date->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_alternates_new_years_each_year()
    {
        $expected = [
            2017 => 'schu',
            2018 => 'welch',
            2019 => 'schu',
            2020 => 'welch',
            2021 => 'schu',
            2022 => 'welch',
            2023 => 'schu',
            2024 => 'welch',
            2025 => 'schu',
            2026 => 'welch'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(range(2017, 2026) as $year) {
            $newYears = newYears($year);
            $actual[$year] = $ownership->determine($newYears->day, $newYears->month, $newYears->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_independence_day_to_the_opposite_of_who_had_new_years()
    {
        $expected = [
            2017 => 'welch',
            2018 => 'schu',
            2019 => 'welch',
            2020 => 'schu',
            2021 => 'welch',
            2022 => 'schu',
            2023 => 'welch',
            2024 => 'schu',
            2025 => 'welch',
            2026 => 'schu'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(range(2017, 2026) as $year) {
            $holiday = julyFourth($year);
            $actual[$year] = $ownership->determine($holiday->day, $holiday->month, $holiday->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_memorial_day_to_the_same_side_that_had_new_years()
    {
        $expected = [
            2017 => 'schu',
            2018 => 'welch',
            2019 => 'schu',
            2020 => 'welch',
            2021 => 'schu',
            2022 => 'welch',
            2023 => 'schu',
            2024 => 'welch',
            2025 => 'schu',
            2026 => 'welch'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(range(2017, 2026) as $year) {
            $holiday = memorial($year);
            $actual[$year] = $ownership->determine($holiday->day, $holiday->month, $holiday->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_assigns_labor_day_to_the_opposite_side_that_had_new_years()
    {
        $expected = [
            2017 => 'welch',
            2018 => 'schu',
            2019 => 'welch',
            2020 => 'schu',
            2021 => 'welch',
            2022 => 'schu',
            2023 => 'welch',
            2024 => 'schu',
            2025 => 'welch',
            2026 => 'schu'
        ];

        $ownership = new WeekOwnership;

        $actual = [];
        foreach(range(2017, 2026) as $year) {
            $holiday = labor($year);
            $actual[$year] = $ownership->determine($holiday->day, $holiday->month, $holiday->year);
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    function it_respects_the_week_numbers_and_the_alternating_rules_at_the_end_of_the_year()
    {
        $ownership = new WeekOwnership;

        // according to the calendar-list,
        // wk1 in 2017 starts 12/30
        // schu get odd weeks in odd years, so it should be welch
        $side = $ownership->determine(31, 12, 2016);
        $this->assertSame('schu', $side);

        // A year when the last day of the week is 12/30,
        // and that week belongs to the welch's. The next
        // week starts on 12/31 and that year is even so 
        // the welch's get that. They should have two weeks there then.
        $side = $ownership->determine(30, 12, 2021);
        $this->assertSame('welch', $side);
        $side = $ownership->determine(31, 12, 2021);
        $this->assertSame('welch', $side);


    }

}


function newYears($year)
{
    return makeHoliday(strtotime("first day of january $year"));
}

function julyFourth($year)
{
    return makeHoliday(strtotime("july 4 $year"));
}

function memorial($year)
{
    return makeHoliday(strtotime("last monday of may $year"));
}

function labor($year)
{
    return makeHoliday(strtotime("first monday of september $year"));
}

function dayInEvenWeekEachYear($year)
{
    return makeHoliday(strtotime("3/19/$year"));
}

function dayInOddWeekEachYear($year)
{
    return makeHoliday(strtotime("10/22/$year"));
}

function makeHoliday($timestamp)
{
    $holiday = new \stdClass;
    $holiday->day = date('d', $timestamp);
    $holiday->month = date('m', $timestamp);
    $holiday->year = date('Y', $timestamp);
    return $holiday;
}
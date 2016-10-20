<?php

use OceanCrest\EventGateway;
use OceanCrest\UserGateway;

class EventGatewayTest extends GatewayTester
{
    protected $gateway;

    function setUp()
    {
        parent::setUp();
        $this->gateway = new EventGateway($this->db);
    }

    /**
     * @test
     */
    function it_gathers_the_events_for_a_whole_month()
    {
        $user = $this->makeUser();
 
        $event_id = $this->gateway->reserve(
            $user->side, 
            '02/05/2016', 
            3
        );

        $user2 = $this->makeUser();
        $event_id = $this->gateway->reserve(
            $user2->side, 
            '02/12/2016', 
            2
        );

        $events = $this->gateway->monthlyEvents('02', '2016');

        $this->assertCount(5, $events);

    }

    /**
     * @test
     */
    function it_stores_the_date_for_stay()
    {
        $user = $this->makeUser();

        $event_id = $this->gateway->reserve(
            $user->side, 
            '02/01/2016', 
            1
        );

        $this->assertGreaterThan(0, $event_id);
        $events = $this->gateway->monthlyEvents('02', '2016');
        $this->assertCount(1, $events);
    }

    /**
     * @test
     */
    function it_cancels_the_reservation_for_a_day()
    {
        $user = $this->makeUser();

        $event_id = $this->gateway->reserve(
            $user->side, 
            '03/02/2016', 
            1
        );

        $result = $this->gateway->cancel($event_id);
        $events = $this->gateway->monthlyEvents('03', '2016');

        $this->assertTrue($result);
        $this->assertCount(0, $events);
    }

    /**
     * @test
     */
    function it_returns_the_details_about_an_event()
    {
        $user = $this->makeUser();

        $day = '2/4/2016';

        $event_id = $this->gateway->reserve(
            $user->side, 
            // DD/MM/YYYY
            $day, 
            1
        );

        $details = $this->gateway->details('2/4/2016');

        $this->assertEquals($user->side, $details[0]['family']);
    }
}
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
            // DD/MM/YYYY
            '05/02/2016', 
            3
        );

        $user2 = $this->makeUser();
        $event_id = $this->gateway->reserve(
            $user2->side, 
            // DD/MM/YYYY
            '12/02/2016', 
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
            // DD/MM/YYYY
            '01/02/2016', 
            1
        );

        $this->assertGreaterThan(0, $event_id);
    }

    /**
     * @test
     */
    function it_cancels_the_reservation_for_a_day()
    {
        $user = $this->makeUser();

        $event_id = $this->gateway->reserve(
            $user->side, 
            // DD/MM/YYYY
            '02/03/2016', 
            1
        );

        $result = $this->gateway->cancel($event_id);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    function it_returns_the_details_about_an_event()
    {
        $user = $this->makeUser();

        $day = '4/2/2016';

        $event_id = $this->gateway->reserve(
            $user->side, 
            // DD/MM/YYYY
            $day, 
            1
        );

        $details = $this->gateway->details($day);

        $this->assertEquals($user->side, $details[0]['family']);
    }
}
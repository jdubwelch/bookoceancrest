<?php 

use OceanCrest\UserGateway;
use OceanCrest\UserTransactions;
use \Mockery as m;

class EventTransactionsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_is_true()
    {
        $this->assertTrue(true);
    }

}
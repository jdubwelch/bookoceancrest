<?php 

use OceanCrest\UserGateway;
use OceanCrest\UserTransactions;
use \Mockery as m;

class UserTransactionsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_activates_a_user_and_sends_a_notification()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('activate')->with(22)->andReturn(true);
        $gateway->shouldReceive('email')->with(22)->andReturn('some@email.com');

        $transaction = new UserTransactions($gateway);

        $result = $transaction->activate(22);

        $this->assertTrue($result);
        $this->assertEmpty($transaction->getErrors());
    }

    /**
     * @test
     */
    function it_fails_gracefully_if_activation_fails()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('activate')->with(22)->andReturn(false);
        $gateway->shouldNotReceive('email');

        $transaction = new UserTransactions($gateway);

        $result = $transaction->activate(22);

        $this->assertFalse($result);
        $this->assertNotEmpty($transaction->getErrors());
    }

}
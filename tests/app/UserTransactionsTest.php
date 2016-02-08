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

    /**
     * @test
     */
    function it_changes_the_password_for_a_user()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('updatePassword')->with(99, 'newPassword')->andReturn(1);

        $transaction = new UserTransactions($gateway);
        $result = $transaction->changePassword(99, 'newPassword', 'newPassword');
        
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    function it_wont_change_the_password_if_the_confirmation_does_not_match()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldNotReceive('updatePassword');

        $transaction = new UserTransactions($gateway);
        $result = $transaction->changePassword(99, 'new_password', 'new_password2');
        
        $this->assertFalse($result);
        $this->assertNotEmpty($transaction->getErrors());
    }

    /**
     * @test
     */
    function it_does_not_allow_invalid_passwords()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldNotReceive('updatePassword');

        $transaction = new UserTransactions($gateway);

        /**
         * Not sure why we don't allow special characters, but it originally didn't
         */
        $result = $transaction->changePassword(99, '!foo@', '!foo@');
        
        $this->assertFalse($result);
        $this->assertNotEmpty($transaction->getErrors());
    }

}
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

    /**
     * @test
     */
    function it_when_a_user_forgets_their_password_it_reset_and_they_are_notified()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('getUserByEmail')->with('forgot@mypassword.com')->andReturn(5)->once();
        $gateway->shouldReceive('updatePassword')->with(5, anything())->andReturn(true)->once();

        $transaction = new UserTransactions($gateway);
        $result = $transaction->resetPassword('forgot@mypassword.com');

        $this->assertTrue($result);
        $this->assertCount(0, $transaction->getErrors());
    }

    /**
     * @test
     */
    function it_when_a_user_forgets_their_password_it_fails_if_the_email_is_not_attached_to_a_user()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('getUserByEmail')->with('invalid@email.com')->once()->andReturn(false);
        $gateway->shouldNotReceive('updatePassword');

        $transaction = new UserTransactions($gateway);
        $result = $transaction->resetPassword('invalid@email.com');

        $this->assertFalse($result);
        $this->assertGreaterThan(0, $transaction->getErrors());
    }

    /**
     * @test
     */
    function it_registers_a_user()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldReceive('uniqueEmail')->with('bob@email.com')->once()->andReturn(true);
        $gateway->shouldReceive('create')->with('Bob & Sally', 'bob@email.com', 'Welch', 'pword')->once()->andReturn(99);
        $transactions = new UserTransactions($gateway);

        $result = $transactions->register([
            'name' => 'Bob & Sally',
            'side' => 'Welch',
            'email' => 'bob@email.com',
            'password' => 'pword',
            'password_confirm' => 'pword'
        ]);

        $this->assertTrue($result);
        $this->assertCount(0, $transactions->getErrors());
    }

    /**
     * @test
     */
    function it_fails_when_bad_registration_data_is_provided()
    {
        $gateway = m::mock('OceanCrest\UserGateway');
        $gateway->shouldNotReceive('uniqueEmail');
        $gateway->shouldNotReceive('create');
        $transactions = new UserTransactions($gateway);

        $result = $transactions->register([
            'name' => 'Bob & Sally',
            'side' => '0',
            'email' => 'fake',
            'password' => 'pword',
            'password_confirm' => 'pword2'
        ]);

        $this->assertFalse($result);
        $this->assertCount(3, $transactions->getErrors());
    }

}
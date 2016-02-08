<?php 

use Mlaphp\Request;
use OceanCrest\AuthGateway;
use OceanCrest\AuthTransactions;
use OceanCrest\User;
use \Mockery as m;


class AuthTransactionsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_is_logs_in_a_user_if_they_are_authorized()
    {
        $credentials = [
            'email' => 'foo@bar.com',
            'password' => '1234'
        ];
        $gateway = m::mock('OceanCrest\AuthGateway');
        $user = new User(1, 'Jason & Deena', 'Welch');
        $gateway->shouldReceive('attempt')->with($credentials)->once()->andReturn($user);

        $request = new Request($GLOBALS);

        // session started
        @session_start();

        $transactions = new AuthTransactions($gateway, $request);
        $authorized = $transactions->attempt($credentials);

        $this->assertTrue($authorized);
        $this->assertSame(1, $request->session['user_id']);
        $this->assertSame('Jason & Deena', $request->session['name']);
        $this->assertSame('Welch', $request->session['side']);
    }

}
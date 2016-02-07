<?php

use OceanCrest\AuthGateway;
use OceanCrest\UserGateway;

class AuthGatewayTest extends GatewayTester
{
    protected $gateway;

    function setUp()
    {
        parent::setUp();
        $this->gateway = new AuthGateway($this->db);
    }

    /**
     * @test
     */
    function it_returns_the_user_if_they_are_authorized()
    {
        $userGateway = new UserGateway($this->db);

        $email = $this->faker->email;
        $password = $this->faker->word;

        $user_id = $userGateway->create(
            $this->faker->firstName,
            $email, 
            $this->db->escape_data($this->faker->lastName),
            $password
        );

        $userGateway->activate($user_id);

        $user = $this->gateway->attempt([
            'email' => $email,
            'password' => $password
        ]);

        $this->assertEquals($user_id, $user->id);
    }

    /**
     * @test
     */
    function it_returns_false_if_user_does_not_exist()
    {
        $user = $this->gateway->attempt([
            'email' => 'foo',
            'password' => 'bar'
        ]);
        $this->assertFalse($user);
    }

    /**
     * @test
     */
    function it_returns_false_if_the_user_is_not_active()
    {
        $userGateway = new UserGateway($this->db);

        $email = $this->faker->email;
        $password = $this->faker->word;

        $user_id = $userGateway->create(
            $this->faker->firstName,
            $email, 
            $this->db->escape_data($this->faker->lastName),
            $password
        );

        $user = $this->gateway->attempt([
            'email' => $email,
            'password' => $password
        ]);

        $this->assertFalse($user);
    }
}
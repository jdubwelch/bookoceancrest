<?php

use OceanCrest\DB;
use OceanCrest\UserGateway;

class UserGatewayTest extends GatewayTester
{
    protected $gateway;

    function setUp()
    {
        parent::setUp();
        $this->gateway = new UserGateway($this->db);
    }
    
    /**
     * @test
     */
    function it_activates_a_user()
    {
        $user_id = $this->gateway->create(
            $this->faker->firstName,
            $this->faker->email, 
            $this->db->escape_data($this->faker->lastName),
            $this->faker->word
        );

        $result = $this->gateway->activate($user_id);

        $this->assertEquals(1, $result);
    }

    /**
     * @test
     */
    function it_gathers_the_email_for_a_user()
    {
        $email = $this->faker->email;

        $user_id = $this->gateway->create(
            $this->faker->firstName,
            $email, 
            $this->db->escape_data($this->faker->lastName),
            $this->faker->word
        );

        $gathered_email = $this->gateway->email($user_id);

        $this->assertEquals($email, $gathered_email);
    }

    /**
     * @test
     */
    function it_gets_a_user_by_email_address()
    {
        $email = $this->faker->email;

        $user_id = $this->gateway->create(
            $this->faker->firstName,
            $email, 
            $this->db->escape_data($this->faker->lastName),
            $this->faker->word
        );

        $gathered_user_id = $this->gateway->getUserByEmail($email);

        $this->assertEquals($user_id, $gathered_user_id);
    }

    /**
     * @test
     */
    function it_updates_a_users_password()
    {
        $user_id = $this->gateway->create(
            $this->faker->firstName,
            $this->faker->email, 
            $this->db->escape_data($this->faker->lastName),
            $this->faker->word
        );

        $result = $this->gateway->updatePassword($user_id, 'new_password');
        $this->assertEquals(1, $result);
    }


    /**
     * @test
     */
    function it_checks_if_an_email_is_unique()
    {
        /**
         * Unique Email, should return true
         */
        $email = $this->faker->email;
        $unique = $this->gateway->uniqueEmail($email);
        $this->assertTrue($unique);

        /**
         * Non-unique email will return false
         */
        $email = $this->faker->email;
        $new_user_id = $this->gateway->create(
            $this->faker->firstName,
            $email, 
            $this->faker->lastName,
            $this->faker->word
        );

        $non_unique = $this->gateway->uniqueEmail($email);

        $this->assertFalse($non_unique);
    }

    /**
     * @test
     */
    function it_creates_a_new_user()
    {
        $new_user_id = $this->gateway->create(
            $this->faker->firstName,
            $this->faker->email, 
            $this->faker->lastName,
            $this->faker->word
        );

        $this->assertGreaterThan(0, $new_user_id);
    }

}
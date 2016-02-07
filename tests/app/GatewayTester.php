<?php 

use OceanCrest\DB;
use OceanCrest\User;
use OceanCrest\UserGateway;

class GatewayTester extends \PHPUnit_Framework_TestCase
{
    protected $connection;

    protected $gateway;

    protected $faker;

    public function setUp()
    {
        $this->db = new DB('127.0.0.1', 'root', '', 'oceancrest_test');
        
        $this->faker =  Faker\Factory::create();
    }

    public function tearDown()
    {
        // Reset the tables
        mysqli_query($this->db->connection, "DELETE FROM `ocUsers`");
        mysqli_query($this->db->connection, "DELETE FROM `ocCalendar`");
        mysqli_query($this->db->connection, "ALTER TABLE `ocUsers` AUTO_INCREMENT = 1");
        mysqli_query($this->db->connection, "ALTER TABLE `ocCalendar` AUTO_INCREMENT = 1");
    }

    protected function makeUser()
    {
        $userGateway = new UserGateway($this->db);

        $mockUser = new stdClass();
        $mockUser->name = $this->faker->firstName;
        $mockUser->email = $this->faker->email;
        $mockUser->side = $this->faker->lastName;
        $mockUser->password = $this->faker->word;

        $mockUser->id = $userGateway->create(
            $mockUser->name,
            $mockUser->email, 
            $mockUser->side,
            $mockUser->password
        );

        return $mockUser;
    }
}
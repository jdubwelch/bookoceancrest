<?php namespace OceanCrest;

class AuthGateway
{
    /**
     * Set via constructor
     * @var DB
     */
    protected $db;

    function __construct(DB $db) {
        $this->db = $db;
    }

    public function attempt($credentials)
    {
        $email = $this->db->escape_data($credentials['email']);
        $password = $this->db->escape_data($credentials['password']);

        $query = "SELECT user_id, name, side 
                FROM ocUsers 
                WHERE (email='{$email}' 
                    AND password = PASSWORD('{$password}') 
                    AND activated = '1')
                LIMIT 1";        
        $result = mysqli_query ($this->db->connection, $query) 
            or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error());

        if (! $result) {
            return false;
        }
        
        list($id, $name, $side) = mysqli_fetch_row($result); 
        mysqli_free_result($result);

        if (! $id) {
            return false;
        }

        return new User($id, $name, $side);
    }
}
<?php namespace OceanCrest;

class UserGateway extends DB {

    /**
     * Set via constructor
     * @var mysql_connection
     */
    protected $connection;

    /**
     * Activate an existing user.
     * 
     * @param  int $user_id 
     * @return int
     */
    public function activate($user_id)
    {
        $query = "UPDATE `ocUsers` SET `activated` = '1' WHERE `user_id` = '{$user_id}' LIMIT 1;";      
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());

        return mysql_affected_rows();
    }

    /**
     * Get the email for a user.
     * 
     * @param  int $user_id
     * @return string
     */
    public function email($user_id)
    {
        $query = "SELECT email FROM ocUsers WHERE user_id = '{$user_id}' LIMIT 1";
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());
        
        $row = mysql_fetch_array($result);
        
        return $row[0];
    }

    /**
     * Get the user id from a given email address.
     * @param  string $email 
     * @return int|false
     */
    public function getUserByEmail($email)
    {
        $query = "SELECT user_id FROM ocUsers WHERE email='".  $this->escape_data($email) . "'";     
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());
        if (mysql_num_rows($result) == 1) {
            // Retrieve the user ID.
            list($uid) = mysql_fetch_array ($result, MYSQL_NUM); 
        } else {
            echo '<p><font color="red" size="+1">The submitted email address does not match those on file!</font></p>';
            $uid = FALSE;
        }

        return $uid;
    }

    /**
     * Update the password for a user.
     * 
     * @param  int $user_id  
     * @param  string $password 
     * @return boolean           
     */
    public function updatePassword($user_id, $password)
    {
        // Make the query.
        $query = "UPDATE `ocUsers` SET `password` = PASSWORD('{$password}') WHERE `user_id` = '{$user_id}' LIMIT 1";     
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());
        return mysql_affected_rows();
    }

    public function uniqueEmail($email)
    {
        // Make sure the email address is available.
        $query = "SELECT `user_id` FROM ocUsers WHERE email='$email' LIMIT 1"; 
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());
        return (mysql_num_rows($result) == 0);
    }

    /**
     * Create a new user.
     * 
     * @param  string $name     
     * @param  string $email    
     * @param  string $side     
     * @param  string $password 
     * @return $user_id int
     */
    public function create($name, $email, $side, $password)
    {
        $query = "INSERT INTO `ocUsers` (`user_id`, `name`, `email`, `password`, `side`, `activated`) 
        VALUES (NULL, '$name', '$email', PASSWORD('$password'), '$side', '0');";       
        $result = mysql_query ($query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());

        if (! $result) {
            return false;
        }

        return mysql_insert_id();
    }





}
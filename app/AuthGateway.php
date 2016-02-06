<?php namespace OceanCrest;

class AuthGateway extends DB
{
    public function attempt($credentials)
    {
        $query = "SELECT user_id, name, side 
                FROM ocUsers 
                WHERE (email='{$credentials['email']}' 
                    AND password = PASSWORD('{$credentials['password']}') 
                    AND activated = '1')
                LIMIT 1";        
        $result = mysql_query ($query) 
            or trigger_error("Query: $query\n<br />MySQL Error: " . mysql_error());

        if (! $result) {
            return false;
        }
        
        list($id, $name, $side) = mysql_fetch_assoc($result, MYSQL_NUM); 
        mysql_free_result($result);

        return new User($id, $name, $side);
    }
}
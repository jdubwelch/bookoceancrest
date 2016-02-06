<?php namespace OceanCrest;

class DB {

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Create a function for escaping the data.
    public function escape_data ($data) {
        
        // Address Magic Quotes.
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        
        // Check for mysql_real_escape_string() support.
        if (function_exists('mysql_real_escape_string')) {
            $data = mysql_real_escape_string (trim($data), $this->connection);
        } else {
            $data = mysql_escape_string (trim($data));
        }

        // Return the escaped value.    
        return $data;

    } 

}
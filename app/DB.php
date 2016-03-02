<?php namespace OceanCrest;

class DB {

    public $connection;

    public function __construct($host, $user, $password, $database)
    {
        $this->connection = $this->connect($host, $user, $password, $database);
    }

    public function connect($host, $user, $password, $database)
    {
        if ($this->connection) {
            return $this->connection;
        }

        if ($dbc = mysqli_connect ($host, $user, $password)) { // Make the connnection.

            if (!mysqli_select_db ($dbc, $database)) { // If it can't select the database.
            
                // Handle the error.
                trigger_error("Could not select the database!\n<br />MySQL Error: " . mysqli_error());
                exit();
                
            } // End of mysqli_select_db IF.

            return $dbc;
            
        } else { // If it couldn't connect to MySQL.

            // Print a message to the user, include the footer, and kill the script.
            trigger_error("Could not connect to MySQL!\n<br />MySQL Error: " . mysqli_error());
            exit();
            
        } // End of $dbc IF.
    }

    // Create a function for escaping the data.
    public function escape_data ($data) {
        
        // Address Magic Quotes.
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        
        // Check for mysql_real_escape_string() support.
        if (function_exists('mysql_real_escape_string')) {
            $data = mysqli_real_escape_string ($this->connection, trim($data));
        } else {
            $data = mysqli_escape_string ($this->connection, trim($data));
        }

        // Return the escaped value.    
        return $data;

    } 

}
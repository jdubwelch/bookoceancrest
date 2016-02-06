<?php namespace OceanCrest;

class DB {

    // Create a function for escaping the data.
    public static function escape_data ($data) {
        
        // Address Magic Quotes.
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        
        // Check for mysql_real_escape_string() support.
        if (function_exists('mysql_real_escape_string')) {
            global $dbc; // Need the connection.
            $data = mysql_real_escape_string (trim($data), $dbc);
        } else {
            $data = mysql_escape_string (trim($data));
        }

        // Return the escaped value.    
        return $data;

    } 

}
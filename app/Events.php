<?php namespace OceanCrest;

class Events 
{
    public static function readCalendarData ($month, $year) {

        require_once("../cgi-bin/oc/dbConnection.php");
        
        // CREATE TIMESTAMPS TO SEARCH WITH
        $firstDayTimestamp = mktime (0,0,0, $month, 1, $year);
        $daysInMonth = date ("t", $firstDayTimestamp);
        $lastDayTimestamp = mktime (23, 59, 59, $month, $daysInMonth, $year);
        
        // CREATE SQL
        $sql = "SELECT id, UNIX_TIMESTAMP(dateField) AS timestampField, family ";
        $sql .= "FROM ocCalendar ";
        $sql .= "WHERE UNIX_TIMESTAMP(dateField) >= " . $firstDayTimestamp . " AND UNIX_TIMESTAMP(dateField) <= " . $lastDayTimestamp . " ";
        $sql .= "ORDER BY timestampField ASC";
        
        // READ IN DATA
        $dbResult = mysql_query($sql) or die ("MYSQL Error: " . mysql_error() );
        $numRecords = mysql_num_rows ($dbResult);
        $eventsArray[] = "";
        $eventData = [];

        for ($i=0; $i < $numRecords; $i++) {
            $row = mysql_fetch_assoc ($dbResult);
            $day = date ("j", $row['timestampField']);
            $family = $row['family'];
                    
            // CHECK DATE ISN'T ALREADY IN $eventsArray
            if(!in_array($day, $eventsArray)) {
                $eventData[$day] = $family;         
            } 
        }

        // CLOSE DB CONNECTION
        mysql_close($dbc);
        
        // RETURN eventsArray TO CODE THAT CALLED FUNCTION 
        return $eventData;
    }

}
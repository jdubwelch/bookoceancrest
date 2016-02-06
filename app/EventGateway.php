<?php namespace OceanCrest;

class EventGateway extends DB {

    /**
     * Get the details for a single date.  
     * 
     * @param  string $date Format: mm/dd/yyyy
     * @return array
     */
    public function details($date)
    {
        $dateArray = explode("/",$date);
        $startTimestamp = mktime(0,0,0,$dateArray[1],$dateArray[0],$dateArray[2]);
        $endTimestamp = mktime(23,59,00,$dateArray[1],$dateArray[0],$dateArray[2]);

        // Create SQL to read in database records
        $sql  = "SELECT id,family, UNIX_TIMESTAMP(dateField) AS `timestampField`  ";
        $sql .= "FROM ocCalendar ";
        $sql .= "WHERE UNIX_TIMESTAMP(dateField) >= " . $startTimestamp . " AND UNIX_TIMESTAMP(dateField) <= " . $endTimestamp . " ";
        $sql .= "ORDER BY UNIX_TIMESTAMP(dateField) ASC";

        // Read in Records from Database  
        $dbResult = mysql_query($sql)
          or die ("MySQL Error: " . mysql_error() );
        $numRecords = mysql_num_rows($dbResult);
        for($i=0;$i < $numRecords;$i++){
            $temp = mysql_fetch_assoc($dbResult);
            if (!get_magic_quotes_gpc()) {
                $temp['family'] = stripslashes($temp['family']);
            }
            $records[] = $temp;
        }

        return $records;
    }

    /**
     * Cancel an event.
     * 
     * @param  int $id 
     * @return boolean
     */
    public function cancel($id)
    {
        $query = "DELETE FROM `ocCalendar` WHERE `id` = '$id' LIMIT 1";
        return mysql_query($query);
    }

    /**
     * Reserve the cabin for a number of days.
     * @param  string $family    
     * @param  string $startDate
     * @param  int $duration  
     * @return boolean
     */
    public function reserve($family, $startDate, $duration)
    {
        // Create Date Time Field
        $time = "00:00:00";
        $dateArray = explode("/", $startDate);
        $d = $dateArray[0];
        $yr = $dateArray[2];
        $mo = $dateArray[1];
        
        if ($duration > 1) {
            for ($i = 0; $i < $duration; $i++) {
                $reserveDates = $d + $i;
                $datetime = "$yr-$mo-$reserveDates $time";
                $sql = "INSERT INTO ocCalendar (dateField,family,event) VALUES ('" . $datetime . "','" . $family . "','" . $event . "')";
                mysql_query($sql);
            }
        } else {
            $datetime = "$yr-$mo-$d $time";
            $sql = "INSERT INTO ocCalendar (dateField,family,event) VALUES ('" . $datetime . "','" . $family . "','" . $event . "')";
            mysql_query($sql);
        }

        return true;
    }
}
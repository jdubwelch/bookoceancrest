<?php namespace OceanCrest;

class EventGateway {

    /**
     * Set via constructor
     * @var DB
     */
    protected $db;

    function __construct(DB $db) {
        $this->db = $db;
    }

    public function monthlyEvents($month, $year) {
        
        // CREATE TIMESTAMPS TO SEARCH WITH
        $firstDayTimestamp = mktime (0,0,0, $month, 1, $year);
        $daysInMonth = date ("t", $firstDayTimestamp);
        $lastDayTimestamp = mktime (23, 59, 59, $month, $daysInMonth, $year);
        
        // CREATE SQL
        $sql = "SELECT id, UNIX_TIMESTAMP(dateField) AS `timestampField`, family ";
        $sql .= "FROM ocCalendar ";
        $sql .= "WHERE UNIX_TIMESTAMP(dateField) >= " . $firstDayTimestamp . " AND UNIX_TIMESTAMP(dateField) <= " . $lastDayTimestamp . " ";
        $sql .= "ORDER BY timestampField ASC";
        
        // READ IN DATA
        $dbResult = mysqli_query($this->db->connection, $sql) 
            or die ("MYSQL Error: " . mysqli_error($this->db->connection) );
        $numRecords = mysqli_num_rows ($dbResult);
        $eventsArray[] = "";
        $eventData = [];

        for ($i=0; $i < $numRecords; $i++) {
            $row = mysqli_fetch_assoc ($dbResult);
            $day = date ("j", $row['timestampField']);
            $family = $row['family'];
                    
            // CHECK DATE ISN'T ALREADY IN $eventsArray
            if(!in_array($day, $eventsArray)) {
                $eventData[$day] = $family;         
            } 
        }

        
        // RETURN eventsArray TO CODE THAT CALLED FUNCTION 
        return $eventData;
    }

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
        $dbResult = mysqli_query($this->db->connection, $sql)
          or die ("MySQL Error: " . mysql_error() );
        $numRecords = mysqli_num_rows($dbResult);
        for($i=0;$i < $numRecords;$i++){
            $temp = mysqli_fetch_assoc($dbResult);
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
        return mysqli_query($this->db->connection, $query);
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
        $mo = $dateArray[1];
        $yr = $dateArray[2];
        
        if ($duration > 1) {
            for ($i = 0; $i < $duration; $i++) {
                $reserveDates = $d + $i;
                $datetime = "$yr-$mo-$reserveDates $time";
                $sql = "INSERT INTO ocCalendar (dateField,family) VALUES ('" . $datetime . "','" . $this->db->escape_data($family) . "')";
                mysqli_query($this->db->connection, $sql) or trigger_error("Query: $sql\n<br />MySQL Error: " . mysqli_error($this->db->connection));;
            }
        } else {
            $datetime = "$yr-$mo-$d $time";
            $sql = "INSERT INTO ocCalendar (dateField,family) VALUES ('" . $datetime . "','" . $family . "')";
            mysqli_query($this->db->connection, $sql) or trigger_error("Query: $sql\n<br />MySQL Error: " . mysqli_error($this->db->connection));;
        }

        return mysqli_insert_id($this->db->connection);
    }
}
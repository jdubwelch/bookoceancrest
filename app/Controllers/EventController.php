<?php namespace OceanCrest\Controllers;

use Mlaphp\Request;
use Mlaphp\Response;
use OceanCrest\EventGateway;

class EventController 
{
    protected $request;
    protected $eventGateway;
    protected $response;

    function __construct(Request $request, EventGateway $eventGateway, Response $response)
    {
        $this->request = $request;
        $this->eventGateway = $eventGateway;
        $this->response = $response;
    }

    function __invoke()
    {
        // Check that we have a date parameter in the URL, if none redirect back to calendar page
        if(strlen($this->request->get['day']) < 1){
            header("Location: index.php");
            exit();
        }

        // MAKE SURE THEY ARE LOGGED IN
        if (isset($this->request->session['name'])) {
            $name = $this->request->session['name'];
        } else {
            // Start defining the URL.
            $url = 'http://' . $this->request->server['HTTP_HOST'] . dirname($this->request->server['PHP_SELF']);
            // Check for a trailing slash.
            if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
                $url = substr ($url, 0, -1); // Chop off the slash.
            }
            // Add the page.
            $url .= '/index.php';
            
            ob_end_clean(); // Delete the buffer.
            header("Location: $url");
            exit(); // Quit the script.
        }

        // Create Timestamps to read in all events on given day
        $date = $this->request->get['day'];

        $records = $this->eventGateway->details($date);

        $dateArray = explode("/",$date);
        $month = $dateArray[1];
        $day = $dateArray[0];
        $year = $dateArray[2];

        if (isset($this->request->post['delete'])) {

            // CHECK TO MAKE SURE THE RIGHT PERSON IS ACCESSING THIS PAGE
            if ($name == $records[0]['family']) {
                
                $id = $this->request->post['id'];
                $result = $this->eventGateway->cancel($id);
                
                if ($result) {
                    header("Location: calendar.php?month=$month&year=$year");
                    exit();
                }
            } 
        }

        $this->response->setView('details.php'); 
        $this->response->setVars([
            'request' => $this->request,
            'name' => $this->request->session['name'],
            'event' => [
                'family' => $records[0]['family'],
                'id' => $records[0]['id'],
                'date' => "$month/$day/$year",
                'owned' => ($this->request->session['name'] == $records[0]['family'])
            ]   
        ]);

        return $this->response;
    }
}

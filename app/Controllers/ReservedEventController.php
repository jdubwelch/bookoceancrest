<?php namespace OceanCrest\Controllers;

use Mlaphp\Request;
use Mlaphp\Response;
use OceanCrest\EventGateway;

class ReservedEventController 
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

    public function __invoke()
    {
        // Check that we have a date parameter in the URL, if none redirect back to calendar page
        if(strlen($this->request->get['day']) < 1){
            header("Location: index.php");
            exit();
        }
        
        // Create Timestamps to read in all events on given day
        $date = $this->request->get['day'];

        $dateArray = explode("/",$date);
        $month = $dateArray[0];
        $day = $dateArray[1];
        $year = $dateArray[2];

        // Handle the Delete Request
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

        $records = $this->eventGateway->details($date);

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

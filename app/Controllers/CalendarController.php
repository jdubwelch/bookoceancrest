<?php namespace OceanCrest\Controllers;

use Mlaphp\Request;
use Mlaphp\Response;
use OceanCrest\EventGateway;

class CalendarController 
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
        // Set the page title and include the HTML header.
        $page_title = 'O C E A N  C R E S T >> CALENDAR';

        //  Set the date to display
        if (isset($this->request->post['submit'])) {

            $submit = $this->request->post['submit'];
            $month_now = $this->request->post['month'];
            $year_now = $this->request->post['year'];

            // Subtract one from month for prev and add 1 for next
            if ($submit == "Prev") {
                $month_now--;
            } else {
                $month_now++;
            } 
            
            $date = getdate(mktime(0,0,0,$month_now, 1, $year_now));

        } elseif (isset($this->request->get['month'])) {

            $date = getdate(mktime(0,0,0, $this->request->get['month'], 1, $this->request->get['year']));
                
        } else {
            $date = getdate();
        }

        $month = $date["mon"];
        $monthName = $date["month"];
        $year = $date["year"];

        $eventData = $this->eventGateway->monthlyEvents($month, $year);

        $this->response->setView('calendar.php'); 
        $this->response->setVars([
            'request' => $this->request,
            'name' => $this->request->session['name'],
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'eventData' => $eventData
        ]);

        return $this->response;
    }

}

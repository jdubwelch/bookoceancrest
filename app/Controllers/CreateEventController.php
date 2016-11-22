<?php namespace OceanCrest\Controllers;

use Mlaphp\Request;
use Mlaphp\Response;
use OceanCrest\EventGateway;

class CreateEventController
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
        if(isset($this->request->post['action']) && $this->request->post['action'] == "add"){

            $this->eventGateway->reserve(
                $this->request->session['name'],
                $this->request->post['date'],
                $this->request->post['staying']
            );

            $dateArray = explode("/", $this->request->post['date']);

            $mo = $dateArray[0];
            $yr = $dateArray[2];

            // Return to Calendar Page
            header("Location: calendar.php?month=$mo&year=$yr");
        }

        $da = $this->request->get['day'];
        $da = explode ('/', $da);

        $month = $da[0];
        $day = $da[1];
        $year = $da[2];

        $this->response->setView('add_event.php');
        $this->response->setVars([
            'request'      => $this->request,
            'name'         => $this->request->session['name'],
            'action'       => $this->request->server['PHP_SELF'],
            'day'          => $this->request->get['day'],
            'arrival_date' => "$month/$day/$year",
        ]);

        return $this->response;
    }
}

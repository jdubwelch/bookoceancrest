<?php 

use \Mockery as m;
use OceanCrest\Controllers\CalendarController;
use Mlaphp\Request;
use Mlaphp\Response;

class CalendarControllerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_shows_the_calendar()
    {
        $request = new Request($GLOBALS);
        $eventGateway = m::mock('OceanCrest\EventGateway');
        $response = new Response(__DIR__.'/../views'); 

        $request->post = [];
        $request->session['name'] = 'Bob';
        $request->get['month'] = '2';
        $request->get['year'] = '2016';

        $eventGateway
            ->shouldReceive('monthlyEvents')
            ->with('2', '2016')
            ->once()
            ->andReturn([]);

        $controller = new CalendarController($request, $eventGateway, $response);
        $response = $controller->__invoke();

        $view = $response->getView();
        $vars = $response->getVars();

        $this->assertSame('calendar.php', $view);
    }

}
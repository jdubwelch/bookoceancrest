<?php 

use \Mockery as m;
use OceanCrest\Controllers\CreateEventController;
use Mlaphp\Request;
use Mlaphp\Response;

class CreateEventControllerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_shows_the_view_for_creating_a_new_event()
    {
        $request = new Request($GLOBALS);
        $eventGateway = m::mock('OceanCrest\EventGateway');
        $response = new Response(__DIR__.'/../views'); 

        $request->post = [];
        $request->get['day'] = '2/12/2016';
        $this->request->server['PHP_SELF'] = 'foo';

        $controller = new CreateEventController($request, $eventGateway, $response);
        $response = $controller->__invoke();

        $view = $response->getView();
        $vars = $response->getVars();

        $this->assertSame('add_event.php', $view);
        $this->assertSame('2/12/2016', $vars['day']);
        $this->assertSame('2/12/2016', $vars['arrival_date']);
    }
}
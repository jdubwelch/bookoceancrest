<?php 

use \Mockery as m;
use OceanCrest\Controllers\EventController;
use Mlaphp\Request;
use Mlaphp\Response;

class EventControllerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    function it_uses_the_details_view()
    {
        $request = new Request($GLOBALS);
        $eventGateway = m::mock('OceanCrest\EventGateway');
        $response = new Response(__DIR__.'/../views'); 

        $request->get['day'] = '3/2/2016';
        $request->post = [];
        $request->session['name'] = 'Bob';

        $eventGateway
            ->shouldReceive('details')
            ->with('3/2/2016')
            ->once()
            ->andReturn([
                [
                    'id' => 1,
                    'family' => 'Welch',
                    'date' => '2/3/2016',
                    'owned' => true
                ]
            ]);

        $controller = new EventController($request, $eventGateway, $response);
        $response = $controller->__invoke();


        $view = $response->getView();
        $vars = $response->getVars();

        $this->assertSame('details.php', $view);
        $this->assertSame('Welch', $vars['event']['family']);
        $this->assertSame('Bob', $vars['name']);
    }
}
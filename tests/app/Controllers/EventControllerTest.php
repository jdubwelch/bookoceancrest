<?php 

use \Mockery as m;
use OceanCrest\Controllers\ReservedEventController;
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
    function it_shows_an_event()
    {
        $request = new Request($GLOBALS);
        $eventGateway = m::mock('OceanCrest\EventGateway');
        $response = new Response(__DIR__.'/../views'); 

        $request->get['day'] = '2/3/2016';
        $request->post = [];
        $request->session['name'] = 'Bob';

        $eventGateway
            ->shouldReceive('details')
            ->with('2/3/2016')
            ->once()
            ->andReturn([
                [
                    'id' => 1,
                    'family' => 'Welch',
                    'date' => '2/3/2016',
                    'owned' => true
                ]
            ]);

        $controller = new ReservedEventController($request, $eventGateway, $response);
        $response = $controller->__invoke();

        $view = $response->getView();
        $vars = $response->getVars();

        $this->assertSame('details.php', $view);
        $this->assertSame('Welch', $vars['event']['family']);
        $this->assertSame('2/3/2016', $vars['event']['date']);
        $this->assertSame('Bob', $vars['name']);
    }
}
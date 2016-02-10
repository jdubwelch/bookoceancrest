<?php 

class DetailsHtmlTest extends \PHPUnit_Framework_TestCase
{
    protected $response;
    protected $output;
    
    public function setUp()
    {
        $request = new \Mlaphp\Request($GLOBALS);

        $this->response = new \Mlaphp\Response(__DIR__.'/../../views');
        $this->response->setView('details.php');
        $this->response->setVars([
            'request' => $request,
            'name' => 'Jason & Deena',
            'event' => [
                'family' => 'Jason & Deena',
                'id' => 1,
                'date' => "3/30/2016",
                'owned' => true
            ]   
        ]);
        $this->output = $this->response->requireView();
    }

    /**
     * @test
     */
    function it_displays_the_date_and_who_is_staying()
    {
        $expect = '<h3>Jason & Deena have reserved the cabin on 3/30/2016</h3>';
        $this->assertOutputHas($expect);
    }

    /**
     * @test
     */
    function it_shows_a_button_to_cancel_the_event_if_owned_by_the_user()
    {
        $expect = 'click if not staying anymore';
        $this->assertOutputHas($expect);
    }

    public function assertOutputHas($expect)
    {
        if (strpos($this->output, $expect) === false) {
            $this->fail("Did not find expected output: $expect");
        }
    }
}


?>
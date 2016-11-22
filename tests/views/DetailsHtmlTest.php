<?php

class DetailsHtmlTest extends \PHPUnit_Framework_TestCase
{
    protected $response;
    protected $request;
    protected $output;

    public function setUp()
    {
        $this->request = new \Mlaphp\Request($GLOBALS);

        $this->response = new \Mlaphp\Response(__DIR__.'/../../views');
        $this->response->setView('details.php');
        $this->response->setVars([
            'request' => $this->request,
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
        $this->assertOutputHas('Jason & Deena');
        $this->assertOutputHas('3/30/2016');
    }

    /**
     * @test
     */
    function it_shows_a_button_to_cancel_the_event_if_owned_by_the_user()
    {
        $expect = 'click if not staying anymore';
        $this->assertOutputHas($expect);
    }

    /**
     * @test
     */
    function it_does_not_show_the_cancel_button_when_the_event_is_not_owned_by_user()
    {
        $this->response->setVars([
            'request' => $this->request,
            'name' => 'Not Jason or Deena',
            'event' => [
                'family' => 'Jason & Deena',
                'id' => 1,
                'date' => "3/30/2016",
                'owned' => false
            ]
        ]);
        $this->output = $this->response->requireView();

        $expect = 'click if not staying anymore';
        $this->assertOutputDoesNotHave($expect);
    }

    public function assertOutputHas($expect)
    {
        if (! $this->outputHas($expect)) {
            $this->fail("Did not find expected output: $expect");
        }
    }

    public function assertOutputDoesNotHave($expect)
    {
        if ($this->outputHas($expect)) {
            $this->fail("Did not expect to have this output: $expect");
        }
    }

    private function outputHas($expect)
    {
        return strpos($this->output, $expect) !== false;
    }
}


?>
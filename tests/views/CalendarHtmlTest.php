<?php 

class CalendarHtmlTest extends \PHPUnit_Framework_TestCase
{
    protected $response;
    protected $request;
    protected $output;
    
    public function setUp()
    {
        $this->request = new \Mlaphp\Request($GLOBALS);

        $this->response = new \Mlaphp\Response(__DIR__.'/../../views');
        $this->response->setView('calendar.php');
        $this->response->setVars([
            'request' => $this->request,
            'name' => 'Jason & Deena',
            'month' => '2',
            'year' => '2016',
            'monthName' => 'February',
            'eventData' => [
                    1 => 'Bob Schu',
                    2 => 'Bob Schu',
                    3 => 'Jason & Deena',
                    4 => 'Jason & Deena',
                    23 => 'Jason & Deena',
            ]

        ]);
    }

    /**
     * @test
     */
    function it_works()
    {
        // $this->assertNotEmpty($this->output);
    }

    /**
     * @test
     */
    function it_displays_the_name_of_the_person_who_has_reserved_a_day()
    {
        // $expectations = [
        //     '<a href="details.php?day=1/2/2016">1</a></div><div id="event">Bob Schu</div>',
        //     '<a href="details.php?day=2/2/2016">2</a></div><div id="event">Bob Schu</div>',
        //     '<a href="details.php?day=4/2/2016">4</a></div><div id="event">Jason & Deena</div>',
        //     '<a href="details.php?day=23/2/2016">23</a></div><div id="event">Jason & Deena</div>',
        // ];

        // foreach ($expectations as $expect) {
        //     $this->assertOutputHas($expect);
        // }
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
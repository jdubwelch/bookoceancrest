<?php 

class AddEventHtmlTest extends \PHPUnit_Framework_TestCase
{
    protected $response;
    protected $request;
    protected $output;
    
    public function setUp()
    {
        $this->request = new \Mlaphp\Request($GLOBALS);

        $this->response = new \Mlaphp\Response(__DIR__.'/../../views');
        $this->response->setView('add_event.php');
        $this->response->setVars([
            'request' => $this->request,
            'name' => 'Jason & Deena',
            'action' => '/add_event.php',
            'day' => '5/2/2016',
            'arrival_date' => '2/5/2016'
        ]);
        $this->output = $this->response->requireView();
    }
    
   /**
    * @test
    */
   function it_shows_the_date_of_arrival()
   {
        $this->assertOutputHas('<td align="left">2/5/2016</td>');
   }

   /**
    * @test
    */
   function it_passes_the_day_through_the_form()
   {
        $this->assertOutputHas('<input name="date" type="hidden" value="5/2/2016">');
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
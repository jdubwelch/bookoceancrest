<?php namespace OceanCrest;

use Mlaphp\Request;

class EventTransaction 
{
    private $eventGateway;

    public function __construct(EventGateway $eventGateway)
    {
        $this->eventGateway = $eventGateway;
    }
}
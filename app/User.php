<?php namespace OceanCrest;

class User
{
    public $id;
    public $name;
    public $side;

    public function __construct($id, $name, $side)
    {
        $this->id = $id;
        $this->name = $name;
        $this->side = $side;
    }
}
<?php
namespace App;

class Controller implements ControllerInterface
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
    
    }
}

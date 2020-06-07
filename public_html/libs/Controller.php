<?php
require_once 'ControllerInterface.php';

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

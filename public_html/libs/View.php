<?php
namespace App;

class View
{
    public function __construct()
    {
        
    }
    
    public function render(String $view)
    {
        include 'views/header.phtml';
        include $view;
        include 'views/footer.phtml';
    }
}

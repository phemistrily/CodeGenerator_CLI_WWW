<?php

class View
{
	public function __construct()
	{
		
	}
	
	public function render(String $view)
	{
    require('views/header.phtml');
    require($view);
    require('views/footer.phtml');
	}
}
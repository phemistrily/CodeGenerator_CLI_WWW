<?php

class IndexController extends Controller
{
  public function index()
  {
		$this->view->render('views/index.phtml');
  }
}
<?php
class Boot
{
  private $route;
  private $controllerName;
  private $controllerFile;
  private $controller;

  public function __construct()
  {
    $this->loadFrameworkFiles();
    if(!$this->checkIsPost())
    {
      $this->generateGetRouting();
      $this->setRequiredVariables();
      $this->runPage();
    }
    else
    {
      $this->generatePostRouting();
      $this->setRequiredVariables();
      $this->runForm();
    }
  }

  private function loadFrameworkFiles()
  {
    require_once('Controller.php');
    require_once('View.php');
  }

  private function checkIsPost()
  {
    if(isset($_POST['form'])) return true;
  }

  private function generateGetRouting()
  {
    $this->route = explode('/',rtrim($_SERVER['REQUEST_URI'], '/'));
    var_dump($this->route);
    if(!isset($this->route[1]))
      $this->route[1] = '';
    switch ($this->route[1]) {
      case '':
        $this->controllerName = "index";
        break;
      
      default:
        $this->controllerName = "notAuthorized";
        break;
    }
  }

  

  private function setRequiredVariables()
  {
    $this->controllerName = ucfirst($this->controllerName). 'Controller';
    $this->controllerFile = sprintf('controllers/%s.php', $this->controllerName);
  }

  private function runPage()
  {
    if($this->importController())
    {
      $this->runAction();
    }
  }
  
  private function importController() :bool
  {
    if(file_exists($this->controllerFile))
    {
      require_once($this->controllerFile);
      $this->controller = new $this->controllerName;
      return true;
    }
    else
    {
      var_dump('File Not found');
      return false;
    }
  }

  private function runAction()
  {
    if (isset($this->route[2])) {
      $actionName = $this->route[2];
      if(isset($this->route[3])) {
        $this->controller->{$actionName}($this->route[3]);	
      }
      else {
        $this->controller->{$actionName}();
      }
    }
    else
    {
      $this->controller->index();
    }				
  }
  
  private function generatePostRouting()
  {
    $this->formAction = $_POST['form'];
    unset($_POST['form']);
    $this->postParms = $_POST;
    switch ($this->formAction) {
      case 'generateCodes':
        $this->controllerName = "codes";
        break;
      
      default:
        header('location: /');
        break;
    }
  }

  private function runForm()
  {
    if($this->importController())
    {
      $this->controller->{$this->formAction}(...array_values($this->postParms));
    }
  }
}
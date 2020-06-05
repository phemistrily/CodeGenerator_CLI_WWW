<?php
class Boot
{
  private $route;
  private $controllerName;
  private $controllerFile;
  private $controller;
  private $cliParms;

  public function __construct($argc = null, $argv = null)
  {
    $this->loadFrameworkFiles();
    if($argc && $argv) {
      $actionKey = $this->getActionCli($argv);
      $this->generateCliRouting($argv, $actionKey);
      $this->setRequiredVariables();
      $this->runCli($argv);
    }
    else if(!$this->checkIsPost())
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
    return isset($_POST['form']);
  }

  private function generateGetRouting()
  {
    $this->route = explode('/',rtrim($_SERVER['REQUEST_URI'], '/'));
    if(!isset($this->route[1]))
      $this->route[1] = '';
    switch ($this->route[1]) {
      case 'index':
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

  private function runCli()
  {
    if($this->importController())
    {
      $this->controller->{$this->cliAction}(...array_values($this->cliParms));
    }
  }

  private function getActionCli($argv)
  {
    if(in_array('--action', $argv))
    {
        $key = array_search('--action', $argv);
    }
    return $key+1;
  }

  private function generateCliRouting($argv, $actionKey)
  {
    switch ($argv[$actionKey]) {
      case 'generateCodes':
        $this->cliAction = 'generateCodes';
        $this->controllerName = 'codes';
        $this->cliParms = $this->setParmsForGenerateCodes($argv);
        break;
      
      default:
        die('no permission');
        break;
    }
  }

  private function setParmsForGenerateCodes($argv)
  {
      $parmsArray = [
        'numberOfCodes' => 100,
        'lengthOfCode' => 10,
        'renderType' => 'cli',
        'file' => 'kody.txt'
      ];
      if(in_array('--numberOfCodes', $argv))
      {
          $key = array_search('--numberOfCodes', $argv)+1;
      }
      $parmsArray['numberOfCodes'] = $argv[$key];
      if(in_array('--lengthOfCode', $argv))
      {
          $key = array_search('--lengthOfCode', $argv)+1;
      }
      $parmsArray['lengthOfCode'] = $argv[$key];
      if(in_array('--file', $argv))
      {
          $key = array_search('--file', $argv)+1;
      }
      $parmsArray['file'] = $argv[$key];
      return $parmsArray;
  }
}
<?php
namespace App;

use App\Controllers;
class Boot
{
    private $route;
    private $controllerPrefix = 'App\\Controllers\\';
    private $controllerName;
    private $controllerFile;
    private $controller;
    private $cliParms;

    public function __construct($argc = null, $argv = null)
    {
        
        if ($argc && $argv) {
            $actionKey = $this->getActionCli($argv);
            $this->generateCliRouting($argv, $actionKey);
            $this->setRequiredVariables();
            $this->runCli($argv);
        } else if (!$this->checkIsPost()) {
            $this->generateGetRouting();
            $this->setRequiredVariables();
            $this->runPage();
        } else {
            $this->generatePostRouting();
            $this->setRequiredVariables();
            $this->runForm();
        }
    }

    private function checkIsPost()
    {
        return isset($_POST['form']);
    }

    private function generateGetRouting()
    {
        $this->route = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        if (!isset($this->route[1])) {
            $this->route[1] = '';
        }
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
        $this->controllerName = $this->controllerPrefix .ucfirst($this->controllerName). 'Controller';
    }

    private function runPage()
    {
        if ($this->importController()) {
            $this->runAction();
        }
    }
  
    private function importController() :bool
    {
        try {
            $this->controller = new $this->controllerName();
            return true;
        }
        catch (\Throwable $th) {
            header('location: /');
            // var_dump($th);
            // var_dump('File Not found');
            return false;
        }
    }

    private function runAction()
    {
        if (isset($this->route[2])) {
            $actionName = $this->route[2];
            if (isset($this->route[3])) {
                $this->controller->{$actionName}($this->route[3]);    
            } else {
                $this->controller->{$actionName}();
            }
        } else {
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
        if ($this->importController()) {
            $this->controller->{$this->formAction}(...array_values($this->postParms));
        }
    }

    private function runCli()
    {
        if ($this->importController()) {
            $this->controller->{$this->cliAction}(...array_values($this->cliParms));
        }
    }

    private function getActionCli($argv)
    {
        if (in_array('--action', $argv)) {
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
      
        $parmsArray['numberOfCodes'] = $this->setArgvParam($argv, '--numberOfCodes');
        $parmsArray['lengthOfCode'] = $this->setArgvParam($argv, '--lengthOfCode');
        $parmsArray['file'] = $this->setArgvParam($argv, '--file');
        return $parmsArray;
    }

    private function setArgvParam($argv, $param)
    {
        if (in_array($param, $argv)) {
            $key = array_search($param, $argv)+1;
        }
        return $argv[$key];
    }
}

<?php
require_once('models/Code.php');
require_once('models/File.php');
class CodesController extends Controller
{
  private $generatedCodes = array();

  public function index()
  {
		//$this->view->render('views/index.phtml');
  }

  public function generateCodes(int $numberOfCodes,int $lengthOfCode, string $renderBy = 'file')
  {
    $code = new Code();
    for ($i = 0; $i < $numberOfCodes; $i++)
    {
      $newCode = $code->createCode($lengthOfCode);
      if(!$this->containsCode($newCode))
      {
        array_push($this->generatedCodes, $newCode);
      }
    }
    $this->renderCodes($renderBy);
  }

  private function containsCode($code)
  {
    if(in_array($code, $this->generatedCodes))
    {
      return true;
    }
    return false;
  }

  private function renderCodes($renderBy)
  {
    switch ($renderBy) {
      case 'file':
        $file = new File();
        $this->writeCodesInFile($file);
        break;
      
      default:
        var_dump('Nope');
        break;
    }
  }

  private function writeCodesInFile($file)
  {
    if(is_array($this->generatedCodes))
    {
      foreach ($this->generatedCodes as $code) {
        $file->writeInFile($code);
        $file->readFile();
      }
    }
    
  }
}
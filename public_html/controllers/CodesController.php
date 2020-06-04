<?php
require_once('models/Code.php');
require_once('models/File.php');
class CodesController extends Controller
{
  public function index()
  {
		//$this->view->render('views/index.phtml');
  }

  public function generateCodes(int $numberOfCodes,int $lengthOfCode)
  {
    $file = new File();

    $code = new Code();
    for ($i = 0; $i < $numberOfCodes; $i++)
    {
      $newCode = $code->createCode($lengthOfCode);
      $isAdded = $file->searchLineInFile($newCode);
      if($isAdded)
        $file->writeInFile($newCode);
    }
    $file->readFile();
    $file->downloadFile();
  }

  
}
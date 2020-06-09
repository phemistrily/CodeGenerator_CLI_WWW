<?php
namespace App\Controllers;

use App\Models;
use App;

class CodesController extends App\Controller
{
    private $generatedCodes = array();
    private const REQUIRED_FAIL_ATTEMPTS_TO_FAIL = 100000;
    private const TOO_MANY_CODES_MESSAGE = 'Nie można wyrenderować kodów, prawdopodobnie nie ma tyle przypadków';
    private const DEFAULT_FILENAME = 'kody.txt';
    private const DEFAULT_RENDER_BY = 'file';

    public function index()
    {
        //$this->view->render('views/index.phtml');
    }

    public function generateCodes($numberOfCodes,$lengthOfCode, $renderBy = self::DEFAULT_RENDER_BY, $filePath = self::DEFAULT_FILENAME)
    {
        $this->vaildParamsForGenerateCodes($numberOfCodes, $lengthOfCode);
        $code = new Models\Code();
        $pushedCodes = 0;
        $failAttempts = 0;
        while ($pushedCodes < $numberOfCodes && $failAttempts < self::REQUIRED_FAIL_ATTEMPTS_TO_FAIL) {
            $newCode = $code->createCode($lengthOfCode);
            if (!$this->containsCode($newCode)) {
                array_push($this->generatedCodes, $newCode);
                $pushedCodes++;
            }
            else
            {
                $failAttempts++;
            }
        }
        if ($failAttempts < self::REQUIRED_FAIL_ATTEMPTS_TO_FAIL) {
            $this->renderCodes($renderBy, $filePath);
        }
        else {
            $this->renderCannotFoundCodes($renderBy);
        }
    }

    private function vaildParamsForGenerateCodes($numberOfCodes, $lengthOfCode)
    {
        if (!is_numeric($numberOfCodes) || $numberOfCodes <= 0 || !is_numeric($lengthOfCode) || $lengthOfCode <= 0) {
            header('location: /index/index/paramsNotCorrect');
        }
    }

    private function containsCode($code)
    {
        return in_array($code, $this->generatedCodes);
    }

    private function renderCodes($renderBy, $filePath = null)
    {
        switch ($renderBy) {
        case 'file':
            $file = new Models\File();
            $this->writeCodesInFile($file);
            $this->generateFile($file);
            break;
        case 'cli':
            $file = new Models\File();
            $this->writeCodesInFile($file);
            $this->createFileOnServer($file, $filePath);
            break;
        default:
            break;
        }
    }

    private function renderCannotFoundCodes($renderBy)
    {
        switch ($renderBy) {
        case 'file':
            header('location: /index/index/tooManyCodes');
            break;
        case 'cli':
            echo self::TOO_MANY_CODES_MESSAGE;
            break;
        default:
            break;
        }
    }

    private function writeCodesInFile($file)
    {
        if (is_array($this->generatedCodes)) {
            $file->writeArrayInFile($this->generatedCodes);
        }
    }

    private function generateFile($file)
    {
        $file->downloadFile();
    }

    private function createFileOnServer($file, $filePath)
    {
        $file->returnToFileFirstByte();
        $file->createFileOnServer($filePath);
        echo 'file is created';
    }
}

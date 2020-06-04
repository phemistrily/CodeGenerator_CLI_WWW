<?php
class File
{
  public $file;
  public $filePath;

  public function __construct()
  {
    $this->file = tmpfile();
    $metaData = stream_get_meta_data($this->file);
    $this->filePath = $metaData['uri'];
  }

  public function searchLineInFile(String $lineData) :bool
  {
    fseek($this->file, 0);
    if ($this->file) {
        while (($line = fgets($this->file)) !== false) {
          if($line == $lineData)
            return false;
        }
    } else {
        var_dump('can not load file');
        die();
    } 
    return true;
  }

  public function writeInFile(String $line)
  {
    fwrite($this->file, $line. PHP_EOL);
  }

  public function readFile()
  {
    fseek($this->file, 0);
    echo fread($this->file, filesize($this->filePath));
  }

  public function downloadFile($name = 'kody')
  {
    header('Content-Description: File Transfer');
    header('Content-Type: text/txt');
    header("Content-Disposition: attachment; filename=$name.txt");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file->filePath));
  }

  public function __destruct()
  {
    fclose($this->file);
  }
}
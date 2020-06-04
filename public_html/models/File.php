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

  public function writeInFile(string $line)
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
    header('Content-Length: ' . filesize($this->filePath));
  }

  public function __destruct()
  {
    fclose($this->file);
  }
}
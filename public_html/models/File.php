<?php
class File
{
  public $file;
  public $filePath;

  public function __construct()
  {
    $this->file = tmpfile();
    $this->checkFileExist();
    $metaData = stream_get_meta_data($this->file);
    $this->filePath = $metaData['uri'];
  }

  private function checkFileExist()
  {
    if(!$this->file)
    {
      header('location: /index/index/fileNotCreated');
    }
  }

  public function writeArrayInFile(array $array)
  {
    file_put_contents($this->filePath, implode("\n", $array));
  }

  public function returnToFileFirstByte()
  {
    fseek($this->file, 0);
  }

  public function readFile()
  {
    $this->returnToFileFirstByte();
    echo fread($this->file, filesize($this->filePath));
  }

  public function downloadFile($name = 'kody.txt')
  {
    header('Content-Description: File Transfer');
    header('Content-Type: text/txt');
    header("Content-Disposition: attachment; filename=$name");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($this->filePath));
    flush();
    readfile($this->filePath);
  }

  public function createFileOnServer($path)
  {
    $this->checkDirectoryExist($path);
    if (!copy($this->filePath, $path)) {
      echo "failed to copy $this->filePath...\n";
    }
    echo $path;
  }

  private function checkDirectoryExist($path)
  {
    $directory = $this->getDirectoryFromPath($path);
    if (!file_exists($directory)) {
      echo 'cannot save in this directory';
    }
  }

  private function getDirectoryFromPath($path)
  {
    $path = pathinfo($path);
    return $path['dirname'];
  }

  public function __destruct()
  {
    fclose($this->file);
  }
}
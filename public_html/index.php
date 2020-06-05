<?php
require_once('libs/Boot.php');
if(isset($argc) && $argc > 1)
{
  new Boot($argc, $argv);
}
else
{
  new Boot();
}
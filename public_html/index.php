<?php
require_once '../vendor/autoload.php';
set_time_limit(0);
if (isset($argc) && $argc > 1) {
    new App\Boot($argc, $argv);
} else {
    new App\Boot();
}

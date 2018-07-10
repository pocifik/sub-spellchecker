<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function __autoload($class)
{
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
    if (file_exists($file)) {
        require $file;
    }
}

require __DIR__ . '/core/Core.php';

$core = new core\Core();
$core->init();
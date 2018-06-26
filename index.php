<?php

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
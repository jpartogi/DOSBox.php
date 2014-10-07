<?php

spl_autoload_register(function ($class_name) {
    $class_name =  str_replace('\\', '/', $class_name);
    $file_name =  'app/' . $class_name . '.php';
    if (file_exists($file_name)) {
        require_once $file_name;
    }
});

require_once 'tests/DOSBox/DOSBoxTestCase.php';
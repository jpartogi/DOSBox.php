<?php

spl_autoload_register(function ($class_name) {
    $class_name =  str_replace('\\', '/', $class_name);
    require_once  __DIR__ . '/app/' . $class_name . '.php';
});

require_once __DIR__ . '/tests/DOSBox/DOSBoxTestCase.php';
<?php

spl_autoload_register(function ($class_name) {
    $class_name =  str_replace('\\', '/', $class_name);
    require_once  'app/' . $class_name . '.php';
});

require_once 'tests/DOSBox/DOSBoxTestCase.php';
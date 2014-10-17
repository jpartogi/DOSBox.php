<?php

spl_autoload_register(function ($class_name) {
    $class_name =  str_replace('\\', '/', $class_name);
    require_once  __DIR__ . '/app/' . $class_name . '.php';
});

// $line = trim(fgets(STDIN)); // reads one line from STDIN

use DOSBox\System\Configurator as Configurator;

$config = new Configurator();
$config->configureSystem();
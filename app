<?php

use DOSBox\Configuration\Configurator as Configurator;

spl_autoload_register(function ($class_name) {
    require  __DIR__ . '/' . $class_name . '.php';
});

// $line = trim(fgets(STDIN)); // reads one line from STDIN

$config = new Configurator();
$config->configurateSystem();
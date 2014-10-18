<?php

namespace DOSBox\System;

use DOSBox\Filesystem\Drive as Drive;
use DOSBox\Command\CommandFactory as CommandFactory;
use DOSBox\Command\CommandInvoker as CommandInvoker;
use DOSBox\Console\Console;
use DOSBox\Console\ConsoleOutputter;

class Configurator {
    public function configureSystem() {
        $drive = new Drive("C");
        $drive->restore();

        $factory = new CommandFactory($drive);
        $commandInvoker = new CommandInvoker();
        $commandInvoker->setCommands($factory->getCommands());

        $outputter = new ConsoleOutputter();
        $console = new Console($commandInvoker, $drive, $outputter);

        $console->processInput();
    }
}
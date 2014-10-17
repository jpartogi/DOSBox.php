<?php

namespace DOSBox\System;

use DOSBox\Filesystem\Drive as Drive;
use DOSBox\Command\CommandFactory as CommandFactory;
use DOSBox\Command\CommandInvoker as CommandInvoker;
use DOSBox\Console\Console;

class Configurator {
    public function configureSystem() {
        $drive = new Drive("C");
        $drive->restore();

        $factory = new CommandFactory($drive);
        $commandInvoker = new CommandInvoker();
        $commandInvoker->setCommands($factory->getCommandList());

        $console = new Console($commandInvoker, $drive);

        $console->processInput();
    }
}
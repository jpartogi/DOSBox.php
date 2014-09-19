<?php

namespace DOSBox\Interfaces;

use DOSBox\Interfaces\IOutputter;

interface IExecuteCommand {
    public function executeCommand($command, IOutputter $outputter);
}
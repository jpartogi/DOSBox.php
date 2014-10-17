<?php

use DOSBox\Command\CommandInvoker;
use DOSBox\Console\MockOutputter;

class DOSBoxTestCase extends PHPUnit_Framework_TestCase {
    protected $commandInvoker;
    protected $mockOutputter;

    protected function setUp() {
        $this->mockOutputter = new MockOutputter();
        $this->commandInvoker = new CommandInvoker();
    }

    protected function executeCommand($commandLine) {
        $this->commandInvoker->executeCommand($commandLine, $this->mockOutputter);
    }
}
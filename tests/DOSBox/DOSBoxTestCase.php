<?php

use DOSBox\Invoker\CommandInvoker;
use DOSBOx\Console\MockOutputter;

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
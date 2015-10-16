<?php

require_once 'bootstrap.php';

use DOSBox\Command\CommandInvoker;
use DOSBox\Console\MockOutputter;

class BaseContext {
  protected function setUp(){
        $this->mockOutputter = new MockOutputter();
        $this->commandInvoker = new CommandInvoker();
  }
  
  protected function executeCommand($commandLine) {
        $this->commandInvoker->executeCommand($commandLine, $this->mockOutputter);
  }
}
<?php

use DOSBox\Command\CommandInvoker;
use DOSBox\Command\Library\CmdMock;

class CommandInvokerTest extends DOSBoxTestCase {
    protected function setUp() {
    	parent::setUp();
    	$this->drive = $this->getMock('DOSBox\Interfaces\IDrive');
    	$this->outputter = $this->getMock('DOSBox\Interfaces\IOutputter');
    	$this->command = new CmdMock("test", $this->drive);
    	
    	$commands = array($this->command);

    	$this->invoker = new CommandInvoker();
		$this->invoker->setCommands($commands);
    }

    public function testGetCommands(){
    	$this->assertCount(1, $this->invoker->getCommands());
    }

    public function testParseEmptyStringCommand(){
    	$this->assertEquals($this->invoker->parseCommandName(""), "");
    }

    public function testParseOnlyCommandName(){
    	$this->assertEquals($this->invoker->parseCommandName("dir"), "dir");
    }

    public function testParseUpperCaseCommandName(){
    	$this->assertEquals($this->invoker->parseCommandName("DIR"), "dir");
    }

    public function testParseCommandNameWithOneParam(){
    	$this->assertEquals($this->invoker->parseCommandName("dir param1"), "dir");
    }

    public function testParseCommandNameWithComma(){
    	$this->assertEquals($this->invoker->parseCommandName("dir,param1, param2"), "dir");
    }

    public function testParseCommandNameEndsWithComma(){
    	$this->assertEquals($this->invoker->parseCommandName("dir,"), "dir");
    }

    public function testParseCommandNameEndsWithWhiteSpaces(){
    	$this->assertEquals($this->invoker->parseCommandName("dir    "), "dir");
    }

    public function testParseCommandNameWithSingleLetterParam(){
    	$this->assertEquals($this->invoker->parseCommandName("dir o"), "dir");
    }

    public function testExtractCommandParams(){
    	$this->assertEquals($this->invoker->extractCommandParams("dir w o"), "w o");
    }

    public function testParseCommandNoParams(){
    	$params = $this->invoker->parseCommandParams("dir");
    	$this->assertCount(0, $params);
    }


    public function testParseCommandTwoParamsWithSeveralSpaces(){
    	$params = $this->invoker->parseCommandParams("dir    /w     param1  ");
		$this->assertCount(2, $params);
		$this->assertEquals("/w", $params[0]);
		$this->assertEquals("param1", $params[1]);
    }

    public function testExecuteCommand() {
    	$this->invoker->executeCommand("test", $this->outputter );
    	$this->assertTrue($this->command->executed);
    }

    public function testExecuteUnavailableCommand(){
        $this->invoker->executeCommand("bar", $this->outputter);
        $this->assertFalse($this->command->executed);
    }
}
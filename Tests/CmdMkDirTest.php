<?php

require_once 'DOSBoxTestCase.php';

use DOSBox\Filesystem\Directory;
use DOSBox\Command\Library\CmdMkDir;
use DOSBox\Filesystem\Drive;

class CmdMkDirTest extends DOSBoxTestCase 
{
	private $command;
	private $drive;

	protected function setUp()
    {    	
    	parent::setUp();
    	$this->drive = new Drive("C");
    	$this->command = new CmdMkDir("mkdir", $this->drive);
    }

    public function testCreateDirectory(){
    	$this->command->createDirectory("subdir", $this->drive);

    	$this->assertCount(1, $this->drive->getCurrentDirectory()->getContent());
    }
}
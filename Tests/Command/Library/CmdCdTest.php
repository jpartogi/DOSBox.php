<?php

namespace Tests\Command\Library;

use Tests\DOSBoxTestCase;

use DOSBox\Filesystem\Directory;
use DOSBox\Filesystem\File;
use DOSBox\Command\Library\CmdCd;
use DOSBox\Filesystem\Drive;

class CmdCdTest extends DOSBoxTestCase {
    private $command;
    private $drive;
    private $rootDir;
    private $subDir1;

    protected function setUp() {
        parent::setUp();
        $this->drive = new Drive("C");
        $this->rootDir = $this->drive->getRootDir();
        // C:\subdir1
        $this->subDir1 = new Directory("subdir1");
        $this->rootDir->add($this->subDir1);

        $this->command = new CmdCd("cd", $this->drive);
        $this->commandInvoker->addCommand($this->command);
    }

    public function testCmdCd_ChangeToSubdirectory_ChangesDirectory() {
        $this->executeCommand("cd " . $this->subDir1->getPath());
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());
    }

    public function testCmdCd_ChangeToSubDirectoryWithEndingBacklash_ChangesDirectory(){
        $this->executeCommand("cd " . $this->subDir1->getPath() . "\\");
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());
    }

    public function testCmdCd_WithBacklash_ChangesToRoot(){
        $this->drive->changeCurrentDirectory($this->subDir1);

        $this->executeCommand("cd \\");
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->rootDir, $this->drive->getRootDirectory());
    }

    public function testCmdCd_WithPointPoint_ChangesToParent(){
        $this->drive->changeCurrentDirectory($this->subDir1);

        $this->executeCommand("cd ..");
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->rootDir, $this->drive->getRootDirectory());
    }

    public function testCmdCd_WithPointPointInRootDir_RemainsInRootDir(){
        $this->drive->changeCurrentDirectory($this->rootDir);

        $this->executeCommand("cd ..");
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->rootDir, $this->drive->getRootDirectory());
    }

    public function testCmdCd_WithPoint_RemainsInCurrentDirectory(){
        $this->drive->changeCurrentDirectory($this->subDir1);

        $this->executeCommand("cd .");
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());
    }
} 
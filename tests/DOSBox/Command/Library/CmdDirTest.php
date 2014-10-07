<?php

use DOSBox\Filesystem\Directory;
use DOSBox\Filesystem\File;
use DOSBox\Command\Library\CmdDir;
use DOSBox\Filesystem\Drive;

class CmdDirTest extends DOSBoxTestCase {
    private $command;
    private $drive;
    private $rootDir;
    private $subDir1;
    private $subDir2;
    private $file1;
    private $file2InDir1;

    protected function setUp() {
        parent::setUp();
        $this->drive = new Drive("C");
        $this->rootDir = $this->drive->getRootDir();
        // C:\subdir1
        $this->subDir1 = new Directory("subdir1");
        $this->rootDir->add($this->subDir1);

        // C:\subdir1\file1.txt
        $this->file1InDir1 = new File("file1.txt", "");
        $this->subDir1->add($this->file1InDir1);
        // C:\subdir1\file2.txt
        $this->file2InDir1 = new File("file2.txt", "");
        $this->subDir1->add($this->file2InDir1);

        // C:\file1.txt
        $this->file1 = new File("file1.txt", "");
        $this->rootDir->add($this->file1);

        $this->subDir2 = new Directory("subdir2");
        $this->rootDir->add($this->subDir2);

        $this->command = new CmdDir("dir", $this->drive);
        $this->commandInvoker->addCommand($this->command);
    }

    public function testCmdDir_WithoutParameter_PrintPathOfCurrentDirectory() {
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir");
        $this->assertContains($this->rootDir->getPath(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_WithoutParameter_PrintFiles(){
        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->executeCommand("dir");
        $this->assertContains($this->file1InDir1->getName(), $this->mockOutputter->getOutput());
        $this->assertContains($this->file2InDir1->getName(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_WithoutParameter_PrintDirectories(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir");
        $this->assertContains($this->subDir1->getName(), $this->mockOutputter->getOutput());
        $this->assertContains($this->subDir2->getName(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_WithoutParameter_PrintsFooter(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir");
        $this->assertContains("1 File(s)", $this->mockOutputter->getOutput());
        $this->assertContains("2 Dir(s)", $this->mockOutputter->getOutput());
    }

    public function testCmdDir_PathAsParameter_PrintGivenPath(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir c:\\subDir1");
        $this->assertContains($this->subDir1->getPath(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_PathAsParameter_PrintFilesInGivenPath(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir c:\\subdir1");
        $this->assertContains($this->file2InDir1->getName(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_PathAsParameter_PrintsFooter(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir c:\\subdir1");
        $this->assertContains("2 File(s)", $this->mockOutputter->getOutput());
        $this->assertContains("0 Dir(s)", $this->mockOutputter->getOutput());
    }

    public function testCmdDir_FileAsParameter_PrintFilesInGivenPath(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir " . $this->file1InDir1->getPath());
        $this->assertContains($this->file1InDir1->getName(), $this->mockOutputter->getOutput());
        $this->assertContains($this->file2InDir1->getName(), $this->mockOutputter->getOutput());
    }

    public function testCmdDir_FileAsParameter_PrintsFooter(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("dir " . $this->file2InDir1->getPath());
        $this->assertContains("2 File(s)", $this->mockOutputter->getOutput());
        $this->assertContains("0 Dir(s)", $this->mockOutputter->getOutput());
    }

    public function testCmdDir_NotExistingDirectory_PrintsError(){
        $this->executeCommand("dir NonExistingDirectory");
        $this->assertContains("File Not Found", $this->mockOutputter->getOutput());
    }

    public function testCmdDir_AllParametersAreReset(){
        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->executeCommand("dir c:\\subDir2");
        $this->assertContains($this->subDir2->getPath(), $this->mockOutputter->getOutput());
        $this->mockOutputter->_empty();
        $this->executeCommand("dir");
        $this->assertContains($this->subDir1->getPath(), $this->mockOutputter->getOutput());
    }
} 
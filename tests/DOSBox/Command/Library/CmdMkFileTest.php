<?php
//namespace Tests\Command\Library;

//use Tests\DOSBoxTestCase;

use DOSBox\Filesystem\Directory;
use DOSBox\Command\Library\CmdMkFile;
use DOSBox\Filesystem\Drive;

class CmdMkFileTest extends DOSBoxTestCase {
    private $command;
    private $drive;
    private $rootDir;
    private $numbersOfFilesBeforeTest;

    protected function setUp() {
        parent::setUp();
        $this->drive = new Drive("C");
        $this->command = new CmdMkFile("mkfile", $this->drive);
        $this->rootDir = $this->drive->getRootDir();

        $this->commandInvoker->addCommand($this->command);

        $this->numbersOfFilesBeforeTest = $this->drive->getRootDirectory()->getNumberOfContainedFiles();
    }

    public function testCmdMkFile_WithoutContent_CreatesEmptyFile() {
        // To be implemented
    }

    public function testCmdMkFile_WithContent_CreatesFileWithContent() {
        // given
        $newFileName = "testFile";
        $newFileContent = "ThisIsTheContent";

        // when
        $this->executeCommand("mkfile " . $newFileName . " " . $newFileContent);

        // then
        // 1. File is added
        $this->assertEquals($this->numbersOfFilesBeforeTest + 1, $this->drive->getCurrentDirectory()->getNumberOfContainedFiles());

        // 2. No error is found in console
        $this->assertNotNull($this->mockOutputter);
        $this->assertEmpty($this->mockOutputter->getOutput());

        // 3. File has content
        $createdFile = $this->drive->getItemFromPath( $this->drive->getCurrentDirectory()->getPath() . "\\" . $newFileName);
        $this->assertEquals($newFileContent, $createdFile->getFileContent());
    }

    public function testCmdMkFile_NoParameters_ReportsError(){
        // To be implemented
    }

} 
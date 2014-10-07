<?php

use DOSBox\Filesystem\Directory;
use DOSBox\Command\Library\CmdMkDir;
use DOSBox\Filesystem\Drive;

class CmdMkDirTest extends DOSBoxTestCase {
	private $command;
	private $drive;
    private $numbersOfDirectoriesBeforeTest;

	protected function setUp() {
    	parent::setUp();
    	$this->drive = new Drive("C");
    	$this->command = new CmdMkDir("mkdir", $this->drive);

        $this->commandInvoker->addCommand($this->command);

        $this->numbersOfDirectoriesBeforeTest = $this->drive->getRootDirectory()->getNumberOfContainedDirectories();
    }

    public function testCreateDirectory(){
    	$this->command->createDirectory("subdir", $this->drive);

    	$this->assertCount(1, $this->drive->getCurrentDirectory()->getContent());
    }

    public function testCmdMkDir_CreateNewDirectory_NewDirectoryIsAdded() {
        $testDirName = "test1";
        $this->executeCommand("mkdir " . $testDirName);

        $testDirectory = $this->drive->getItemFromPath( $this->drive->getDriveLetter() . '\\' . $testDirName );
        $this->assertSame($this->drive->getRootDirectory(), $testDirectory->getParent());
        $this->assertEquals($this->numbersOfDirectoriesBeforeTest + 1, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());
        $this->assertNotNull($this->mockOutputter);
        $this->assertEmpty($this->mockOutputter->getOutput());
    }

    public function testCmdMkDir_SingleLetterDirectory_NewDirectoryIsAdded(){
        $testDirName = "a";
        $this->executeCommand("mkdir " . $testDirName);
        $testDirectory = $this->drive->getItemFromPath( $this->drive->getDriveLetter() . '\\' . $testDirName );
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->drive->getRootDirectory(), $testDirectory->getParent());
    }

    public function CmdMkDir_NoParameters_ErrorMessagePrinted() {
        $this->executeCommand("mkdir");
        $this->assertEquals($this->numbersOfDirectoriesBeforeTest, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());
        $this->assertContains("syntax of the command is incorrect", $this->mockOutputter->getOutput());
    }

    public function testCmdMkDir_ParameterContainsBacklash_ErrorMessagePrinted(){
        $this->executeCommand("mkdir c:\\test1");
        $this->assertContains(CmdMkDir::PARAMETER_CONTAINS_BACKLASH, $this->mockOutputter->getOutput());
    }

    public function testCmdMkDir_ParameterContainsBacklash_NoDirectoryCreated() {
        $this->executeCommand("mkdir c:\\test1");
        $this->assertEquals($this->numbersOfDirectoriesBeforeTest, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());
        $this->assertContains(CmdMkDir::PARAMETER_CONTAINS_BACKLASH, $this->mockOutputter->getOutput());
    }

    public function testCmdMkDir_SeveralParameters_SeveralNewDirectoriesCreated() {
        $testDirName1 = "test1";
        $testDirName2 = "test2";
        $testDirName3 = "test3";

        $this->executeCommand("mkdir " . $testDirName1 . " " . $testDirName2 . " " . $testDirName3);

        $testDirectory1 = $this->drive->getItemFromPath( $this->drive->getDriveLetter() . '\\' . $testDirName1 );
        $testDirectory2 = $this->drive->getItemFromPath( $this->drive->getDriveLetter() . '\\' . $testDirName2 );
        $testDirectory3 = $this->drive->getItemFromPath( $this->drive->getDriveLetter() . '\\' . $testDirName3 );

        $this->assertSame($testDirectory1->getParent(), $this->drive->getRootDirectory());
        $this->assertSame($testDirectory2->getParent(), $this->drive->getRootDirectory());
        $this->assertSame($testDirectory3->getParent(), $this->drive->getRootDirectory());

        $this->assertEquals($this->numbersOfDirectoriesBeforeTest + 3, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());

        $this->assertEmpty($this->mockOutputter->getOutput());
    }
/*
    public function testCmdMkDir_AllParametersAreReset() {
        $testDirName = "test1";
        $this->executeCommand("mkdir " + $testDirName);
        $this->assertEquals($this->numbersOfDirectoriesBeforeTest + 1, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());

        $this->executeCommand("mkdir");
        $this->assertEquals($this->numbersOfDirectoriesBeforeTest + 1, $this->drive->getRootDirectory()->getNumberOfContainedDirectories());
        $this->assertContains("The syntax of the command is incorrect", $this->mockOutputter->getOutput());
    }*/
}
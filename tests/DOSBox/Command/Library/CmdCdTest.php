<?php

use DOSBox\Filesystem\Directory;
use DOSBox\Filesystem\File;
use DOSBox\Command\Library\CmdCd;
use DOSBox\Filesystem\Drive;

class CmdCdTest extends DOSBoxTestCase {
    private $command;
    private $drive;
    private $rootDir;
    private $subDir1;
    private $subDir2;
    private $file1InDir1;

    protected function setUp() {
        parent::setUp();
        $this->drive = new Drive("C");
        $this->rootDir = $this->drive->getRootDir();
        // C:\subdir1
        $this->subDir1 = new Directory("subdir1");
        $this->rootDir->add($this->subDir1);
        $this->file1InDir1 = new File("File1InDir1", "");
        $this->subDir1->add($this->file1InDir1);

        $this->subDir2 = new Directory("subdir2");
        $this->rootDir->add($this->subDir2);

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

    public function testCmdCd_WithoutParameter_PrintsCurrentDirectory(){
        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->executeCommand("cd");
        $this->assertContains($this->subDir1->getPath(), $this->mockOutputter->getOutput());
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());
    }

    public function testCmdCd_WithInvalidAbsolutePath_RemainsInCurrentDirectory(){
        $this->drive->changeCurrentDirectory($this->subDir2);
        $this->executeCommand("cd c:\\gaga\\gugus");
        $this->assertSame($this->subDir2, $this->drive->getCurrentDirectory());
        $this->assertContains(CmdCd::SYSTEM_CANNOT_FIND_THE_PATH_SPECIFIED, $this->mockOutputter->getOutput());
    }

    public function testCmdCd_WithFileAsPath_RemainsInCurrentDirectory(){
        $this->drive->changeCurrentDirectory($this->subDir2);
        $this->executeCommand("cd " . $this->file1InDir1->getPath());
        $this->assertSame($this->subDir2, $this->drive->getCurrentDirectory());
        $this->assertContains(CmdCd::DESTINATION_IS_FILE, $this->mockOutputter->getOutput());
    }

    public function testCmdCd_WithRelativePath_ChangesDirectory(){
        $this->executeCommand("cd " . $this->subDir1->getName());
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());
    }

    public function testCmdCd_WithSingleLetterDirectory_ChangesDirectory(){
        $directoryWithSingleLetter = new Directory("a");
        $this->rootDir->add($directoryWithSingleLetter);
        $this->drive->changeCurrentDirectory($this->rootDir);

        $this->executeCommand("cd " . $directoryWithSingleLetter->getName());
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($directoryWithSingleLetter, $this->drive->getCurrentDirectory());
    }

    public function testCmdCd_ChangeToNotExistingDirectory_PrintsError(){
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->executeCommand("cd NonExistingDirectory");
        $this->assertSame($this->rootDir, $this->drive->getCurrentDirectory());
        $this->assertContains(CmdCd::SYSTEM_CANNOT_FIND_THE_PATH_SPECIFIED, $this->mockOutputter->getOutput());
    }

    public function testCmdCd_AllParametersAreReset() {
        $this->executeCommand("cd " . $this->subDir1->getPath());
        $this->assertEmpty($this->mockOutputter->getOutput()); // success
        $this->assertSame($this->subDir1, $this->drive->getCurrentDirectory());

        $this->executeCommand("cd");
        $this->assertContains($this->subDir1->getPath(), $this->mockOutputter->getOutput());
    }

} 
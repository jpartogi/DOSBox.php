<?php

require_once 'BaseContext.php';

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

use DOSBox\Filesystem\Directory;
use DOSBox\Filesystem\File;
use DOSBox\Command\Library\CmdCd;
use DOSBox\Filesystem\Drive;

class CmdCdContext extends BaseContext implements Context
{  
    /**
     * @Given /^drive C have files and dirs$/
     */
    public function driveCHaveFilesAndDirs()
    {
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

    /**
     * @When /^I change to sub directory$/
     */
    public function changeToSubDirectory()
    {
        $this->executeCommand("cd " . $this->subDir1->getPath());
    }

    /**
     * @Then /^I should be in the sub directory$/
     */
    public function itShouldBeInSubDirectory()
    {
      PHPUnit_Framework_Assert::assertEmpty($this->mockOutputter->getOutput());
      PHPUnit_Framework_Assert::assertSame($this->subDir1, $this->drive->getCurrentDirectory()); 
    }

}
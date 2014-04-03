<?php

require_once 'DOSBoxTestCase.php';

use DOSBox\Filesystem\Drive;
use DOSBox\Filesystem\Directory;

class DriveTest extends DOSBoxTestCase 
{
	private $drive;
	private $rootDir;
	private $subDir1;
	private $subDir2;

    protected function setUp()
    {
    	parent::setUp();
    	$this->drive = new Drive("C");
    	$this->rootDir = $this->drive->getRootDir();
    	$this->subDir1 = new Directory("subdir1");
    	$this->rootDir->add( $this->subDir1 );

    	$this->subDir2 = new Directory("subdir2");
    	$this->subDir1->add($this->subDir2);
    }

    public function testGetItemFromDirectory()
    {
    	$item = $this->drive->getItemFromDirectory("C:\subdir1\subdir2", $this->subDir1);
    	$this->assertSame($this->subDir2, $item);
    }

    public function testPatchGivenItem() {
    	$this->assertEquals("C:\\", $this->drive->patchGivenItem("C:/"));
    }

    public function testGetItemFromPath(){
        // Go to subdir1
        $this->drive->changeCurrentDirectory($this->subDir1);
        // Go to subdir2
        $this->drive->changeCurrentDirectory($this->subDir2);

        $item = $this->drive->getItemFromPath(".");
        
        $this->assertSame($this->subDir2, $item);

        // Go back to parent directory
        $item = $this->drive->getItemFromPath("..");
        
        $this->assertSame($this->subDir1, $item);
    }
}
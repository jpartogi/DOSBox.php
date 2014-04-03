<?php

require_once 'DOSBoxTestCase.php';

use DOSBox\Filesystem\Directory;

class DirectoryTest extends DOSBoxTestCase 
{
	private $rootDir;
	private $subDir1;
	private $subDir2;

    protected function setUp()
    {
    	parent::setUp();
    	$this->rootDir = new Directory("root");
    	$this->subDir1 = new Directory("subdir1");
    	$this->subDir2 = new Directory("subdir2");
    }

    public function testAddDir(){
    	$this->rootDir->add( $this->subDir1 );
    	$this->assertCount(1, $this->rootDir->getContent());
    	$this->assertEquals($this->rootDir, $this->subDir1->getParent());
    }

    public function testRemoveParent() {
    	$this->subDir2->setParent($this->rootDir);
    	$this->assertEquals($this->rootDir, $this->subDir2->getParent());

    	$this->subDir2->removeParent($this->subDir2);
    }	
}
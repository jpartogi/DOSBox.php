<?php

//namespace Tests\Filesystem;

//use Tests\DOSBoxTestCase;
use DOSBox\Filesystem\Directory;

class DirectoryTest extends DOSBoxTestCase {
	private $rootDir;
	private $subDir1;
	private $subDir2;

    protected function setUp() {
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

    public function testSubdirectoryParentIsSetCorrectly1() {
        $this->rootDir->add( $this->subDir1 );

        $item = $this->rootDir->getContent()[0];
        $parent = $item->getParent();
        $this->assertNotNull($parent);

        $path = $item->getPath();
        $this->assertTrue( strcmp( $path, $this->rootDir->getName() . "\\" . $this->subDir1->getName()) == 0);

    }

    public function testRename() {
        $this->subDir1->setName("NewName");
        $this->assertTrue( strcmp( $this->subDir1->getName(), "NewName") == 0);
    }

    public function testNumberOfFilesAndDirectories() {
        // TODO
    }
}
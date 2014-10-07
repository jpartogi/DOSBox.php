<?php

//namespace Tests\Filesystem;

//use Tests\DOSBoxTestCase;

use DOSBox\Filesystem\Drive;
use DOSBox\Filesystem\Directory;
use DOSBox\Filesystem\File;

class DriveTest extends DOSBoxTestCase {
	private $drive;
	private $rootDir;
	private $subDir1;
	private $subDir2;
    private $subSubDir1;
    private $file1InRootDir;
    private $file2InSubDir1;

    protected function setUp() {
    	parent::setUp();
    	$this->drive = new Drive("C");
    	$this->rootDir = $this->drive->getRootDir();

        $this->file1InRootDir = new File("file1InRootDir", "test");
        $this->rootDir->add( $this->file1InRootDir );

        // C:\subdir1
    	$this->subDir1 = new Directory("subdir1");
    	$this->rootDir->add( $this->subDir1 );

        $this->file2InSubDir1 = new File("file1InSubDir1", "test");
        $this->subDir1->add( $this->file2InSubDir1 );

        // C:\subdir2
    	$this->subDir2 = new Directory("subdir2");
    	$this->rootDir->add( $this->subDir2);

        // C:\subdir1\subsubdir1
        $this->subSubDir1 = new Directory("subsubdir1");
        $this->subDir1->add( $this->subSubDir1 );
    }

    public function testGetItemFromDirectory() {
    	$item = $this->drive->getItemFromDirectory("C:\subdir1\subsubdir1", $this->subDir1);
    	$this->assertSame($this->subSubDir1, $item);
    }

    public function testPatchGivenItem() {
    	$this->assertEquals("C:\\", $this->drive->patchGivenItem("C:/"));
    }

    public function testGetItemFromPath(){
        // Go to subdir1
        $this->drive->changeCurrentDirectory($this->subDir1);

        // Go to subsubdir1
        $this->drive->changeCurrentDirectory($this->subSubDir1);

        $item = $this->drive->getItemFromPath(".");
        
        $this->assertSame($this->subSubDir1, $item);

        // Go back to parent directory
        $item = $this->drive->getItemFromPath("..");
        
        $this->assertSame($this->subDir1, $item);
    }

    public function testCurrentDirectory() {
        $this->assertTrue( strcmp($this->drive->getCurrentDirectory()->getName(), "C:") == 0 );

        $subDir = new Directory("subDir");
        $this->drive->getRootDirectory()->add($subDir);
        $this->drive->changeCurrentDirectory($subDir);

        $this->assertSame($this->drive->getCurrentDirectory(), $subDir);
    }

    public function testGetItemFromPathWithAbsolutePaths() {
        $testpath = $this->rootDir->getPath();
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->rootDir);

        $testpath = $this->subDir1->getPath();
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->subDir1);

        $testpath = $this->subDir2->getPath();
        $testpath = str_replace('\\', '/', $testpath);
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->subDir2);

        $testpath = $this->file2InSubDir1->getPath();
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->file2InSubDir1);

        $testpath = $this->file1InRootDir->getPath();
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->file1InRootDir);

        $testpath = "g:\\gaga\\gugus";
        $this->assertTrue($this->drive->getItemFromPath($testpath) == null);

        $testpath = "\\" . $this->subDir1->getName();
        $this->assertSame($this->drive->getItemFromPath($testpath), $this->subDir1);

        $this->assertSame($this->drive->getItemFromPath("C:\\subDir1"), $this->subDir1);
        $this->assertSame($this->drive->getItemFromPath("c:\\subDir1"), $this->subDir1);
        $this->assertSame($this->drive->getItemFromPath("c:/subDir1"), $this->subDir1);
    }

    public function testGetItemFromPathWithRelativePaths() {
        $subSubDirName = "subsubdir1";

        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->assertSame($this->drive->getItemFromPath($subSubDirName), $this->subSubDir1);
    }

    public function testGetItemFromPathWithSpecialPaths() {
        // Path "\"
        $this->assertSame($this->drive->getItemFromPath("\\"), $this->drive->getRootDirectory());

        // Path ".."
        $this->drive->changeCurrentDirectory($this->subSubDir1);
        $this->assertSame($this->drive->getItemFromPath(".."), $this->subSubDir1->getParent());

        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->assertSame($this->drive->getItemFromPath(".."), $this->rootDir);

        // Path "."
        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->assertSame($this->drive->getItemFromPath("."), $this->subDir1);

        // Path ".\"
        $this->drive->changeCurrentDirectory($this->subDir1);
        $this->assertSame($this->drive->getItemFromPath(".\\"), $this->subDir1);

        // Path ".\subDir2"
        $this->drive->changeCurrentDirectory($this->rootDir);
        $this->assertSame($this->drive->getItemFromPath(".\\subDir2"), $this->subDir2);

        // Path "..\subDir1"
        $this->drive->changeCurrentDirectory($this->subDir2);
        $this->assertSame($this->drive->getItemFromPath("..\\subDir1"), $this->subDir1);

        // Path ".\..\subDir1"
        $this->drive->changeCurrentDirectory($this->subDir2);
        $this->assertSame($this->drive->getItemFromPath(".\\..\\subDir1"), $this->subDir1);
    }

    public function testSingleCharacterDirectories() {
        $this->assertTrue($this->rootDir->getNumberOfContainedDirectories() == 2);

        $newDir = new Directory("N");
        $this->rootDir->add($newDir);
        $this->assertTrue($this->rootDir->getNumberOfContainedDirectories() == 3);

        $newDirPath = $this->rootDir->getPath() . "\\N" ;

        $item = $this->drive->getItemFromPath($newDirPath);
        $this->assertNotNull($item);
        $this->assertTrue($item->isDirectory() == true);
        $this->assertSame($item, $newDir);
    }
}
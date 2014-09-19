<?php

namespace DOSBox\Filesystem;

use DOSBox\Filesystem\FileSystemItem;

class Directory extends FileSystemItem {
    private $content; // FileSystemItem

    public function __construct($name){
        parent::__construct($name, NULL);
        $this->content = array();
    }

    // Adding File and Directory using the same method.
    public function add(FileSystemItem $fileSystemItemToAdd){
        array_push($this->content, $fileSystemItemToAdd);
        if(!$this->hasAnotherParent($fileSystemItemToAdd)){
            $this->removeParent($fileSystemItemToAdd);
        }
        $fileSystemItemToAdd->setParent($this);
    }

    public function hasAnotherParent(FileSystemItem $fileSystemItem){
        return is_null($fileSystemItem->getParent());
    }

    // TODO: Unit test this
    public function removeParent(FileSystemItem $fileSystemItemToAdd){
        //$result = array_diff($fileSystemItemToAdd->getParent()->content, array($fileSystemItemToAdd));
        $fileSystemItemToAdd->setParent(NULL);
    }

    public function remove(FileSystemItem $fileSystemItem) {
        if(in_array($fileSystemItem, $this->content)) {
            $fileSystemItem->setParent(null);
            $this->removeContent($fileSystemItem);
        }
    }

    public function getContent(){
        return $this->content;
    }

    public function isDirectory() {
        return true;
    }

    public function getNumberOfContainedFiles() {
        $numberOfFiles = 0;
        foreach($this->content as $item) {
            if(!$item->isDirectory()) {
                $numberOfFiles++;
            }
        }
        return $numberOfFiles;
    }

    public function getNumberOfContainedDirectories() {
        $numberOfDirs = 0;
        foreach($this->content as $item) {
            if($item->isDirectory() == true) {
                $numberOfDirs++;
            }
        }
        return $numberOfDirs;
    }

    public function getSize(){
        return 0;
    }

    // TODO: Unit test this
    public function removeContent($item){
        $key = array_search($item, $this->content);
        if($key!==false){
            unset($this->content[$key]);
        }
    }
}
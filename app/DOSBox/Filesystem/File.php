<?php

namespace DOSBox\Filesystem;

use DOSBox\Filesystem\FileSystemItem;

class File extends FileSystemItem {
    private $content;

    public function __construct($name, $content){
        parent::__construct($name, NULL);
        $this->content = $content;
    }

    public function getFileContent() {
        return $this->content;
    }

    public function isDirectory() {
        return false;
    }

    public function getSize() {
        return strlen($this->content);
    }

    public function getNumberOfContainedFiles() {
        return 0;  // A file does not contain any other files
    }

    public function getNumberOfContainedDirectories() {
        return 0;  // A file does not contain any sub-directories
    }
}
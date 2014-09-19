<?php

namespace DOSBox\Filesystem;

use DOSBox\Interfaces\IDrive;
use DOSBox\Filesystem\Directory;

class Drive implements IDrive{
    private $driveLetter;
    private $label;
    private $rootDir;
    private $currentDir;

    public function __construct($driveLetter) {
        $this->driveLetter = strtoupper(substr($driveLetter,0,1));
        $this->label = "";
        $this->rootDir = new Directory($this->driveLetter . ':');
        $this->currentDir = $this->rootDir;
    }

    public function getLabel(){
        return $this->getLabel();
    }

    public function setLabel($label){
        $this->label = $label;
    }

    public function getRootDirectory(){
        return $this->rootDir;
    }

    public function save(){
        // Not yet implemented
    }

    public function createFromRealDirectory($path) {
        // Not yet implemented
    }

    public function restore(){
        // Not yet implemented
    }

    public function getDriveLetter(){
        return $this->driveLetter . ':';
    }

    public function getPrompt(){
        return $this->currentDir->getPath() . '\> ';
    }

    public function getCurrentDirectory(){
        return $this->currentDir;
    }

    public function getRootDir(){
        return $this->rootDir;
    }

    public function getItemFromPath($givenItemPath) {
        $givenItemPathPatched = $this->patchGivenItem($givenItemPath);

        // Remove ending "\"
        $givenItemPathPatched = trim($givenItemPathPatched);

        if( substr($givenItemPathPatched, strlen($givenItemPathPatched) - 1) == '\\'
            && strlen($givenItemPathPatched) >= 2) {
            $givenItemPathPatched = substr($givenItemPathPatched, 0, strlen($givenItemPathPatched)-1);
        }

        // Test for special paths
        if(strcasecmp($givenItemPathPatched, "\\") == 0) {
            return $this->getRootDirectory();
        }

        if(strcasecmp($givenItemPathPatched, '..') == 0) {
            $parent = $this->getCurrentDirectory()->getParent();
            if($parent == null) {  // Case if current directory is already root
                $parent = $this->getRootDirectory();
            }
            return $parent;
        }

        if(strcasecmp($givenItemPathPatched, '.') == 0) {
            return $this->getCurrentDirectory();
        }

        // Check for .\
        if(strlen($givenItemPathPatched) >= 2) {
            if(strcasecmp( substr($givenItemPathPatched, 0, 2), ".\\") == 0) {
                $givenItemPathPatched = substr($givenItemPathPatched, 2, strlen($givenItemPathPatched));
            }
        }

        // Check for ..\
        if(strlen($givenItemPathPatched) >= 3) {
            if(strcasecmp( substr($givenItemPathPatched, 0, 3), "..\\") == 0) {
                $temp = $this->getCurrentDirectory()->getParent()->getPath();
                $temp .= "\\";
                $temp .= substr($givenItemPathPatched, 3, strlen($givenItemPathPatched));

                $givenItemPathPatched = $temp;
            }
        }

        // Add drive name if path starts with "\"
        if(substr($givenItemPathPatched, 0, 1) === "\\") {
            $givenItemPathPatched = $this->driveLetter . ":" . $givenItemPathPatched;
        }
        // Make absolute path from relative paths
        if(strlen($givenItemPathPatched) == 1 || substr($givenItemPathPatched, 1, 1) != ":") {
            $givenItemPathPatched = $this->getCurrentDirectory()->getPath() . "\\" . $givenItemPathPatched;
        }

        // Find more complex paths recursive
        if(strcasecmp($givenItemPathPatched, $this->rootDir->getPath()) == 0) {
            return $this->rootDir;
        }

        return $this->getItemFromDirectory($givenItemPathPatched, $this->rootDir);
    }

    public function patchGivenItem($givenItemPath){
        $givenItemPathPatched = str_replace("/", "\\", $givenItemPath);

        return $givenItemPathPatched;
    }

    public function getItemFromDirectory($givenItemName, Directory $directoryToLookup){
        $content = $directoryToLookup->getContent();
        $pathName;
        $retVal;

        foreach($content as $item){
            $pathName = $item->getPath();

            if(strcasecmp($pathName, $givenItemName) == 0) {
                return $item;
            }

            if($item->isDirectory() == true) {
                $retVal = $this->getItemFromDirectory($givenItemName, $item);

                if($retVal != null) {
                    return $retVal;
                }
            }
        }

        return null;
    }

    public function changeCurrentDirectory(Directory $dir) {
        if($this->getItemFromPath($dir->getPath()) == $dir) {
            $this->currentDir = $dir;
            return true;
        } else {
            return false;
        }
    }
}
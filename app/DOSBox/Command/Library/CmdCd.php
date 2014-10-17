<?php

namespace DOSBox\Command\Library;

use DOSBox\Interfaces\IDrive;
use DOSBox\Interfaces\IOutputter;
use DOSBOx\Filesystem\Directory;
use DOSBox\Command\BaseCommand as Command;

class CmdCd extends Command {
    const SYSTEM_CANNOT_FIND_THE_PATH_SPECIFIED = "The system cannot find the path specified.";
    const DESTINATION_IS_FILE = "The directory name is invalid.";

    private $destinationDirectory;

    public function __construct($commandName, IDrive $drive){
        parent::__construct($commandName, $drive);
    }

    public function checkNumberOfParameters($numberOfParametersEntered) {
        return ($numberOfParametersEntered == 0 || $numberOfParametersEntered == 1);
    }

    public function checkParameterValues(IOutputter $outputter) {
        if($this->getParameterCount() > 0) {
            $this->destinationDirectory = $this->extractAndCheckIfValidDirectory($this->params[0], $this->getDrive(), $outputter);

            return $this->destinationDirectory!=null;
        } else {
            $this->destinationDirectory = null;
            return true;
        }
    }

    public function execute(IOutputter $outputter){
        if ($this->getParameterCount() == 0) {
            $this->printCurrentDirectoryPath($this->getDrive()->getCurrentDirectory()->getPath(), $outputter);
        } else {
            $this->changeCurrentDirectory($this->destinationDirectory, $this->getDrive(), $outputter);
        }
    }

    public static function printCurrentDirectoryPath($currentDirectoryName, IOutputter $outputter){
        $outputter->printLine($currentDirectoryName);
    }

    public static function changeCurrentDirectory(Directory $destinationDirectory, IDrive $drive, IOutputter $outputter){
        $success = $drive->changeCurrentDirectory($destinationDirectory);

        if (!$success) {
            $outputter->printLine(self::SYSTEM_CANNOT_FIND_THE_PATH_SPECIFIED);
        }
    }

    public static function extractAndCheckIfValidDirectory($destinationDirectoryName, IDrive $drive, IOutputter $outputter){
        $destinationDirectory = $drive->getItemFromPath($destinationDirectoryName);

        if ($destinationDirectory == null) {
            $outputter->printLine(self::SYSTEM_CANNOT_FIND_THE_PATH_SPECIFIED);
            return null;
        }

        if (!$destinationDirectory->isDirectory()) {
            $outputter->printLine(self::DESTINATION_IS_FILE);
            return null;
        }

        return $destinationDirectory;
    }
}
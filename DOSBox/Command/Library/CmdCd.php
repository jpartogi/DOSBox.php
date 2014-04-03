<?php

namespace DOSBox\Command\Library {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Interfaces\IOutputter;
	use DOSBOx\Filesystem\Directory;
	use DOSBox\Command\Framework\Command as Command;

	class CmdCd extends Command {
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
	        }
	        else {
	        	 $this->destinationDirectory = null;
	        	 return true;
	        }
		}

		public function execute(IOutputter $outputter){
			if ($this->getParameterCount() == 0)
        	{
				$this->printCurrentDirectoryPath($this->getDrive()->getCurrentDirectory()->getPath(), $outputter);
        	}
        	else
        	{
				$this->changeCurrentDirectory($this->destinationDirectory, $this->getDrive(), $outputter);
        	}
		}

		public static function printCurrentDirectoryPath($currentDirectoryName, IOutputter $outputter)
		{
			$outputter->printLine($currentDirectoryName);
		}

		public static function changeCurrentDirectory(Directory $destinationDirectory, IDrive $drive, IOutputter $outputter)
		{
			$success = $drive->changeCurrentDirectory($destinationDirectory);
		}

		public static function extractAndCheckIfValidDirectory($destinationDirectoryName, IDrive $drive, IOutputter $outputter) 
		{
			$destinationDirectory = $drive->getItemFromPath($destinationDirectoryName);

			return $destinationDirectory;
    	}
	}
}
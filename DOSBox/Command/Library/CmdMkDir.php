<?php

namespace DOSBox\Command\Library {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Interfaces\IOutputter;
	use DOSBox\Filesystem\Directory;
	use DOSBox\Command\Framework\Command as Command;

	class CmdMkDir extends Command {
		public function __construct($commandName, IDrive $drive){
			parent::__construct($commandName, $drive);	
		}

		public function checkNumberOfParameters($numberOfParametersEntered) {
			return true;
		}

		public function checkParameterValues(IOutputter $outputter) {
			return true;
		}

		public function execute(IOutputter $outputter){
			$this->createDirectory($this->params[0], $this->drive); // TODO: no hardcode
		}

	   	public function createDirectory($newDirectoryName, IDrive $drive) {
	        $newDirectory = new Directory($newDirectoryName);
	        $drive->getCurrentDirectory()->add($newDirectory);
	    }
	}
}
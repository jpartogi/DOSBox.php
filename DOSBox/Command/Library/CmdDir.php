<?php

namespace DOSBox\Command\Library {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Interfaces\IOutputter;
	use DOSBox\Filesystem\Directory;
	use DOSBox\Command\Framework\Command as Command;

	class CmdDir extends Command {
		private $directoryToPrint;

		public function __construct($commandName, IDrive $drive){
			parent::__construct($commandName, $drive);	
		}

		public function checkNumberOfParameters($numberOfParametersEntered) {
			return ($numberOfParametersEntered == 0 || $numberOfParametersEntered == 1); 
		}

		public function checkParameterValues(IOutputter $outputter) {
			$this->directoryToPrint = $this->getDrive()->getCurrentDirectory(); // TODO: check with params

			return $this->directoryToPrint != NULL;
		}

		public function execute(IOutputter $outputter){
			$this->checkParameterValues($outputter);

			$this->printHeader($this->directoryToPrint, $outputter);
			$this->printContent($this->directoryToPrint->getContent(), $outputter);
			$this->printFooter($this->directoryToPrint, $outputter);
		}

	    public function printHeader($directoryToPrint, IOutputter $outputter) {
	        $outputter->printLine("Directory of " . $directoryToPrint->getPath());
	        $outputter->newLine();
	    }

	 	public function printContent($directoryContent, IOutputter $outputter) {
	        foreach ($directoryContent as $item)
	        {
	            if ($item->isDirectory())
	            {
	                $outputter->printNoLine("<DIR>");
	                $outputter->printNoLine("\t");
	                $outputter->printNoLine("  ");
	            }
	            else
	            {
	                $outputter->printNoLine("\t");
	                $outputter->printNoLine($item->getSize() . " ");
	            } 

	            $outputter->printNoLine($item->getName());
	            $outputter->newLine();
	        }
		}

		public function printFooter($directoryToPrint, IOutputter $outputter) {
	        $outputter->printLine("\t" . $directoryToPrint->getNumberOfContainedFiles() . " File(s)");
	        $outputter->printLine("\t" . $directoryToPrint->getNumberOfContainedDirectories() . " Dir(s)");
	    }
	}
}
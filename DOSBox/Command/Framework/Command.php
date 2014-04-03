<?php

namespace DOSBox\Command\Framework {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Interfaces\IOutputter;

	abstract class Command {
		protected $commandName;
		protected $drive;
		protected $params = array();

		public function __construct($commandName, IDrive $drive){
			$this->commandName = $commandName;
			$this->drive = $drive;
		}

		protected abstract function execute(IOutputter $outputter);

		protected abstract function checkNumberOfParameters($numberOfParametersEntered);

		protected abstract function checkParameterValues(IOutputter $outputter);

		public final function checkParameters(IOutputter $outputter){ // TODO: throws exception
			if(!$this->checkNumberOfParameters($this->getParameterCount())){
				return false;
			}

			if(!$this->checkParameterValues($outputter)) {
				return false;
			}

			return true;
		}

		public function compareCmdName($commandName){
			return ($commandName === $this->commandName);
		}

		public function setParams($params){
			$this->params = $params;
		}

		protected function getParameterCount(){
			return count($this->params);	
		}

		protected function getDrive(){
			return $this->drive;
		}

		public function getCommandName(){
			return $this->commandName;
		}
	}	
}
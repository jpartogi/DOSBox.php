<?php

namespace DOSBox\Command\Library {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Command\Library\CmdCd as CmdCd;

	class CommandFactory {
		private $commands = array();

		public function __construct(IDrive $drive){
			array_push($this->commands, new CmdCd("cd", $drive));
			array_push($this->commands, new CmdMkDir("mkdir", $drive));
			array_push($this->commands, new CmdDir("dir", $drive));
			array_push($this->commands, new CmdMkFile("mkfile", $drive));
			// Add your commands here
		}

		public function getCommandList(){
			return $this->commands;
		}
	}
}
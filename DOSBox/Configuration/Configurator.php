<?php

namespace DOSBox\Configuration {

	use DOSBox\Filesystem\Drive as Drive;
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Command\Library\CommandFactory as CommandFactory;
	use DOSBox\Invoker\CommandInvoker as CommandInvoker;
	use DOSBox\Console\Console;

	class Configurator {
		public function configurateSystem() {
			$drive = new Drive("C");
			$drive->restore();

			$factory = new CommandFactory($drive);
			$commandInvoker = new CommandInvoker();
			$commandInvoker->setCommands($factory->getCommandList());

			$console = new Console($commandInvoker, $drive);

			$console->processInput();
		}
	}
}
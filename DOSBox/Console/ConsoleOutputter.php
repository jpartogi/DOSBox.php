<?php

namespace DOSBox\Console {
	use DOSBox\Interfaces\IOutputter;

	class ConsoleOutputter implements IOutputter {
		private $numberOfPrintedCharacters;

		public function printLine($text){
			print $text . "\n" ;
			$this->analyzePrintedCharacters($text);
		}

		public function printNoLine($text){
			print $text;
			$this->analyzePrintedCharacters($text);
		}

		public function newLine(){
			print "\n";
		}

		protected function analyzePrintedCharacters($printedString){
			$tempString = trim($printedString);
			$this->numberOfPrintedCharacters += strlen($tempString);
		}

	}
}
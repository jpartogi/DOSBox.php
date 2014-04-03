<?php

namespace DOSBox\Interfaces {
	interface IOutputter {
		public function printLine($text);

		public function newLine();

		public function printNoLine($text);
	}
}
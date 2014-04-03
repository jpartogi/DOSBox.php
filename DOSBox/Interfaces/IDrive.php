<?php

namespace DOSBox\Interfaces {
	interface IDrive {
		/**
		* Creates a directory structure from the stored structure. The current directory structure is deleted.
		*/
		public function restore();

		public function getPrompt();

		public function getCurrentDirectory();
	}
}
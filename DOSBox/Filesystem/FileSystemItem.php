<?php

namespace DOSBox\Filesystem {
	use DOSBox\Filesystem\Directory;

	abstract class FileSystemItem {
		private $name;
		private $parent;

		public function __construct($name, $parent){
			$this->name = $name;
			$this->parent = $parent;
		}

		public function getPath() {
			$path = "";
			
			if($this->parent != null) {
				$path = $this->parent->getPath() . "\\" . $this->name;
			}
			else {  // For root directory
				$path = $this->name;
			}
			
			return $path;
		}

		public function getParent(){
			return $this->parent;
		}

		public function setParent($parent){
			$this->parent = $parent;
		}

		public function getName(){
			return $this->name;
		}

		public abstract function isDirectory();

		public function __toString(){
			return $this->getPath();
		}
	}
}
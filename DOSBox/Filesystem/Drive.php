<?php

namespace DOSBox\Filesystem {
	use DOSBox\Interfaces\IDrive;
	use DOSBox\Filesystem\Directory;

	class Drive implements IDrive{
		private $driveLetter;
		private $label;
		private $rootDir;
		private $currentDir;

		public function __construct($driveLetter) {
			$this->driveLetter = strtoupper(substr($driveLetter,0,1));
			$this->label = "";
			$this->rootDir = new Directory($this->driveLetter . ':');
			$this->currentDir = $this->rootDir;
		}

		public function restore(){
			// Not yet implemented
		}

		public function getDriveLetter(){
			return $this->driveLetter . ':';
		}

		public function getPrompt(){
			return $this->currentDir->getPath() . '\>';
		}

		public function getCurrentDirectory(){
			return $this->currentDir;
		}

		public function getRootDir(){
			return $this->rootDir;
		}

		public function getItemFromPath($givenItemPath) {
			$givenItemPathPatched = $this->patchGivenItem($givenItemPath);

			if(strcasecmp($givenItemPathPatched, '.') == 0) {
				return $this->getCurrentDirectory();
			}

			if(strcasecmp($givenItemPathPatched, '..') == 0) {
				$parent = $this->getCurrentDirectory()->getParent();
				if($parent == null) {  // Case if current directory is already root
					$parent = $this->getRootDirectory();
				}
				return $parent;
			}

			if(strlen($givenItemPathPatched) == 1 || substr($givenItemPathPatched,1) != ':') {
				$givenItemPathPatched = $this->getCurrentDirectory()->getPath() . "\\" . $givenItemPathPatched;
			}

			return $this->getItemFromDirectory($givenItemPathPatched, $this->rootDir);
		}

		public function patchGivenItem($givenItemPath){
			$givenItemPathPatched = str_replace("/", "\\", $givenItemPath);
		
			return $givenItemPathPatched;
		}

		public function getItemFromDirectory($givenItemName, Directory $directoryToLookup){
			$content = $directoryToLookup->getContent();
			$pathName;
			$retVal;

			foreach($content as $item){
				$pathName = $item->getPath();

				if(strcasecmp($pathName, $givenItemName) == 0) {
					return $item;
				}

				if($item->isDirectory() == true) {
					$retVal = $this->getItemFromDirectory($givenItemName, $item);
					if($retVal != null) {
						return $retVal;
					}
				}
			}

			return null;
		}

		public function changeCurrentDirectory(Directory $dir) {
			$this->currentDir = $dir;
		}
	}
}
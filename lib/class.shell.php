<?php

	require_once(CORE . '/class.symphony.php');
	require_once(TOOLKIT . '/class.lang.php');
	require_once(CORE . '/class.log.php');
	
	require_once(EXTENSIONS . '/shell/lib/class.shellcommand.php');
	require_once(EXTENSIONS . '/shell/lib/class.errorhandler.php');	
	
	Class Shell extends Symphony{
		
		public static function instance(){
			if(!(self::$_instance instanceof Shell)) 
				self::$_instance = new self;

			return self::$_instance;
		}

		protected function __construct(){
			parent::__construct();
			
			ShellExceptionHandler::initialise();
			
			$this->Profiler->sample('Engine Initialisation (Shell Mode)');
		}
		
		public static function listCommands($extension=NULL){
			
			$extensions = array();
			
			if(is_null($extension)){
				foreach (new DirectoryIterator(EXTENSIONS) as $fileInfo) {
				    if($fileInfo->isDir() && !$fileInfo->isDot() && is_dir($fileInfo->getPathname() . '/bin')){
						$extensions[$fileInfo->getFilename()] = array();
					}
				}
			}
			else{
				
				if(!is_dir(EXTENSIONS . "/{$extension}/bin")){
					throw new Exception('Could not locate any commands for extension. ' . "'{$extension}/bin' directory does not exist.");
				}
				
				$extensions[$extension] = array();
			}
			
			foreach($extensions as $name => $commands){
				foreach (new DirectoryIterator(EXTENSIONS . "/{$name}/bin") as $fileInfo) {
					
					if($name == 'shell' && $fileInfo->getFilename() == 'symphony') continue;
					
					if(!$fileInfo->isDir() && !$fileInfo->isDot() && substr($fileInfo->getFilename(), 0, 1) != '.'){
						$extensions[$name][] = $fileInfo->getFilename();
					}
				}
			}
			
			return (!is_null($extension) ? $extensions[$extension] : $extensions);
		}
	
	}
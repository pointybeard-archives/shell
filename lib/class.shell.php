<?php

	require_once(LIB . '/class.symphony.php');
	require_once(LIB . '/class.lang.php');
	require_once(LIB . '/class.log.php');
	
	require_once('class.shellcommand.php');
	require_once('class.errorhandler.php');
	
	Class Shell extends Symphony{
		
		public static function instance(){
			if(!(self::$_instance instanceof Shell)) 
				self::$_instance = new self;

			return self::$_instance;
		}

		protected function __construct(){
			parent::__construct();
			ShellExceptionHandler::initialise();
		}
		
		public static function listCommands($extension=NULL){
			
			$extensions = array();
			
			if(is_null($extension)){
				foreach(new ExtensionIterator(ExtensionIterator::FLAG_STATUS, Extension::STATUS_ENABLED) as $e){
					$path = Extension::getPathFromClass(get_class($e));
					$handle = Extension::getHandleFromPath($path);

					if(is_dir("{$path}/bin")){
						$extensions[$handle] = array();
					}
				}
			}
			else{
				
				if(!is_dir(EXTENSIONS . "/{$extension}/bin")){
					throw new ShellException('Could not locate any commands for extension. ' . "'{$extension}/bin' directory does not exist.");
				}
				
				$extensions[$extension] = array();
			}
			
			foreach(array_keys($extensions) as $handle){
				$scripts = glob(EXTENSIONS . "/{$handle}/bin/*");
				foreach($scripts as $s){
					$name = basename($s);
					if(($handle == 'shell' && $name == 'symphony') || $name{0} == '.') continue;
					$extensions[$handle][] = basename($name);
				}
			}
			
			return (!is_null($extension) ? $extensions[$extension] : $extensions);
		}
	
	}
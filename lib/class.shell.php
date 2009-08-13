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
	
	}
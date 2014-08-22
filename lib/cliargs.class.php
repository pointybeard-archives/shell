<?php
	/**
	 * Handles arguments from the command line ($argv), returning an associative array.
	 * @package Shell/Documentation/Core
	 */
	class CLIArgs{

		private $_args;

		/**
		 * Constructor. Reads from the global $argv array, parsing it out into the private $_args array
		 * @return type
		 */
		public function __construct(){

			global $argv;
			$this->_args = array();

			// Handle 4 different kinds of params
			for($key = 0; $key < count($argv); $key++){
				$val = $argv[$key];

				// 1. --zz=something
				if(preg_match('@--[a-z0-9]+=@i', $val)){
					$item = function($str){ $bits = explode("=", $str, 2); return array(ltrim($bits[0], '--') => $bits[1]);};
					$this->_args = array_merge($this->_args, $item($val));
				}

				// 2. --aa
				elseif(substr($val, 0, 2) == '--'){
					$this->_args[ltrim($val, '-')] = true;
				}

				// 3. -x
				elseif($val{0} == '-' && isset($argv[$key+1]) && $argv[$key+1]{0} == '-'){
					$this->_args[ltrim($val, '-')] = true;
				}

				// 4. -y blah
				elseif($val{0} == '-' && isset($argv[$key+1]) && $argv[$key+1]{0} != '-'){
					$this->_args[ltrim($val, '-')] = $argv[$key+1];
					$key++;
				}

				// 5. blah
				else{
					$this->_args[] = $val;
				}
			}
		}
	}
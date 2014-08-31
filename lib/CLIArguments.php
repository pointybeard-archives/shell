<?php
	namespace Shell\Lib;
	use \Iterator;

	Class Argument{
		private $_name;
		private $_value;

		public function __construct($name, $value){
			$this->_name = $name; $this->_value = $value;
		}

		public function __get($name){
			if($name != "name" && $name != "value") return false;
			return $this->{"_{$name}"};
		}
	}

	/**
	 * Handles arguments from the command line ($argv), returning an associative array.
	 * @package Shell/Documentation/Core
	 */
	Class CliArguments implements Iterator{

		private $_args, $_keys;
		private $_position = 0;

		/**
		 * Constructor. Reads from the global $argv array, parsing it out into the private $_args array
		 */
		public function __construct(){

			global $argv;

			$this->_position = 0;
			$this->_args = array();
			$this->_keys = array();

			// Handle 4 different kinds of params
			for($key = 0; $key < count($argv); $key++){
				$value = $argv[$key];

				// 1. --zz=something
				if(preg_match('@--[a-z0-9]+=@i', $value)){
					$bits = explode("=", $value, 2);
					$name = ltrim($bits[0], '--');
					$value = $bits[1];
				}

				// 2. --aa
				elseif(substr($value, 0, 2) == '--'){
					$name = ltrim($value, '-'); $value = true;
				}

				// 3. -x
				elseif($value{0} == '-' && isset($argv[$key + 1]) && $argv[$key + 1]{0} == '-'){
					$name = ltrim($value, '-'); $value = true;
				}

				// 4. -y blah
				elseif($value{0} == '-' && isset($argv[$key + 1]) && $argv[$key + 1]{0} != '-'){
					$name = ltrim($value, '-'); $value = $argv[$key+1];
					$key++;
				}
/*
				// 5. blah
				else{
					$name = NULL;
				}
*/

				$this->_args[] = new namespace\Argument($name, $value);
				$this->_keys[] = $name;
			}
		}

	    public function find($name){
	    	return (in_array($name, $this->_keys) ? $this->_args[array_search($name, $this->_keys)] : false);
	    }

		public function rewind() {
			$this->_position = 0;
		}

		public function current() {
			return $this->_args[$this->_position];
		}

		public function key() {
			return $this->_position;
		}

		public function next() {
			++$this->_position;
		}

		public function valid() {
			return isset($this->_args[$this->_position]);
		}
	}
<?php

	require_once(CORE . '/class.symphony.php');
	require_once(TOOLKIT . '/class.lang.php');
	require_once(CORE . '/class.log.php');

	Class Shell extends Symphony{

		private $_CLIArgs;

		public static function instance(){
			if(!(self::$_instance instanceof Shell))
				self::$_instance = new self;

			return self::$_instance;
		}

		protected function __construct(){
			parent::__construct();
			\Shell\Lib\ExceptionHandler::initialise();
			$this->_CLIArgs = new \Shell\Lib\CLIArguments;
		}

		public function __get($name){
			if(!isset($this->{"_{$name}"})) return false;
			return $this->{"_{$name}"};
		}

		/**
		 * Overload the Symphony::login function to bypass some code that
		 * forces use of the Administration class (which of course is not
		 * available in Shell). Hopefully this is fixed in the core Symphony code
		 *
		 */
		public function login($username, $password, $isHash=false){
			$username = self::Database()->cleanValue($username);
			$password = self::Database()->cleanValue($password);

			if(strlen(trim($username)) > 0 && strlen(trim($password)) > 0){

				$author = AuthorManager::fetch('id', 'ASC', 1, null, sprintf("
						`username` = '%s'
					", $username
				));

				if(!empty($author) && Cryptography::compare($password, current($author)->get('password'), $isHash)) {
					$this->Author = current($author);

					// Only migrate hashes if there is no update available as the update might change the tbl_authors table.
					if(Cryptography::requiresMigration($this->Author->get('password'))){
						throw new ShellException('User details require updating. Please login to the admin interface.');
					}

					$this->Cookie->set('username', $username);
					$this->Cookie->set('pass', $this->Author->get('password'));
					self::Database()->update(array(
						'last_seen' => DateTimeObj::get('Y-m-d H:i:s')),
						'tbl_authors',
						sprintf(" `id` = %d", $this->Author->get('id'))
					);

					return true;
				}
			}

			return false;
		}

		public static function cleanArguments(array $args){
			$command = NULL;
			$options = array();
			$inOption = false;

			foreach($args as $item){

				if($item{0}.$item{1} == '--'){
					$bits = preg_split('/=/', $item, 2);
					$key = ltrim($bits[0], '-');
					$options[$key] = $bits[1];
				}

				elseif($item{0} == '-'){
					$inOption = true;
					$key = ltrim($item, '-');
				}

				elseif($inOption == true){
					$options[$key] = $item;
					$inOption = false;
				}

				else{
					$options[] = $item;
				}
			}

			return $options;
		}

		public static function listCommands($extension=NULL){

			$extensions = array();

			if(is_null($extension)){
				foreach(ExtensionManager::fetch() as $handle => $about){
					if(!in_array(EXTENSION_ENABLED, $about['status'])) continue;

					if(is_dir(ExtensionManager::__getClassPath($handle) . '/bin')){
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
<?php
	
	Abstract Class ShellCommand{
		abstract public function run(array $args=NULL);
		public function usage(){
			echo "No usage information for this command is available.";
		}
	}
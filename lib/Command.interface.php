<?php
	namespace Shell\Lib;
	interface Command{
		public function run();
		public function usage();
	}
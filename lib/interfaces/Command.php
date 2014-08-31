<?php
	namespace Shell\Lib\Interfaces;
	interface Command{
		public function run();
		public function usage();
	}
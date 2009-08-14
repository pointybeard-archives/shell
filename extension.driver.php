<?php

	Class extension_Shell extends Extension{

		public function about(){
			return array('name' => 'Shell',
						 'version' => '0.2',
						 'release-date' => '2009-08-14',
						 'author' => array('name' => 'Alistair Kearney',
										   'website' => 'http://symphony-cms.com',
										   'email' => 'alistair@symphony-cms.com')
				 		);
		}
		
	}
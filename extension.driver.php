<?php

	Class extension_Shell implements iExtension{

		public function about(){
			return (object)array('name' => 'Shell',
						 'version' => '0.3',
						 'release-date' => '2009-08-18',
						 'author' => (object)array('name' => 'Alistair Kearney',
										   'website' => 'http://symphony-cms.com',
										   'email' => 'alistair@symphony-cms.com')
				 		);
		}
		
	}
	
	return 'extension_Shell';
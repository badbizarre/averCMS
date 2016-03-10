<?php

class Main_Controller {
	public function __construct() {
    
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			no_cache();
		}

		if (URL::getSegment(1) == 'adminpanel') {
			Load::controller('adminpanel/authenticate');
		}

		cmsUser::getInstance();
		Load::controller(URL::getController(), URL::getAction());
		
	}
}
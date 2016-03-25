<?php

class Messages {
	
	private static $_table, $_id_user;

	public static function init() {
		
		self::$_table = get_table('users_message');
		self::$_id_user = cmsUser::isSessionSet('user') ? cmsUser::sessionGet('user:id') : 0;
		
	}

}




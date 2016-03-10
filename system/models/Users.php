<?php

class Users {
	
	private static $_table, $_id_user;

	public static function init() {
		
		self::$_table = get_table('users');
		self::$_id_user = cmsUser::isSessionSet('user') ? cmsUser::sessionGet('user:id') : 0;
		
	}
  
	
	public static function get_friends_list($action) {
		
		$where = 'active = 1';
		
		if ($action == 'friends') {
			$where .= ' and id IN ('.get_keys_items(Friends::get_friends(),'friend_id').')';
		}
				
		if ($action == 'new') {
			$where .= ' and id IN ('.get_keys_items(Friends::get_new_friends(),'user_id').')';
		}
						
		if ($action == 'out') {
			$where .= ' and id IN ('.get_keys_items(Friends::get_out_friends(),'friend_id').')';
		}
						
		if ($action == 'old') {
			$where .= ' and id IN ('.get_keys_items(Friends::get_old_friends(),'user_id').')';
		}
		
		return Database::getRows(self::$_table, 'id DESC', false,$where);	

	}

}




<?php

class Users extends Database {
	
	private static $_table;

	public static function init() {
		
		self::$_table = get_table('users');

	}
  
	public static function getUser($user_id) {
		
		$user = self::getRow(self::$_table,$user_id);
		
		return $user;
		
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
		
		return self::getRows(self::$_table, 'id DESC', false,$where);	

	}

}




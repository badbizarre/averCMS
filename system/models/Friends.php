<?php

class Friends extends Database {
	
	private static $_table;

	public static function init() {
		
		self::$_table = get_table('users_friends');
		
	}
  
	public static function clean($friend_id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `user_id` = :user_id and `friend_id` = :friend_id';

		$params = array (
			'user_id' => self::$_id_user,
			'friend_id' => $friend_id
		);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении друга!', E_USER_ERROR);
		}

	}
    
	public static function cancel($friend_id) {

		self::clean($friend_id);
	
		$data = array(
			'is_mutual' => 2
		);
		$where = 'user_id = '.$friend_id.' and friend_id = '.self::$_id_user;
		self::update(self::$_table,$data,$where);
	
	}
  
	public static function get_friends() {

		return self::getRows(self::$_table,'id DESC', false, 'is_mutual = 1 and user_id = '.self::$_id_user);
			
	}

	public static function get_old_friends() {

		return self::getRows(self::$_table,'id DESC', false, 'is_mutual = 2 and friend_id = '.self::$_id_user);
			
	}

	public static function get_out_friends() {

		return self::getRows(self::$_table,'id DESC', false, 'is_mutual <> 1 and user_id = '.self::$_id_user);
			
	}

	public static function get_new_friends() {

		return self::getRows(self::$_table,'id DESC', false, 'is_mutual = 0 and friend_id = '.self::$_id_user);
			
	}

	public static function is_repeat_add($friend_id) {
		
		$where = 'user_id = '.self::$_id_user.' and friend_id = '.$friend_id;
		
		return self::getCount(self::$_table,$where);
		
	}
	
	public static function is_again_add($friend_id) {
				
		$where = 'user_id = '.$friend_id.' and friend_id = '.self::$_id_user.' and is_mutual = 1';
		
		return self::getCount(self::$_table,$where);
		
	}
		
	public static function is_was_add($friend_id) {
				
		$where = 'user_id = '.$friend_id.' and friend_id = '.self::$_id_user.' and is_mutual = 0';
		
		return self::getCount(self::$_table,$where);
		
	}
	
	public static function is_friend($friend_id) {
		
		$friends = explode(',',get_keys_items(self::get_friends(),'friend_id'));
		
		return in_array($friend_id,$friends);
		
	}

	public static function is_subscriber($friend_id) {
		
		$friends = explode(',',get_keys_items(self::get_new_friends(),'user_id'));
		
		return in_array($friend_id,$friends);
		
	}

	public static function is_signed($friend_id) {
		
		$friends = explode(',',get_keys_items(self::get_out_friends(),'friend_id'));
		
		return in_array($friend_id,$friends);
		
	}

	public static function is_oldest($friend_id) {
		
		$friends = explode(',',get_keys_items(self::get_old_friends(),'user_id'));
		
		return in_array($friend_id,$friends);
		
	}

	public static function get_badge_friends($kolvo) {
		
		$html = '';
		
		if ($kolvo) $html = '('.$kolvo.')';
		
		return $html;
	}
	
	public static function get_friend_button($friend_id) {

		if (self::is_subscriber($friend_id)) {
			
			$action = 'apply';
			$text_btn = 'Принять заявку';						
			
		} elseif (self::is_friend($friend_id)) {
			
			$action = 'delete_friend';
			$text_btn = 'Удалить из друзей';
						
		} elseif (self::is_signed($friend_id)) {
			
			$action = 'abort_apply';								
			$text_btn = 'Отменить заявку';
			
		} elseif (self::is_oldest($friend_id)) {
			
			$action = 'add_repeat';
			$text_btn = 'Добавить снова';
			
		} else {
			
			$action = 'add';
			$text_btn = 'Добавить в друзья';
					
		}
		
		$html = '<form action="/user/'.$action.'" method="POST" class="jsform">
			<input type="hidden" name="friend_id" value="'.$friend_id.'">								
			<button type="submit" class="btn btn-default button-panel__btn">'.$text_btn.'</button>
		</form>';
		
		return $html;
	}

	public static function get_friend_apply_button($friend_id) {
		
		$html = '<form action="/user/apply" method="POST" class="jsform">
			<input type="hidden" name="friend_id" value="'.$friend_id.'">								
			<button type="submit" class="btn btn-default">Принять в друзья</button>
		</form>';
		
		return $html;
	}

}




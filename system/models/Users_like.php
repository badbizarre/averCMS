<?php

class Users_like extends Database {
	
	private static $_table;

	public static function init() {
		
		self::$_table = get_table('users_like');
		
	}
	
	public static function getIds($table_name) {
		
		$where = 'id_user = '.self::$_id_user.' and table_name="'.$table_name.'"';
		return self::getRows(self::$_table,'id asc',false,$where);
		
	}
 
	public static function addLike($table_name) {
		
		/* variate, 1: like, 2: note */

		if (isset($_POST['variate'])) {

			if (isset($_POST['id_table']) and !empty($_POST['id_table'])) {
			
				$id_table = $_POST['id_table'];

				$id_variate = $_POST['variate'];

				$users_like = self::getUsersVariate($id_table,$id_variate,$table_name);
								
				if (isset($users_like) and !empty($users_like)) {	
					
					self::delete(self::$_table,$users_like['id']);
											
				} else {
					
					$data = array(
						'id_user' => self::$_id_user,
						'id_table' => $id_table,
						'id_variate' => $id_variate,
						'table_name' => $table_name
					);
					
					self::insert(self::$_table,$data,'id='.$users_like['id']);
												
				}				
			}

		}
		
	}
  
	public static function getUsersVariate($id_table,$id_variate,$table_name) {

		$where = 'id_table = '.$id_table.' and 
				  table_name="'.$table_name.'" and 
				  id_variate = '.$id_variate.' and	
				  id_user = '.self::$_id_user;

		$result = Database::getRows(self::$_table,'id desc',1,$where);

		return @$result[0];
		
	}

	public static function getCountVariate($id_table,$id_variate,$table_name) {
			
		$where = 'id_table = '.$id_table.' and 
				  table_name="'.$table_name.'" and 
				  id_variate = '.$id_variate;	

		$count = self::getCount(self::$_table,$where);	  
				  
		if ($count > 0) return $count;
		
	}
		 
}




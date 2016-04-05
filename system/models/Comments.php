<?php

class Comments extends Database {
	
	private static $_table;

	public static function init() {
		
		self::$_table = get_table('users_comment');
		
	}
  
	public static function getComments($id_table,$table_name) {
		
		$where = 'id_table='.$id_table.' and table_name="'.$table_name.'" and active = 1';
		
		$items = self::getRows(self::$_table,'id asc',false,$where);
		
		return $items;		
		
	}
    
	public static function addComment($table_name) {

		$data = array(
			'id_user' => self::$_id_user,
			'id_table' => $_POST['id_table'],
			'table_name' => $table_name,
			'comment' => htmlspecialchars($_POST['comment']),
			'date_create' => date('Y-m-d H:i:s'),
			'pid' => $_POST['pid'],
			'active' => 1
		);	
		
		self::insert(self::$_table,$data);
				
	}
 
	public static function updateComment($id) {
				
		$data = array(
			'comment' => htmlspecialchars($_POST['comment'])
		);
	
		self::update(self::$_table,$data,'id='.$id);
		
	}
	 
	public static function removeComment($id) {
					
		$data = array(
			'active' => 0
		);
	
		self::update(self::$_table,$data,'id='.$id);
		
	}
	
	public static function getIdTable($id) {
		
		$result = self::getRow(self::$_table,$id);
		return $result['id_table'];
		
	}

}




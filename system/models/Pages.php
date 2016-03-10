<?php

class Pages {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->pages');
		self::$_table = self::$_config['table'];
	}
  
	public static function fetchContent($path = '', $id = '') {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path OR `id` = :id LIMIT 1';
		
		if (($result = DB::query($statement, Array('path' => $path, 'id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при выполнении запроса к БД для получения содержимого страницы!', E_USER_ERROR);
		}

		if (empty($result)) {
			trigger_error('Страница ['.(($path) ? $path : 'id:'.$id).'] в БД не найдена!', E_USER_ERROR);
		}

		return self::$_content = $result;
	
	}  
  
	public static function getPageByID($id) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($pages = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении страниц из БД', E_USER_ERROR);
		}

		return $pages;
		
	}
  
	public static function getPages($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении страниц из БД', E_USER_ERROR);
		}

		return $makers;
		
	}

	public static function getPagesList($limit = '') {
  
		$limit = ($limit) ? 'LIMIT ' . $limit : '';
		$statement = 'SELECT * FROM `'.self::$_table."` ORDER BY `prioritet` DESC {$limit}";

		if (($result = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении запроса к БД для получения основных разделов сайта!', E_USER_ERROR);
		}

		return $result;
		
	}  
 
	public static function getTotalPages() {
  
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества страниц в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
  
	public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $makers;
		
	}

	public static function addPage($data) {
  
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `name`, `namefull`, `path`, `prioritet`, `short_description`, `title`, `keywords`, `description`)
					  VALUES
						(:active, :name, :namefull, :path, :prioritet, :short_description, :title, :keywords, :description)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении страниц в БД!', E_USER_ERROR);
		}
		
	}
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последней странице из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function updatePage($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`name` = :name,
							`namefull` = :namefull,
							`path` = :path,
							`prioritet` = :prioritet,
							`short_description` = :short_description,
							`title` = :title,
							`keywords` = :keywords,
							`description` = :description
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания страниц в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function removePage($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении страниц из БД!', E_USER_ERROR);
		}

	}

	public static function getId() {
		return self::$_content['id'];
	}

	public static function getPath() {
		return self::$_content['path'];
	}

	public static function getTitle() {
		return self::$_content['title'];
	}

	public static function getName() {
		return self::$_content['name'];
	}

	public static function getNamefull() {
		return self::$_content['namefull'];
	}
  
	public static function getDescription() {
		return self::$_content['description'];
	}

	public static function getKeywords() {
		return self::$_content['keywords'];
	}

	public static function getContent() {
		return self::$_content['short_description'];
	}
 
  
}
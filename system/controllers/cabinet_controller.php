<?php

class Cabinet_Controller {
	private $_table, $_content;

	public function __construct() {	
	
		if (!cmsUser::isSessionSet('user')) header("Location: /");
	
		$this->_page = URL::getSegment(1);
		$this->_table = get_table('users');
		
		$this->_table_users_like = get_table('users_like');
		$this->_table_catalog = get_table('catalog');
		$this->_table_users_friends = get_table('users_friends');
		
		$this->_config = Config::getParam('modules->users');
		$this->_config_catalog = Config::getParam('modules->catalog');
		
		$this->_id_user = cmsUser::sessionGet('user:id');
		
		$this->_content['left'] = Render::view ('cabinet/razdel');
  		
	}

	public function defaultAction() {
	
		$item = Database::getRow($this->_table,cmsUser::sessionGet('user:id'));
		$image = insert_image('users','big',$item['image']);

		$sex = get_array_sex();

		$this->_content['h1'] = "Моя страница";
				
		$this->_content['content'] = Render::view ($this->_page.'/list',array(
			'item' => $item,
			'image' => $image,
			'sex' => $sex[$item['sex']]
		));
	
		Render::layout('page',  $this->_content);
	}
	
	public function settingsAction() {
	
		$item = Database::getRow($this->_table,cmsUser::sessionGet('user:id'));

		$this->_content['h1'] = "Мои настройки";
		
		$this->_content['content'] = Render::view ($this->_page.'/settings',array(
			'item' => $item,
		));
	
		Render::layout('page',  $this->_content);
	}
	
	public function likes_noteAction() {
	

	
		$ids = Database::getRows($this->_table_users_like,'id asc',false,'id_user = '.$this->_id_user.' and table_name="catalog"');
		$ids_html = get_keys_items($ids,'id_table');
		
		$where = "id IN($ids_html)";
		$items = Database::getRows($this->_table_catalog,'id asc',false,$where);

		$this->_content['h1'] = "Мои заметки";
		
		$this->_content['content'] = Render::view ($this->_page.'/likes_note',array(
			'items' => $items,
			'image_path' => $this->_config_catalog['image'],
			'totals' => count($items)
		));
	
		Render::layout('page',  $this->_content);
	}
	
	public function friendsAction() {

		$this->_content['h1'] = "Мои друзья";

		$path_url = ((URL::getSegment(3)=='') ? URL::getSegment(2) : URL::getSegment(3));
		
		if (!in_array($path_url,array('friends','new','out','old'))) return;
		
		$items = Users::get_friends_list($path_url);	
	
		$this->_content['content'] = Render::view (
			$this->_page.'/friends', Array (
				'items' => $items
			)
		);

		Render::layout('page',  $this->_content);
		
	}
	
	public function notificationsAction() {
		
		$this->_content['h1'] = 'Мои ответы';

		$comments = Database::getRows(get_table('users_comment'),'id desc',false,'id_user='.$this->_id_user.' and table_name="catalog"');
		$ids_html = get_keys_items($comments,'id_table');
		$where = "id IN($ids_html)";
		$items = Database::getRows($this->_table_catalog,'id asc',false,$where);
		
		$this->_content['content'] = Render::view ($this->_page.'/notifications',array(
			'items' => $items,
			'totals' => count($comments),
			'user' => cmsUser::getUser()
		));
	
		Render::layout('page',  $this->_content);	
	
	}
  
	public function save_avatarAction() {

		$error = array();
		
		$param = array();

		if ($_FILES['image']['name']) { 
	
			if (!empty($_POST['datay']) and !empty($_POST['datax'])) {
				$param = array(
					'width' => $_POST['width'],
					'height' => $_POST['height'],
					'y' => $_POST['datay'],
					'x' => $_POST['datax'],
				);
			}
			
			$upload = Database::uploadImage($_FILES['image'], $this->_config['image'], $param);

			if ($upload['error']) {$error['image'] = $upload['error'];}
			
		}

		if (!$error) {
				
			$id = cmsUser::sessionGet('user:id');
				
			$item = Database::getRow($this->_table,$id);

			$data['image'] = ((isset($upload)) ? $upload['name'] : $item['image']);

			$where = "id=$id";
			Database::update($this->_table,$data,$where);

			if (isset($upload)) {
				@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
				@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
			}
			
		}
		
		header('Location: /'.$this->_page);
						
	}
  
	public function saveAction() {
		
		if (isset($_POST['action'])) {
			
			if (!empty($_POST['email'])) {

				$data = array( 
					'name' => $_POST['name'],			  		  		  		  		  		  
					'sex' => $_POST['sex'],			  		  		  		  		  		  
					'email' => $_POST['email'],			  		  		  		  		  		  
					'birthday_day' => $_POST['birthday_day'],			  		  		  		  		  		  
					'birthday_month' => $_POST['birthday_month'],			  		  		  		  		  		  
					'birthday_year' => $_POST['birthday_year'],			  		  		  		  		  		  
					'city' => $_POST['city'],			  		  		  		  		  		  
					'current_info' => $_POST['current_info'],			  		  		  		  		  		  
					'about' => $_POST['about'],			  		  		  		  		  		  
					'interes' => $_POST['interes']		  		  		  		  		  		  
				);

				// РЕДАКТИРОВАТЬ элемент в таблице
				if ($_POST['action']=="edit") {

					$id = cmsUser::sessionGet('user:id');
					$where = "id=$id";
					Database::update($this->_table,$data,$where);

				}
				
				$response['succes'] = true;
				$response['message'] = 'Данные успешно сохранены';
				
			} else {
			
				$response['false'] = true;
				$response['message'] = 'Email обязательное поле';

			}
		}

		echo json_encode($response);
		
	}
 	
	public function messagesAction() {
		
		$this->_content['h1'] = 'Мои сообщения';

		$this->_content['content'] = Render::view ($this->_page.'/messages',array(

		));
	
		Render::layout('page',  $this->_content);	
	
	}
   
}
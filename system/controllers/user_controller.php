<?php

class User_Controller {
	private $_content, $_config, $_table, $_user_id;

	public function __construct() {
		
		$this->_page = URL::getSegment(1);
		
		$this->_user_id = cmsUser::sessionGet('user:id');
	
		$this->_table_users_friends = get_table('users_friends');
		
		$this->_content['left'] = Render::view ('cabinet/razdel');
  			
	}

	public function defaultAction() {

		header('Location: /');
		
	}
	
	public function addAction() {
		
		$response = array();

		if (!empty($this->_user_id)) {
				
			$friend_id = $_POST['friend_id'];
						
			if (isset($friend_id) and !empty($friend_id)) {

				if ($this->_user_id == $friend_id) {
					
					$response['succes'] = false;
					$response['message'] = 'Это Вы :-)';
				
				} else {

					if (!Friends::is_repeat_add($friend_id)) {

						$data = array(
							'user_id' => $this->_user_id,
							'friend_id' => $friend_id,
							'is_mutual' => 0
						);
						
						if (Friends::is_was_add($friend_id)) {

							$data['is_mutual'] = 1;

							$where = 'user_id = '.$friend_id.' and friend_id = '.$this->_user_id;
							Database::update($this->_table_users_friends,array('is_mutual'=>1),$where);
													
						} 							
						
						Database::insert($this->_table_users_friends,$data);
							
					
						$response['succes'] = true;
						$response['message'] = 'Запрос на дружбу был отправлен!';
						$response['url'] = $_SERVER['HTTP_REFERER'];					
												
					} else {
				
						$response['succes'] = false;
						$response['message'] = 'Вы уже отправили запрос на дружбу!';
												
					}
					
				}
			}

		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';					
			
		}	
			
		echo json_encode($response);		
		
	}	
	
	public function add_repeatAction() {
		
		$response = array();

		if (!empty($this->_user_id)) {
				
			$friend_id = $_POST['friend_id'];
			
			if (isset($friend_id) and !empty($friend_id)) {

				$data = array(
					'user_id' => $this->_user_id,
					'friend_id' => $friend_id,
					'is_mutual' => 1
				);

				Database::insert($this->_table_users_friends,$data);
				
				$where = 'user_id = '.$friend_id.' and friend_id = '.$this->_user_id;
				Database::update($this->_table_users_friends,array('is_mutual'=>1),$where);
			
				$response['succes'] = true;
				$response['message'] = 'Запрос на дружбу был отправлен!';
				$response['url'] = $_SERVER['HTTP_REFERER'];					
				
			}
			
		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';					
			
		}
		
		echo json_encode($response);			
		
	}
	
	public function applyAction() {
		
		$response = array();
		
		if (!empty($this->_user_id)) {

			$friend_id = $_POST['friend_id'];
				
			if (isset($friend_id) and !empty($friend_id)) {

				if (!Friends::is_repeat_add($friend_id)) {

					Database::update($this->_table_users_friends,array('is_mutual' => 1),'user_id = '.$friend_id);
				
					$data = array(
						'user_id' => $this->_user_id,
						'friend_id' => $friend_id,
						'is_mutual' => 1
					);
					
					Database::insert($this->_table_users_friends,$data);
									
					$response['succes'] = true;
					$response['message'] = 'Заявка в друзья была принята!';
					$response['url'] = $_SERVER['HTTP_REFERER'];
								
				} else {
					
					$response['succes'] = true;
					$response['message'] = 'Вы уже приняли заявку!';					
					
				}
				
			}
			
		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';					
			
		}
		
		echo json_encode($response);		
		
	}
		
	public function abort_applyAction() {
		
		$response = array();
		
		if (!empty($this->_user_id)) {
				
			$friend_id = $_POST['friend_id'];
						
			if (isset($friend_id) and !empty($friend_id)) {

				if (Friends::is_again_add($friend_id)) {
				
					Friends::cancel($friend_id);

				} else {
				
					Friends::clean($friend_id);
				
				}
			
				$response['succes'] = true;
				$response['message'] = 'Заявка в друзья была отменена!';
				$response['url'] = $_SERVER['HTTP_REFERER'];
							
			}
			
		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';					
			
		}
		
		echo json_encode($response);		
		
	}
			
	public function delete_friendAction() {
		
		$response = array();
				
		$friend_id = $_POST['friend_id'];
						
		if (isset($friend_id) and !empty($friend_id)) {

			Friends::cancel($friend_id);
	
			$response['succes'] = true;
			$response['message'] = 'Заявка в друзья была удалена!';
			$response['url'] = $_SERVER['HTTP_REFERER'];
						
			
		}

		echo json_encode($response);		
		
	}
	
}
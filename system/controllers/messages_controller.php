<?php

class Messages_Controller {
	private $_content;

	public function __construct() {

		$this->_page = URL::getSegment(1);

		$this->_table_dialog = get_table('users_dialogs');
	
		$this->_id_user = cmsUser::sessionGet('user:id');
		
		$this->_content['left'] = Render::view('cabinet/razdel').Render::view('catalog/razdel');

	}

	public function defaultAction() {
 	
		if (!cmsUser::isSessionSet('user')) {
			header("Location: /");
			return;
		}

		$items = Messages::getDialog();
		
		if (!empty($items)) {
			$this->_content['content'] = Render::view (
				$this->_page.'/list', Array (
					'items' => $items
				)
			);
		} else {
			$this->_content['content'] = '<div class="alert alert-success">Здесь будет выводиться список Ваших сообщений.</div>';
		}
		
		
		Render::layout('page',  $this->_content);
		
	}

	public function dialogAction() {

		if ((!isset($_GET['id']) and empty($_GET['id'])) or !is_numeric($_GET['id']) or !cmsUser::isSessionSet('user')) {
			header("Location: /messages");
			return;
		}	
		
		$for_user_id = $_GET['id'];

		$items = Messages::getMessages($for_user_id);
		
		Messages::readMessage($for_user_id);
		
		$this->_content['content'] = Render::view(
			$this->_page.'/dialog', Array (
				'items' => $items,
				'id_recipient' => $for_user_id
			)
		);

		Render::layout('page',  $this->_content);	

	}
	
	public function add_msgAction() {
		
		$response = array();
		
		if (!empty($this->_id_user)) {
				
			$user_id = $_POST['user_id'];
							
			if (isset($user_id) and !empty($user_id)) {

				$response = Messages::addMessage($user_id);
			
				$response['url'] = $_SERVER['HTTP_REFERER'];
							
			}

		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';	
			
		}
			
		echo json_encode($response);		
		
	}
				
	public function delete_msgAction() {
		
		$response = array();
		
		if (!empty($this->_id_user)) {
			
			$msg_id = $_POST['msg_id'];
							
			if (isset($msg_id) and !empty($msg_id)) {

				Messages::deleteMessage($msg_id);
		
				$response['succes'] = true;
				$response['message'] = 'Сообщение было удалено!';
				$response['url'] = $_SERVER['HTTP_REFERER'];
							
				
			}
			
		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';	
			
		}
		
		echo json_encode($response);		
		
	}	
	
	public function delete_dialogAction() {
		
		$response = array();
		
		if (!empty($this->_id_user)) {
				
			$dialog_id = $_POST['dialog_id'];
							
			if (isset($dialog_id) and !empty($dialog_id)) {

				Messages::deleteDialog($dialog_id);
		
				$response['succes'] = true;
				$response['message'] = 'Диалог был удален!';
				$response['url'] = $_SERVER['HTTP_REFERER'];
							
				
			}
			
		} else {
			
			$response['succes'] = false;		
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';	
						
		}	

		echo json_encode($response);		
		
	}
		
	
	
}
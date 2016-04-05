<?php

class Messages extends Database {
	
	private static $_table, $_table_dialog, $_table_users, $_table_msg;

	public static function init() {
		
		self::$_table_msg = get_table('messages');
		self::$_table = get_table('users_messages');
		self::$_table_dialog = get_table('users_dialogs');
		self::$_table_users = get_table('users');
		
	}
	
	public static function addMessage($user_id) {

		$response = array();
		$respnse['succes'] = false;
	
		$for_user_id = intval($user_id);
		$comment = htmlspecialchars($_POST['comment']);
		$server_time = date('Y-m-d H:i:s');
		
		$user = Users::getUser($for_user_id);
		
		if (!empty($user)) {
			
			$data_msg = array(
				'comment' => $comment,
				'date_create' => $server_time
			);
			self::insert(self::$_table_msg,$data_msg);
			$id_message = self::getLastId(self::$_table_msg);
			$id_dialog = self::createDialog(self::$_id_user,$for_user_id);
			$id_dialog_2 = self::createDialog($for_user_id,self::$_id_user,true);
			
			if (!$id_dialog or !$id_dialog_2) return;
			
			$data = array(
				'id_dialog' => $id_dialog,
				'for_user_id' => $for_user_id,
				'from_user_id' => self::$_id_user,
				'id_message' => $id_message
			);
			
			self::insert(self::$_table,$data);		
				
			$data['id_dialog'] = $id_dialog_2;	
			
			self::insert(self::$_table,$data);		

			$response['succes'] = true;

		} else {
			
			$response['message'] = 'Такого пользователя не существует!';
			
		}
		
		return $response;
	}
	
	public static function createDialog($iuser_id,$im_user_id,$with_num = false) {
		
		if (!$iuser_id or !$im_user_id) return;
		
		$server_time = date('Y-m-d H:i:s');
		
		$where_check = 'iuser_id = '.$iuser_id.' AND im_user_id = '.$im_user_id;
		$check_dialog = self::getRows(self::$_table_dialog,'id desc',1,$where_check);
	
		if(empty($check_dialog[0])) {

			$data_dialog = array(
				'iuser_id' => $iuser_id,
				'im_user_id' => $im_user_id,
				'idate' => $server_time
			);
			
			if ($with_num) $data_dialog['msg_num'] = 1;
			
			self::insert(self::$_table_dialog,$data_dialog);
			$id_dialog = self::getLastId(self::$_table_dialog);
			
		} else {
			
			$check_dialog = $check_dialog[0];
					
			$data_dialog = array(
				'idate' => $server_time,
				'is_delete' => 0
			);
			
			if ($with_num) $data_dialog['msg_num'] = ++$check_dialog['msg_num'];
			
			$where_dialog = 'iuser_id = '.$iuser_id.' AND im_user_id = '.$im_user_id;
			self::update(self::$_table_dialog,$data_dialog,$where_dialog);	
			$id_dialog = $check_dialog['id'];
			
		}
		
		return $id_dialog;
		
	}
	
	public static function getMessageButton($user_id) {

		$user = Users::getUser($user_id);
	
		$action = 'add_msg';
		$text_btn = 'Написать сообщение';
	
		$user_html = '<div class="dialog-message__user">';
		$user_html .= '<div class="dialog-message__image">
							<a href="/users/id'.$user['id'].'">
								<img src="'.insert_image('users','small',$user['image']).'" class="img-responsive img-circle" />
							</a>
						</div>
						<div class="dialog-message__text">
							<a href="/users/id'.$user['id'].'"><strong>'.$user['name'].'</strong></a>
							<div class="dialog-message__info">
								<a href="/messages/dialog?id='.$user['id'].'" title="">Перейти к диалогу</a>
							</div>
						</div>
					</div>';
	
		$html = '<a href="/cabinet/messages" class="btn btn-default button-panel__btn" onclick="return showDialogMsg(event, '.$user_id.')">'.$text_btn.'</a>';
			
		$html .= '<div class="modal fade modal__messages" tabindex="-1" id="dialog-message'.$user_id.'" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<form class="jsform form-horizontal" role="form" action="/messages/'.$action.'" method="POST">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h5 class="modal-title">Новое сообщение</h5>						
						</div>
						<div class="modal-body">'.
							$user_html.
							html_input('hidden','user_id',$user_id).
							html_textarea('comment').'
						</div>
						<div class="modal-footer text-left">
							<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
							<button type="submit" class="btn btn-success">Отправить</button>
						</div>				
					</div>
				</form>	
			</div>
		</div>';
	
		return $html;
	}
	
	public static function countNewMessage() {

		$count = self::getCount(self::$_table_dialog,'msg_num > 0 and iuser_id = '.self::$_id_user);
		return $count;
		
	}
		
	public static function getMessage($id) {

		$item = self::getRow(self::$_table_msg,$id);
		return $item;
		
	}
			
	public static function getDialog($limit = false) {

		$where = 'iuser_id = '.self::$_id_user.' and is_delete = 0';	
		$items = self::getRows(self::$_table_dialog, 'id desc', $limit, $where);
		return $items;
		
	}
	
	public static function deleteMessage($msg_id) {

		$data = array(
			'is_delete' => 1
		);
		$where = 'id = '.$msg_id;
		self::update(self::$_table,$data,$where);
	
	}
  		
	public static function deleteDialog($dialog_id) {
		

		$data = array(
			'is_delete' => 1,
			'is_read' => 1
		);		
		$where = 'id_dialog = '.$dialog_id;
		self::update(self::$_table,$data,$where);
		
		$data = array(
			'is_delete' => 1,
			'msg_num' => 0
		);	
		$where = 'id = '.$dialog_id;
		self::update(self::$_table_dialog,$data,$where);			
	
	}
  	
	public static function readMessage($for_user_id) {
		
		$where = 'for_user_id = '.self::$_id_user.' and from_user_id = '.$for_user_id;
		self::update(self::$_table,array('is_read'=>1),$where);
		
		$where = 'iuser_id = '.self::$_id_user.' and im_user_id = '.$for_user_id;
		self::update(self::$_table_dialog,array('msg_num'=>0),$where);
		
	}
	
	public static function getMessages($for_user_id) {
		
		$id_dialog = self::createDialog(self::$_id_user,$for_user_id);		

		$where = 'id_dialog = '.$id_dialog.' and is_delete = 0';
		$items = self::getRows(self::$_table, 'id ASC', false, $where);	
		
		return $items;
		
	}
	
	
}




<?php

class Adminpanel_Controller {  

	public function defaultAction() {    

		$this->_content['title'] = "Панель управления";
	
		$comments = Database::getRows(get_table('users_comment'),'id desc',false,'pid=0 and table_name="catalog"');
		$this->_content['content'] = Render::view('adminpanel/index',array(
			'comments' => $comments
		));
		
		Render::layout('adminpanel/adminpanel', $this->_content);

	}

}
<?php

class Pages_Controller {

	public function __construct() {
		$this->_page = URL::getSegment(2);
		$this->_table = get_table($this->_page);
		$this->_config = Config::getParam('modules->'.$this->_page);
		$this->_content['title'] = 'Страницы';
	}

	public function defaultAction() {

		$items = Database::getRows($this->_table,'id desc');
	
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_list',array(
				'items' => $items
			));
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}

    public function loadAction() {
		
		$data = get_items_adminpanel($_GET,Database::getCount($this->_table));
			
		if (@$data['searchField']) $items = Database::searchAdmin($this->_table, $data['searchField'], $data['searchOper'], $data['searchString']);
		else $items = Database::getRows($this->_table, $data['order'], $data['limit']);

		$i = 0;
		foreach($items as $item) {

			$btn_edit = html_btn_edit_admin($this->_page,$item['id']);
		
			$data['rows'][$i]['id'] = $item['id'];	
			$data['rows'][$i]['cell'][] = '<a href="/'.$item['path'].'">'.$item['name'].'</a><br><small>Создан '.transform_date($item['date_create']).'</small>';			
			$data['rows'][$i]['cell'][] = html_label_active($item['active']);			
			$data['rows'][$i]['cell'][] = '<div class="btn-group">'.$btn_edit.'</div>';			
			$i++;

		}

		echo json_encode($data);
  
	}  

	public function viewAction() {

		$item = Database::getRow($this->_table,$_GET['id']);
	
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page,array(
				'item' => $item
			));

		Render::layout('adminpanel/adminpanel', $this->_content);
	}
  
	public function addAction() {

		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'item' => @$item
			));
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}  
	
	public function editAction() {

		$item = Database::getRow($this->_table,$_GET['id']);

		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'item' => $item
			));
		
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}
  
	public function saveAction() {
		
		if (isset($_POST['action'])) {

			$data = array( 
				'name' => $_POST['name'],			  		  		  		  		  		  
				'path' => $_POST['path'],	
				'active' => ((isset($_POST['active'])) ? 1 : 0),				
				'prioritet' => $_POST['prioritet'],			  		  		  		  		  		  
				'short_description' => $_POST['short_description'],			  		  		  		  		  		  
				'title' => $_POST['title'],			  		  		  		  		  		  
				'keywords' => $_POST['keywords'],			  		  		  		  		  		  
				'description' => $_POST['description']
			);

			// ДОБАВИТЬ элемент в таблице
			if ($_POST['action']=="add") {
				$data['date_create'] = date('Y-m-d');
				Database::insert($this->_table,$data);
			
			}		
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table,$data,$where);
			}

			header('Location: /adminpanel/'.$this->_page);

		}
	}

	public function deleteAction() {

		$res = array();

		if (isset($_POST['id_del'])) {
			$arr = explode(',',$_POST['id_del']);
			foreach($arr as $id) {
				Database::delete($this->_table,$id);				
			}
			$res['succes'] = true;
		} else {
			$res['succes'] = true;
			$res['message'] = "Произошла ошибка!";			
		}
		echo json_encode($res);

	}
	
	public function valid_formAction() {
		
		$res = array();	
		
		if (isset($_POST['action'])) {
		
			$path = $_POST['path'];
		
			if (Database::existField($this->_table,$path,@$_POST['id'])) {
				$res['succes'] = true;
			} else {
				$res['succes'] = false;
				$res['message'] = "Путь $path уже существует. Данные не сохранены!";
			}
			
		} 
		
		echo json_encode($res);
		
	}
  
}
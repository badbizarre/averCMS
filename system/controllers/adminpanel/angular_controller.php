<?php

class Angular_Controller {

	public function __construct() {
		$this->_page = URL::getSegment(2);
		$this->_table = get_table('ingredients');
		$this->_content['title'] = 'Angular';
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
			$data['rows'][$i]['cell'][] = '<a href="/'.$item['path'].'">'.$item['name'].'</a>';	
			$data['rows'][$i]['cell'][] = html_label_active($item['active']);					
			$data['rows'][$i]['cell'][] = '<div class="btn-group">'.$btn_edit.'</div>';			
			$i++;

		}

		echo json_encode($data);
  
	}  

	public function viewAction() {

		$item = Database::getRow($this->_table,URL::getSegment(4));

		$items = Database::getRows($this->_table);
	
		Render::view('adminpanel/'.$this->_page.'/'.$this->_page.'_edit',
			array(
				'item' => @$item,
				'items' => $items
			),
			true
		);
		
	}
 
	
	public function editAction() {

	}
  
	public function saveAction() {
		
		if (isset($_POST['action'])) {

			$data = array( 
				'name' => $_POST['name'],
				'path' => $_POST['path'],
				'active' => ((isset($_POST['active'])) ? 1 : 0),
			);

			// ДОБАВИТЬ элемент в таблице
			if ($_POST['action']=="add") {
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
		
			$name = $_POST['name'];
		
			if (Database::existField($this->_table,$name,@$_POST['id'],'name')) {
				$res['succes'] = true;
			} else {
				$res['succes'] = false;
				$res['message'] = "Название $name уже существует. Данные не сохранены!";
			}
			
		} 
		
		echo json_encode($res);
		
	}
	

    public function loadngAction() {
	

		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");

		$items = Database::getRows($this->_table, 'id asc', 20);
		echo json_encode($items);
  
	}  	
  
}
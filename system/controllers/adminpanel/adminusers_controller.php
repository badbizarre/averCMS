<?php

class Adminusers_Controller {

	public function __construct() {
		$this->_page = URL::getSegment(2);
		$this->_table = get_table($this->_page);
		$this->_config = Config::getParam('modules->'.$this->_page);
		$this->_content['title'] = 'Администраторы';
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
			$data['rows'][$i]['cell'][] = '<img src="'.insert_image($this->_page,'small',$item['image']).'" class="img-responsive center-block img-jqtable"/>';			
			$data['rows'][$i]['cell'][] = '<a href="#">'.$item['email'].'</a><br><small>Создан '.transform_date($item['date_create']).'</small>';			
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

		$image = insert_image($this->_page,'big','');
		
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'image' => $image,
				'item' => @$item
			));
		
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}  
	
	public function editAction() {

		$item = Database::getRow($this->_table,$_GET['id']);
		$image = insert_image($this->_page,'big',$item['image']);
		
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'item' => $item,
				'image' => $image
			));
		
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}
  
	public function saveAction() {
		
		if (isset($_POST['action'])) {

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
			
			$data = array( 
				'login' => $_POST['login'],			  		  		  		  		  		  
				'email' => $_POST['email'],			  		  		  		  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
			);
		
			if (!$error) {		
				
				// ДОБАВИТЬ элемент в таблице
				if ($_POST['action']=="add") {
		
					$data['image'] = ((isset($upload)) ? $upload['name'] : '');
					$data['password'] = md5($_POST['password']);
						
					Database::insert($this->_table,$data);
				
				}		
				
				// РЕДАКТИРОВАТЬ элемент в таблице
				if ($_POST['action']=="edit") {

					$item = Database::getRow($this->_table,$_POST['id']);

					$data['image'] = ((isset($upload)) ? $upload['name'] : $item['image']);
					if ($_POST['password']!=$item['password']) $data['password'] = md5($_POST['password']);
					
					$where = 'id = '.$_POST['id'];
					Database::update($this->_table,$data,$where);

					if (isset($upload)) {
						@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
						@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
					}

				}

				header('Location: /adminpanel/'.$this->_page);
				
			} else {
				
				print_r($error);
				
			}

		}
	}

	public function deleteAction() {

		$res = array();

		if (isset($_POST['id_del'])) {
			$arr = explode(',',$_POST['id_del']);
			foreach($arr as $id) {
				$item = Database::getRow($this->_table,$id);
				if (isset($item['image']) and !empty($item['image'])) {
					@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
					@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
				}
				Database::delete($this->_table,$id);				
			}
			$res['succes'] = true;
		} else {
			$res['succes'] = true;
			$res['message'] = "Произошла ошибка!";			
		}
		echo json_encode($res);

	}
  
}
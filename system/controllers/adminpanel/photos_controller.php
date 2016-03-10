<?php

class Photos_Controller {

	public function __construct() {
		$this->_page = URL::getSegment(2);
		$this->_table = get_table($this->_page);
		$this->_table_tree = get_table($this->_page.'_tree');
		$this->_config = Config::getParam('modules->'.$this->_page);
		$this->_content['title'] = 'Фотографии';
	}

	public function defaultAction() {

		$trees = Database::getRows($this->_table_tree);
	
		$this->_content['top_content'] = Render::view('adminpanel/tree',array(
			'trees' => $trees
		));
			
		$this->_content['content'] = Render::view('adminpanel/'.$this->_page.'/'.$this->_page.'_list',array(
			'trees' => $trees
		));
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}

    public function loadAction() {
			
		$where = '1';
		if (isset($_GET['id'])) $where .= ' and id_tree IN ('.get_string_id_tree($this->_table_tree,$_GET['id']).')';
				
		$data = get_items_adminpanel($_GET,Database::getCount($this->_table,$where));
			
		if (@$data['searchField']) $items = Database::searchAdmin($this->_table, $data['searchField'], $data['searchOper'], $data['searchString']);
		else $items = Database::getRows($this->_table, $data['order'], $data['limit'], $where);

		$i = 0;
		foreach($items as $item) {

			$btn_edit = html_btn_edit_admin($this->_page,$item['id']);
		
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '<img src="'.insert_image($this->_page,'small',$item['image']).'" class="img-responsive center-block img-jqtable"/>';			
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = html_label_active($item['active']);			
			$data['rows'][$i]['cell'][] = '<div class="btn-group">'.$btn_edit.'</div>';
			$i++;

		}

		echo json_encode($data);
  
	}  
  
	public function addfileAction() {

		if (isset($_FILES['file'])) {
			
			$file_ary = reArrayFiles($_FILES['file']);
			
			foreach ($file_ary as $file) {
				
				if ($file['name']) { 
			
					$upload = Database::uploadImage($file, $this->_config['image']);

					if ($upload['error']) return;
					
				}
				
				$data = array( 
					'name' => $upload['name'],			  		  		  
					'id_tree' => $_POST['id_tree'],			  		  		  
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'image' => ((isset($upload)) ? $upload['name'] : '')
				);
			
				Database::insert($this->_table,$data);
			
			}
		
		}		
		
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
		$trees = Database::getRows($this->_table_tree);
		
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'trees' => $trees,
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
				'name' => $_POST['name'],			  		  		  		  		  		  
				'id_tree' => $_POST['id_tree'],			  		  		  		  		  		  
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
	
	//Получение данных по id в форму для добавления товара
	public function open_treeAction() {
		$data = array();
		$data = Database::getRow($this->_table_tree,$_POST['id']);
		echo json_encode($data);
	}
	
	public function edit_treeAction() {

		$res = array();
		
		$path = $_POST['path'];
		
		$data = array(
			'pid' => $_POST['pid'],
			'level' => $_POST['level'],
			'title' => $_POST['title'],
			'keywords' => $_POST['keywords'],
			'description' => $_POST['description'],		  
			'name' => $_POST['name'],			  		  		  
			'active' => ((isset($_POST['active'])) ? 1 : 0),
			'short_description' => $_POST['short_description'],
			'path' => $path,
			'prioritet' => $_POST['prioritet']
		);	
		
		$res['succes'] = true;
							
		// ДОБАВИТЬ элемент в таблицу
		if ($_POST['action']=="add") {
		
			if (Database::existField($this->_table_tree,$path,false)) {
				Database::insert($this->_table_tree,$data);
			} else {
				$res['succes'] = false;
				$res['message'] = "Путь $path уже существует";
			}				
		}
	
		// РЕДАКТИРОВАТЬ элемент в таблице
		if ($_POST['action']=="edit") {
			$where = 'id = '.$_POST['id'];
			Database::update($this->_table_tree,$data,$where);	
		}		
	
		$res['tree']  = get_tree(Database::getRows($this->_table_tree));		
		
		echo json_encode($res);

	}

	public function delete_treeAction() {

		$res = array();

		if (isset($_POST['id_del'])) {
			Database::delete($this->_table_tree,$_POST['id_del']);
			$res['succes'] = true;
		} else {
			$res['succes'] = true;
			$res['message'] = "Произошла ошибка!";			
		}
	
		$res['tree']  = get_tree(Database::getRows($this->_table_tree));		
				
		echo json_encode($res);

	}
	
}
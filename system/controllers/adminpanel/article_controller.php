<?php

class Article_Controller {

	public function __construct() {
		$this->_page = URL::getSegment(2);
		$this->_table = get_table($this->_page);
		$this->_table_tree = get_table($this->_page.'_tree');
		$this->_table_cat = get_table($this->_page.'_categories');
		$this->_config = Config::getParam('modules->'.$this->_page);
		$this->_content['title'] = 'Статьи';
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
				
		$table = $this->_table.' AS t1 JOIN '.$this->_table_cat.' as t2 ON t1.id = t2.id_article';		
				
		$data = get_items_adminpanel($_GET,Database::getCount($table,$where,"distinct id"));
			
		if (@$data['searchField']) $items = Database::searchAdmin($this->_table, $data['searchField'], $data['searchOper'], $data['searchString']);
		else $items = Database::getRows($table, $data['order'], $data['limit'], $where, 'id');

		$i = 0;
		foreach($items as $item) {

			$btn_edit = html_btn_edit_admin($this->_page,$item['id']);
		
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '<img src="'.insert_image($this->_page,'small',$item['image']).'" class="img-responsive center-block img-jqtable"/>';			
			$data['rows'][$i]['cell'][] = '<a href="/article/'.$item['path'].'" target="_ablank">'.$item['name'].'</a>';
			$data['rows'][$i]['cell'][] = html_label_active($item['active']);			
			$data['rows'][$i]['cell'][] = '<div class="btn-group">'.$btn_edit.'</div>';
			$i++;

		}

		echo json_encode($data);
  
	}  

	public function addAction() {

		$image = insert_image($this->_page,'big','');
		$trees = Database::getRows($this->_table_tree);
		
		$values = array();
		
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'item' => @$item,
				'trees' => $trees,			
				'image' => $image,
				'values' => $values
			));
		
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}  
	
	public function editAction() {


		if (!isset($_GET['id'])) header('Location: /adminpanel/');

		$item = Database::getRow($this->_table,$_GET['id']);
		$image = insert_image($this->_page,'big',$item['image']);
		$trees = Database::getRows($this->_table_tree);	
		
		$values = array();		
		$elems = Database::getRows($this->_table_cat,'id_categories asc',false,'id_article = '.$_GET['id']);
		foreach($elems as $elem) {
			$values[] = $elem['id_tree'];
		}
		
		$this->_content['content'] = Render::view(
			'adminpanel/'.$this->_page.'/'.$this->_page.'_edit',array(
				'item' => $item,			
				'trees' => $trees,
				'image' => $image,
				'values' => $values
			));
		
		
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}
  
	public function saveAction() {
		
		if (isset($_POST['action'])) {

			$error = array();
			$param = array();
			$response = array();
			
			if ($_FILES['image']['name']) { 

				if (!empty($_POST['datay']) and !empty($_POST['datax'])) {
					$param = array(
						'width' => $_POST['width'],
						'height' => $_POST['height'],
						'y' => $_POST['datay'],
						'x' => $_POST['datax'],
					);
				}
				
				$filename = $_POST['path'];
				
				$upload = Database::uploadImage($_FILES['image'], $this->_config['image'], $param, $filename);

				if ($upload['error']) {$error['image'] = $upload['error'];}
				
			}		
			
			$name = htmlspecialchars($_POST['name']);
			
			$data = array( 
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'name' => $name,			  		  		  		  		  		  
				'path' => $_POST['path'],	
				'recept' => htmlspecialchars($_POST['recept']),			  		  		  		  		  		  
				'date_create' => date('Y-m-d H:i:s'),			  		  		  		  		  		  
				'short_description' => $_POST['short_description'],
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description']
			);

			if (!$error) {		
				
				// ДОБАВИТЬ элемент в таблице
				if ($_POST['action']=="add") {
		
					$data['image'] = ((isset($upload)) ? $upload['name'] : '');
								
					Database::insert($this->_table,$data);
					
					$id_article = Database::getLastId($this->_table);
				
				}		
				
				// РЕДАКТИРОВАТЬ элемент в таблице
				if ($_POST['action']=="edit") {

					$item = Database::getRow($this->_table,$_POST['id']);

					$data['image'] = ((isset($upload)) ? $upload['name'] : $item['image']);
			
					$where = 'id = '.$_POST['id'];
					Database::update($this->_table,$data,$where);

					if (isset($upload)) {
						@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
						@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
					}
					
					$id_article = $_POST['id'];

				}
				
				if (!empty($_POST['id_tree'])) {
					Database::clearTable($this->_table_cat,"id_article=$id_article");
					foreach($_POST['id_tree'] as $id_tree) {
						$data_cat = array(
							'id_article' => $id_article,
							'id_tree' => $id_tree
						);
						Database::insert($this->_table_cat,$data_cat);
					}
				}

				$response['success'] = true;
				$response['message'] = 'Данные успешно сохранены';
				$response['url'] = '/adminpanel/'.$this->_page;
	
			} else {
				$response['success'] = false;
				$response['message'] = $error['image'];	
			}
			
			echo js_response($response);
	
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
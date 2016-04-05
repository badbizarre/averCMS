<?php

class Recepty_Controller {
  private $_content, $_config, $_table;

	public function __construct() {
		
		$this->_page = 'catalog';
		
		$this->_config = Config::getParam('modules->'.$this->_page);
		
		$this->_table = get_table($this->_page);
		
		$this->_table_tree = get_table($this->_page.'_tree');
		
		$this->_table_catalog_ingredients = get_table($this->_page.'_ingredients');
		
		$this->_table_ingredients = get_table('ingredients');
		
		$this->_table_measures = get_table('measures');
		
		$this->_table_cat = get_table($this->_page.'_categories');

		$this->_table_users_recept = get_table('users_recept');
		
		$this->_content['left'] = Render::view('cabinet/razdel').Render::view('catalog/razdel');
 		
		$this->_id_user = cmsUser::sessionGet('user:id'); 	
		
	}

	public function defaultAction() {

		$where = 'active = 1';

		$totals = Database::getCount($this->_table,$where);	
						
		$pagination = new Pagination (
			$totals,
			$this->_config['pagination']['rows_on_page'],
			$this->_config['pagination']['link_by_side'],
			$this->_config['pagination']['url_segment']
			);
			
		$items = Database::getRows($this->_table, 'id DESC', $pagination->getLimit(),$where);	
		Pages::fetchContent(URL::getPath());
		$this->_content['content'] = Render::view (
			$this->_page.'/list', Array (
				'items' => $items,
				'h1item' => Pages::getName(),
				'pagination' => $pagination->getPagination()
			)
		);

		Render::layout('page',  $this->_content);
		
	}

	public function detailedAction() {
  
		$tree = Database::getRow($this->_table_tree,URL::getSegment(3),'path');
  
		if (!empty($tree)) {
			
			$this->_content['h1'] = $tree['name'];
			$this->_content['title'] = $tree['title'];
			$this->_content['keywords'] = $tree['keywords'];
			$this->_content['description'] = $tree['description'];
			
			$breadcrumbs = get_crumbs_category($tree['id']);
			
			$where = 'active = 1 and id_tree IN ('.get_string_id_tree($this->_table_tree,$tree['id']).')';
			
			$table = $this->_table.' AS t1 JOIN '.$this->_table_cat.' as t2 ON t1.id = t2.id_catalog';

			$totals = Database::getCount($table,$where,"distinct id");	
							
			$pagination = new Pagination (
				$totals,
				$this->_config['pagination']['rows_on_page'],
				$this->_config['pagination']['link_by_side'],
				$this->_config['pagination']['url_segment']
				);
				
			$items = Database::getRows($table, 'id DESC', $pagination->getLimit(),$where,'id');
				
			$this->_content['content'] = Render::view (
				$this->_page.'/list', Array (
					'items' => $items,
					'pagination' => $pagination->getPagination(),
					'tree' => $tree,
					'breadcrumbs' => $breadcrumbs
				)
			);

		} 
		Render::layout('page',  $this->_content);
	
	}
	
	public function searchAction() {
    
		$this->_content['h1'] = 'Результаты поиска';
		$this->_content['title'] = 'Результаты поиска';
		
		$q = htmlspecialchars($_GET['q']);
		$items = Database::searchAdmin($this->_table,'name','cn',$q,' or short_description LIKE "%'.$q.'%"');

		if ($items) {
			$this->_content['content'] = Render::view(
				$this->_page.'/list', Array(
					'items' => $items,
					'pagination' => '',
					'imagepath' => $this->_config['image'],
			));
		} else {
			$this->_content['content'] = Render::view(
				$this->_page.'/list', Array(
					'items' => @$items,
					'h1item' => 'По запросу &laquo;'.$_GET['q'].'&raquo; ничего не найдено',
					'pagination' => '',
					'imagepath' => $this->_config['image'],
			));		
		}

		Render::layout('page', $this->_content);
	
	}
	
	public function update_htmlAction() {
		
		$response = array();
		$response['succes'] = false;
		
		if (!empty($this->_id_user)) {
			
			$id_table = $_POST['id_table'];
				
			if (is_numeric($id_table)) {

				Users_like::addLike($this->_page);					

				$item = Database::getRow($this->_table,$id_table);
										
				$response['succes'] = true;
				$response['html'] = get_buttons_recept($item,$this->_page);
			
			}
			
		} else {
			
			$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';
			
		}

		echo json_encode($response);

	}
	
	function update_commentAction() {
			
		$response = array();
		$response['succes'] = false;

		if (isset($_POST['action']) and !empty($_POST['action'])) {

			if (!empty($this->_id_user)) {

				if ($_POST['action']=='add') {
	
					$id_table = $_POST['id_table'];
	
					Comments::addComment($this->_page);
					
				} else {

					$id = $_POST['id'];
					$id_table = Comments::getIdTable($id);
						
					if ($_POST['action']=='edit') {
						
						Comments::updateComment($id);
						
					}
						
					if ($_POST['action']=='remove') {


						Comments::removeComment($id);
					
					}											

				}
				
				$response['html'] = html_comment_content(Comments::getComments($id_table,$this->_page));
				$response['id'] = $id_table;				
				$response['succes'] = true;
				
			} else {
				$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';
			}
			
		} else {
			$response['message'] = 'Произошла ошибка';
		}
		
		echo json_encode($response);

	}
	
	function edit_formAction() {
		
		$response = array();
		
		if (isset($_POST['id_table']) and !empty($_POST['id_table'])) {
				
			$response['succes'] = true;
			$response['html'] = get_comment_form_edit($_POST['id_table'],'recepty');
			
		} else {
			$response['message'] = 'Произошла ошибка';			
		}
		
		echo json_encode($response);
				
	}
	
	function add_receptAction() {
    
		$this->_content['h1'] = 'Добавление рецепта';
		$this->_content['title'] = 'Добавление рецепта';
		
		$image = insert_image($this->_page,'big','');
		$trees = Database::getRows($this->_table_tree);
		$ingredients = Database::getRows($this->_table_ingredients,'name asc');
		$measures = Database::getRows($this->_table_measures);
		
		$values = array();
	
		if (isset($_POST['url'])) {
			$item = parse_by_povarenok_ru($_POST['url']);
			$catalog_ingredients = get_ing_by_parse(@$item['ing']);
		}
			
		$this->_content['content'] = Render::view (
			$this->_page.'/add', Array (
				'item' => @$item,
				'trees' => $trees,			
				'image' => $image,
				'values' => $values,
				'ingredients' => $ingredients,
				'measures' => $measures,
				'img_size' => $this->_config['image']['small'],
				'catalog_ingredients' => @$catalog_ingredients
			)
		);


		Render::layout('page',  $this->_content);
			

	}	
  
	public function saveAction() {

		$error = array();
		$param = array();
		$response = array();
				
		if (isset($_POST['name']) and !empty($_POST['name'])) {
			
			if (empty($_POST['id_tree'])) {
				$response['success'] = false;
				$response['message'] = "Необходимо выбрать раздел!";	
				echo js_response($response);
				return;		
			}
			
			if (empty($_POST['id_ingredient'][0])) {
				$response['success'] = false;
				$response['message'] = "Необходимо выбрать Ингредиент!";	
				echo js_response($response);
				return;				
			}
				
			$filename = translit_path($_POST['name']);
				
			if ($_FILES['image']['name']) { 

				if (!empty($_POST['datay']) and !empty($_POST['datax'])) {
					$param = array(
						'width' => $_POST['width'],
						'height' => $_POST['height'],
						'y' => $_POST['datay'],
						'x' => $_POST['datax'],
					);
				}

				$upload = Database::uploadImage($_FILES['image'], $this->_config['image'], $param, $filename);

				if ($upload['error']) {$error['image'] = $upload['error'];}
				
			}		

			$data = array( 
				'active' => 0,
				'name' => htmlspecialchars($_POST['name']),
				'time' => htmlspecialchars($_POST['time']),			  		  		  		  		  		  
				'recept' => htmlspecialchars($_POST['recept']),
				'path' => $filename,
				'date_create' => date('Y-m-d H:i:s'),			  		  		  		  		  		  
				'short_description' => $_POST['short_description']
			);

			if (!$error) {		

				$data['image'] = ((isset($upload)) ? $upload['name'] : '');
							
				Database::insert($this->_table,$data);
				
				$id_catalog = Database::getLastId($this->_table);

				Database::clearTable($this->_table_cat,"id_catalog=$id_catalog");
				foreach($_POST['id_tree'] as $id_tree) {					
					$data_cat = array(
						'id_catalog' => $id_catalog,
						'id_tree' => $id_tree
					);
					Database::insert($this->_table_cat,$data_cat);
				}
			
				Database::clearTable($this->_table_catalog_ingredients,"id_catalog=$id_catalog");
				foreach($_POST['id_ingredient'] as $key => $item) {
					if (!@$_POST['id_ingredient'][$key]) continue;
					$data_ing = array(
						'id_catalog' => $id_catalog,
						'id_ingredient' => @$_POST['id_ingredient'][$key],
						'kolvo' => @$_POST['kolvo'][$key],
						'id_measure' => @$_POST['id_measure'][$key]
					);
					Database::insert($this->_table_catalog_ingredients,$data_ing);
				}
				
				if (cmsUser::isSessionSet('user')) {
					$id_user = cmsUser::sessionGet('user:id');
					$data_add_recept = array(
						'id_catalog' => $id_catalog,
						'id_user' => $id_user
					);
					Database::insert($this->_table_users_recept,$data_add_recept);				
				}
							
				$response['success'] = true;
				$response['message'] = 'Данные успешно сохранены';
				$response['url'] = '/recepty';
	
			} else {
				$response['success'] = false;
				$response['message'] = $error['image'];	
			}

		} else {
			$response['success'] = false;
			$response['message'] = 'Заполните пожалуйсто поле "Название".';			
		}
			
		echo js_response($response);
			
	}
		
}
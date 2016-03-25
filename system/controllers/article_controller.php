<?php

class Article_Controller {
  private $_content, $_config, $_table;

	public function __construct() {
		
		$this->_page = URL::getSegment(1);
		
		$this->_config = Config::getParam('modules->'.$this->_page);
		
		$this->_table = get_table($this->_page);
		
		$this->_table_tree = get_table($this->_page.'_tree');
		
		$this->_table_cat = get_table($this->_page.'_categories');
		
		$this->_table_comment = get_table('users_comment');
		
		$this->_table_users_like = get_table('users_like');

		$this->_content['left'] = Render::view ('article/razdel').Render::view ('cabinet/razdel');
  			
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
		$item = Database::getRow($this->_table,URL::getSegment(3),'path');

		if (!empty($tree)) {
			
			$this->_content['h1'] = $tree['name'];
			$this->_content['title'] = $tree['title'];
			$this->_content['keywords'] = $tree['keywords'];
			$this->_content['description'] = $tree['description'];
			
			$breadcrumbs = get_crumbs_article($tree['id']);
			
			$where = 'active = 1 and id_tree IN ('.get_string_id_tree($this->_table_tree,$tree['id']).')';
			
			$table = $this->_table.' AS t1 JOIN '.$this->_table_cat.' as t2 ON t1.id = t2.id_article';

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

		} elseif(!empty($item)) {

			$this->_content['h1'] = $item['name'];
			$this->_content['title'] = $item['title'];
			$this->_content['keywords'] = $item['keywords'];
			$this->_content['description'] = $item['description'];
			
			Database::update($this->_table,array('count_show' => ++$item['count_show']),"id=".$item['id']);
			
			$breadcrumbs = get_crumbs_article(Database::getField(get_table('article_categories'),$item['id'],'id_article','id_tree'));
			
			$comments = get_comments_where($item['id'],$this->_page);
			
			$this->_content['content'] = Render::view (
				$this->_page.'/detailed', Array (
					'item' => $item,
					'breadcrumbs' => $breadcrumbs,
					'comments' => $comments
				)
			);
	
		}
		Render::layout('page',  $this->_content);
	
	}
	
	public function update_htmlAction() {
		
		$response = array();
		$response['succes'] = false;
		
		/* variate, 1: like, 2: note */

		if (isset($_POST['variate'])) {

			if (isset($_POST['id_table']) and !empty($_POST['id_table'])) {
				$id_table = $_POST['id_table'];
				$id_user = cmsUser::sessionGet('user:id');
				
				if (!empty($id_user)) {
					
					$id_variate = $_POST['variate'];
								
					if (is_numeric($id_table)) {

						$data = array(
							'id_user' => $id_user,
							'id_table' => $id_table,
							'id_variate' => $id_variate,
							'table_name' => $this->_page
						);
						$users_like = get_users_variate($id_table,$id_variate,$this->_page);
						
						if (isset($users_like) and !empty($users_like)) {	
							
							Database::delete($this->_table_users_like,$users_like['id']);
													
						} else {
							
							Database::insert($this->_table_users_like,$data,'id='.$users_like['id']);
														
						}					

						$item = Database::getRow($this->_table,$id_table);
												
						$response['succes'] = true;
						$response['html'] = get_buttons_recept($item,$this->_page);
					}
					
				} else {
					
					$response['message'] = 'Зарегистрируйтесь или войдите в учетную запись';
					
				}
				
			}
	
		}
		
		echo json_encode($response);

	}
	
	function update_commentAction() {
			
		$response = array();
		$response['succes'] = false;

		if (isset($_POST['action']) and !empty($_POST['action'])) {

			$id_user = cmsUser::sessionGet('user:id');
			
			if (!empty($id_user)) {

				if ($_POST['action']=='add') {
	
					$comment = htmlspecialchars($_POST['comment']);
					
					$id_table = $_POST['id_table'];
					
					$data = array(
						'id_user' => $id_user,
						'id_table' => $id_table,
						'table_name' => $this->_page,
						'comment' => $comment,
						'date_create' => date('Y-m-d H:i:s'),
						'pid' => $_POST['pid']
					);		
					Database::insert($this->_table_comment,$data);
					
				} else {
						
					$id = $_POST['id'];			
					$comment = Database::getRow($this->_table_comment,$id);
					$id_table = $comment['id_table'];
											
					if ($_POST['action']=='edit') {
						
						$data = array(
							'comment' => htmlspecialchars($_POST['comment'])
						);
					
						Database::update($this->_table_comment,$data,'id='.$id);
						
					}
					
					if ($_POST['action']=='remove') {
						
						Database::delete($this->_table_comment,$id);
					
					}

				}

				$comments = get_comments_where($id_table,$this->_page);
				$response['html'] = html_comment_content($comments);
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
			$response['html'] = get_comment_form_edit($_POST['id_table'],$this->_page);
			
		} else {
			$response['message'] = 'Произошла ошибка';			
		}
		
		echo json_encode($response);
				
	}
	
}
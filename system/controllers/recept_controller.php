<?php

class Recept_Controller {
  private $_content, $_config, $_table;

	public function __construct() {
		
		$this->_page = 'catalog';
		
		$this->_config = Config::getParam('modules->'.$this->_page);
		
		$this->_table = get_table($this->_page);
		
		$this->_table_tree = get_table($this->_page.'_tree');
		
		$this->_content['left'] = Render::view ('catalog/razdel');
  		
	}

	public function defaultAction() {
 
		$item = Database::getRow($this->_table,URL::getSegment(3),'path');

		if ($item) {
			$this->_content['h1'] = $item['name'];
			$this->_content['title'] = $item['title'];
			$this->_content['keywords'] = $item['keywords'];
			$this->_content['description'] = $item['description'];
			
			Database::update($this->_table,array('count_show' => ++$item['count_show']),"id=".$item['id']);
			
			$html_ingredient = get_html_ingredient($item['id']);
			$breadcrumbs = get_crumbs_category(Database::getField(get_table('catalog_categories'),$item['id'],'id_catalog','id_tree'));

			$comments = get_comments_where($item['id'],$this->_page);
			
			$this->_content['content'] = Render::view (
				$this->_page.'/detailed', Array (
					'item' => $item,
					'html_ingredient' => $html_ingredient,
					'breadcrumbs' => $breadcrumbs,
					'comments' => $comments
				)
			);
			
		}
		Render::layout('page',  $this->_content);
	
	}

}
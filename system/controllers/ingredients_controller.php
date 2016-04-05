<?php

class Ingredients_Controller {
	private $_table, $_content;

	public function __construct() {	
	
		$this->_page = URL::getSegment(1);
		$this->_table = get_table($this->_page);
		$this->_table_catalog_ingredients = get_table('catalog_'.$this->_page);
		$this->_table_catalog = get_table('catalog');
		$this->_config_catalog = Config::getParam('modules->catalog'); 
	
		$this->_content['left'] = Render::view('cabinet/razdel').Render::view('catalog/razdel');
  		
	}

	public function defaultAction() {
	
		$items = Database::getRows($this->_table,'name asc');

		$this->_content['content'] = Render::view ($this->_page.'/list',array(
			'items' => $items,
		));
	
		Render::layout('page',  $this->_content);
	
	}

	public function detailedAction() {
  
		$tree = Database::getRow($this->_table,URL::getSegment(3),'path');
  
		if (!empty($tree)) {
			
			$this->_content['h1'] = $tree['name'].' - рецепты';
			$this->_content['title'] = $tree['name'].' - рецепты';
			$this->_content['keywords'] = $tree['name'].' - рецепты';
			$this->_content['description'] = $tree['name'].' - рецепты';
			
			$items = Database::getRows($this->_table_catalog_ingredients,'id asc',false,'id_ingredient='.$tree['id'],'id_catalog');
			$keys = get_keys_items($items,'id_catalog');
	
			$where = 'id IN('.$keys.')';
			
			$totals = Database::getCount($this->_table_catalog,$where);
							
			$pagination = new Pagination (
				$totals,
				$this->_config_catalog['pagination']['rows_on_page'],
				$this->_config_catalog['pagination']['link_by_side'],
				$this->_config_catalog['pagination']['url_segment']
				);
							
			$elems = Database::getRows($this->_table_catalog,'id asc',$pagination->getLimit(),$where);

			$this->_content['content'] = Render::view (
				'catalog/list', Array (
					'items' => $elems,
					'pagination' => $pagination->getPagination(),
					'tree' => $tree,
					'breadcrumbs' => NULL
				)
			);			
			
		} 
		Render::layout('page',  $this->_content);
	
	}
		  
}
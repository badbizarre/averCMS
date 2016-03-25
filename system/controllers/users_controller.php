<?php

class Users_Controller {
  private $_content, $_config, $_table;

	public function __construct() {
		
		$this->_page = URL::getSegment(1);
		
		$this->_config = Config::getParam('modules->'.$this->_page);
		
		$this->_table = get_table($this->_page);
		
		$this->_content['left'] = Render::view ('cabinet/razdel').Render::view('catalog/razdel');
  			
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
			
		$items = Database::getRows($this->_table, 'id DESC', $pagination->getLimit(), $where);	

		$this->_content['content'] = Render::view (
			$this->_page.'/list', Array (
				'items' => $items,
				'pagination' => $pagination->getPagination()
			)
		);

		Render::layout('page',  $this->_content);
		
	}
	
	public function detailedAction() {
		
		$user_id = intval(str_replace('id','',URL::getSegment(3)));
		$item = Database::getRow($this->_table,$user_id);
		$image = insert_image('users','big',$item['image']);

		$sex = get_array_sex();

		$this->_content['h1'] = get_user_name($item);
				
		$this->_content['content'] = Render::view ($this->_page.'/user_info',array(
			'item' => $item,
			'image' => $image,
			'sex' => $sex[$item['sex']]
		));
	
		Render::layout('page',  $this->_content);		
		
	}
	
}
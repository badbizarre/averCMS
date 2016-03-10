<?php

class Page_Controller{
	private $_content;

	public function __construct() {	
	
		$this->_content['left'] = Render::view ('catalog/razdel');
  	
	}

	public function defaultAction() {

		Render::layout('page', $this->_content);
		
	}
	
}
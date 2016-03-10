<?php

class Index_Controller {
    private $_content, $_config;

	public function defaultAction() {

		$items = Database::getRows(get_table('catalog_tree'),'prioritet desc',8,'active=1 and pid=1');

		$this->_content['content'] = Render::view('catalog/block_index',array(
			'items' => $items
		)).Render::view('center');

		Render::layout('page', $this->_content);
		
	}

}
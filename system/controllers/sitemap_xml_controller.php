<?php

class Sitemap_XML_Controller {
  public function defaultAction() {
    Load::model('Pages');
    $data['pages'] = Pages::getPagesList();
    $data['trees'] = Database::getRows(get_table('catalog_tree'),'id asc',false);    
	$data['products'] = Database::getRows(get_table('catalog'),'id asc',false);
    header('Content-Type: application/xml');
    Render::layout('sitemap', $data);
  }
}
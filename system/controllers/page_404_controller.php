<?php

class Page_404_Controller {
	public function defaultAction() {
	
		header("HTTP/1.0 404 Not Found");
		Log::write('Страница не существует', FALSE, TRUE);
		
		$data = Array ();
		
		Render::layout('page_404', $data);
	
	}
}
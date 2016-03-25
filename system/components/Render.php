<?php

class Render {
	
	public static function letters($path, $data = Array()) {
		
		$path = Config::getParam('components->render->dirs->letters') . $path . '_letter.php';
		$letter = self::_render($path, $data);
		return $letter;
		
	}

	public static function view($path, $data = Array(), $display = false) {
		
		$path = Config::getParam('components->render->dirs->views') . $path . '_view.php';
		$view = self::_render($path, $data);

		if (!$display) {
			return $view;
		}

		echo $view;
		
	}

	public static function layout($path, $data = Array(), $display = true) {
		
		$path = Config::getParam('components->render->dirs->layouts') . $path . '_layout.php';
		$layout = self::_render($path, $data);

		if (!$display) {
			return $layout;
		}

		echo $layout;
		
	}

	private static function _render($path, $data) {
    
		if (!file_exists($path)) {
			trigger_error('Не найден файл отображения! ['.basename($path).']!', E_USER_ERROR);
		}

		if ($data) {
			extract($data);
		}

		ob_start();
		require_once $path;
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
		
	}
	
}
<?php

class Load {
	private static
		$_config = Array(),
		$_components = Array(),
		$_library = Array();

	public static function init() {
		self::$_config = get_config();
		self::$_config = self::$_config['components']['load'];
	}

	public static function component($components) {
	  
		if (is_string($components)) {
			$components = Array($components);
		}

		foreach ($components as $component_name) {
			if (isset(self::$_components[$component_name])) {
				continue;
			}

			$component_path = self::$_config['dirs']['components'] . "{$component_name}.php";
			self::_requireClass($component_path, $component_name, 'компонента');

			if (method_exists($component_name, 'init')) {
				call_user_func(Array($component_name, 'init'));
			}

			self::$_components[$component_name] = TRUE;
		}
	
	}

	public static function controller($controller, $action = '') {
		
		$controller_name = basename($controller) . '_Controller';
		$controller_path = self::$_config['dirs']['controllers'] . "{$controller}_controller.php";
		self::_requireClass($controller_path, $controller_name, 'контроллера');
		$controller_obj = new $controller_name();

		if (!empty($action)) {
			if (!method_exists($controller_obj, $action)) {
				trigger_error('Действие ['.str_replace('Action', '', $action).'] не существует!', E_USER_ERROR);
			}

			return $controller_obj->$action();
		}

		return $controller_obj;
		
	}

	public static function model($models) {
		
		if (!is_array($models)) {
			$models = Array($models);
		}

		foreach ($models as $model) {
			$model_name = basename($model);
			$model_path = self::$_config['dirs']['models'] . "{$model}.php";
			self::_requireClass($model_path, $model_name, 'модели');

			if (method_exists($model_name, 'init')) {
				call_user_func(Array($model_name, 'init'));
			}
		}
		
	}

	public static function library($library) {
	  
		if (!is_array($library)) {
			$library = Array($library);
		}

		foreach ($library as $library) {
			$library_name = basename($library);

			if (isset(self::$_library[$library_name])) {
				return;
			}

			$library_path = self::$_config['dirs']['library'] . $library;

			if (!file_exists($library_path)) {
				trigger_error('Не найден файл библиотеки ['.$library_name.']!', E_USER_ERROR);
			}

			require_once $library_path;
			self::$_library[$library_name] = TRUE;
		}
	
	}

	private static function _requireClass($class_path, $class_name, $type_rus) {
		
		if (!file_exists($class_path)) {
			trigger_error('Не найден файл '.$type_rus.' ['.$class_name.']!', E_USER_ERROR);
		}

		require_once $class_path;

		if (!class_exists($class_name)) {
			trigger_error('Не найден класс '.$type_rus.' ['.$class_name.']!', E_USER_ERROR);
		}
		
	}
}
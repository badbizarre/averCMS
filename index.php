<?php

define('ROOT', rtrim(realpath(dirname(__FILE__)), '/') . '/');
define('CONFIG_PATH', ROOT.'system/config.php');

function send_header_503() {
  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
  }
}

function no_cache() {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}

function get_config() {
  static $conf;

  if (is_array($conf)) {
    return $conf;
  }

  if (!file_exists(CONFIG_PATH)) {
    send_header_503();
    trigger_error('Не найден файл конфигурации ['.basename(CONFIG_PATH).']!', E_USER_ERROR);
  }

  require_once CONFIG_PATH;

  if (empty($config) || !is_array($config)) {
    send_header_503();
    trigger_error('Файл конфигурации ['.basename(CONFIG_PATH).'] повреждён!', E_USER_ERROR);
  }

  return $conf = $config;
}

function load_component($components) {
  $config = get_config();

  foreach ($components as $component) {
    $component_path = $config['components']['load']['dirs']['components'] . "{$component}.php";

    if (!file_exists($component_path)) {
      send_header_503();
      trigger_error('Не найден компонент ['.$component.']!', E_USER_ERROR);
    }

    require_once $component_path;

    if (!class_exists($component)) {
      send_header_503();
      trigger_error('Не найден класс компонента ['.$component .']!', E_USER_ERROR);
    }

    if (method_exists($component, 'init')) {
      call_user_func(Array($component, 'init'));
    }
  }
}

ob_start();

header('Content-Type: text/html; charset=utf-8');

$config = get_config();

date_default_timezone_set($config['env']['timezone']);
mb_internal_encoding($config['env']['doc_encoding']);

if ($config['env']['cache']) {
  no_cache();
}

if ($config['env']['session_auto_start']) {
  session_start();
}

load_component(Array('Log', 'Load'));

if(@$_SESSION['user']['manager']!=1) $_SESSION['currency'] = 'byr';  
	
$config = $config['components']['load']['autoload'];

Load::library($config['library']);
Load::component($config['components']);
Load::model($config['models']);
Load::controller($config['controller']);
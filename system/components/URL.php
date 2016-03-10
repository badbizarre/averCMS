<?php

class URL {
  private static

    /*** @var String */
    $_url_path = '',

    /*** @var Array */
    $_url_segments = Array(),

    /*** @var String */
    $_controller = '',

    /*** @var String */
    $_action = '',

    /*** @var String */
    $_path = '';

  public static function init() {
    self::_fetchUrlString();
    self::$_url_path = trim(strtolower(self::$_url_path), '/');
    self::_applyUrlFilter();
    self::_applyRoute();
    self::_splitUrl();
    self::_fetchControllerAndAction();
  }

  public static function getUrlString() {
    return '/'.self::$_url_path.'/';
  }

  public static function getSegment($num) {
    if (empty(self::$_url_segments)) {
      return NULL;
    }

    $_url_segments = self::$_url_segments;
    array_unshift($_url_segments, '');

    if (!isset($_url_segments[$num])) {
      return NULL;
    }

    return $_url_segments[$num];
  }

  public static function getController() {
    return self::$_controller;
  }

  public static function getAction() {
    return self::$_action;
  }

  public static function getPath() {
    return self::$_path;
  }

  private static function _fetchUrlString() {
    if (isset($_SERVER['PATH_INFO'])) {
      self::$_url_path = $_SERVER['PATH_INFO'];
    } else {
      self::$_url_path = @getenv('PATH_INFO');
    }

    if (self::$_url_path != '') {
      return self::$_url_path;
    }

    if (isset($_SERVER['ORIG_PATH_INFO'])) {
      self::$_url_path = $_SERVER['ORIG_PATH_INFO'];
    } else {
      self::$_url_path = @getenv('ORIG_PATH_INFO');
    }

    if (self::$_url_path != '') {
      return self::$_url_path;
    }

    return FALSE;
  }

  private static function _applyRoute() {
    $routes = Config::getParam('components->url->routes');

    foreach ($routes as $pattern => $subject) {
      if (preg_match($pattern, self::$_url_path, $matches)) {
        
        array_shift($matches);

        if (!empty($matches)) {
          $i = 1;

          foreach ($matches as $value) {
            $subject = str_replace('$'.$i++, $value, $subject);
          }
        }

        self::$_url_path = $subject;
        break;
      }
    }
  }

  private static function _applyUrlFilter() {
    if (!self::$_url_path) return NULL;

  //  $chars = Config::getParam('components->url->permitted_url_chars');

  //  if (!preg_match("|^[{$chars}]+$|is", self::$_url_path)) {
 //     trigger_error('В URL найдены недопустимые символы!', E_USER_ERROR);
  //  }
  }

  private static function _splitUrl() {
    if (self::$_url_path == '') {
      return FALSE;
    }

    self::$_url_segments = explode('/', self::$_url_path);
  }

  private static function _fetchControllerAndAction() {
    $url_segments = self::$_url_segments;
    $count_segment = count(self::$_url_segments);
    $i = 0;

    while ($url_segments) {
      $implode = implode('/', $url_segments);
      $path = Config::getParam('components->load->dirs->controllers').$implode.'_controller.php';

      if (file_exists($path)) {
        self::$_controller = $implode;
        $action_pos = ($count_segment - $i);
        $action = (isset(self::$_url_segments[$action_pos])) ? self::$_url_segments[$action_pos] : '';
        self::$_path = end($url_segments);

        if (strpos($action, ':') !== FALSE) {
          self::_fetchParams($action_pos);
          break;
        }

        if (!empty($action)) {
          self::$_action = $action . 'Action';
          self::_fetchParams($action_pos + 1);
        }

        break;
      }

      array_pop($url_segments);
      $i++;
    }

    if (empty(self::$_controller)) {
      self::$_path = self::$_url_segments[0];
      self::$_controller = 'page';

      if (!self::_existPath(self::$_path)) {
        self::$_controller = 'page_404';
      }

      self::_fetchParams(1);
    }

    if (empty(self::$_action)) {
      self::$_action = 'defaultAction';
    }
  }

  private static function _existPath($path) {
    $table = Config::getParam('modules->pages->table');
    $statement = 'SELECT `path` FROM `'.$table.'` WHERE `path` = :path';

    if (($result = DB::query($statement, Array('path' => $path))) === FALSE) {
      trigger_error('Ошибка при проверке существования страницы в БД!', E_USER_ERROR);
    }

    if (empty($result)) {
      return FALSE;
    }

    return TRUE;
  }

  private static function _fetchParams($start) {
    $params = array_slice(self::$_url_segments, $start);

    foreach ($params as $param) {
      $splice_param = explode(':', trim($param, ':'));
      $count = count($splice_param);
      $key = $splice_param[0];

      if (empty($key)) continue;
      if ($count == 1) $_GET[$key] = '';

      if ($count > 1) {
        array_shift($splice_param);
        $_GET[$key] = implode('', $splice_param);
      }
    }
  }

}
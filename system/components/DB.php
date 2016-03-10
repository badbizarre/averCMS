<?php

class DB {
  /*** @var PDO */
  public static $Link = null;
  private static $_debug;

  public static function init() {
    self::_magic_quotes_off();
    $config = Config::getParam('components->db');
    self::$_debug = Config::getParam('components->log->display_errors');
    $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];

    try {
      self::$Link = new PDO($dsn, $config['db_user'], $config['db_pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $config['db_charset']));
    } catch (PDOException $e) {
      Log::write($e->getMessage());
      trigger_error('Ошибка при подключении к БД!', E_USER_ERROR);
    }
  }

  public static function query($statement, $params = '', $only_row = FALSE, $column = '') {
    preg_match('/^(\w+)\s/', trim(strtolower($statement)), $matches);
    $predicate = $matches[1];

    if (is_array($params)) {
      /* @var $query PDOStatement */
      $query = self::$Link->prepare($statement);
      if ($query->execute($params) === FALSE) {
        if (self::$_debug) {
          print_r($query->errorInfo());
        }

        Log::write(print_r($query->errorInfo(), TRUE));
        return FALSE;
      }

      if ($predicate != 'select') {
        return $query->rowCount();
      }
      
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      
      if (empty($result)) {
        return Array();
      }

      if ($only_row) {
        if ($column) {
          if (!isset($result[0][$column])) {
            trigger_error('Cтолбца [' . $column . '] в таблице не существует!', E_USER_ERROR);
          }

          return $result[0][$column];
        }

        return $result[0];
      }

      return $result;
    }

    $actions = Array('insert', 'update', 'delete');

    if (in_array($predicate, $actions)) {
      $affected_rows = self::$Link->exec($statement);

      if ($affected_rows === FALSE) {
        if (self::$_debug) {
          print_r(self::$Link->errorInfo());
        }

        Log::write(print_r(self::$Link->errorInfo(), TRUE));
        return FALSE;
      }

      return $affected_rows;
    }

    if (($query = self::$Link->query($statement)) === FALSE) {
      if (self::$_debug) {
        print_r(self::$Link->errorInfo());
      }

      Log::write(print_r(self::$Link->errorInfo(), TRUE));
      return FALSE;
    }

    if ($predicate != 'select') {
      return $query->rowCount();
    }

    if (!$result = $query->fetchAll(PDO::FETCH_ASSOC)) {
      return Array();
    }

    if ($only_row) {
      if ($column) {
        if (!isset($result[0][$column])) {
          trigger_error('Cтолбца [' . $column . '] в таблице не существует!', E_USER_ERROR);
        }

        return $result[0][$column];
      }

      return $result[0];
    }

    return $result;
  }

  private static function _magic_quotes_off() {
    function stripslashes_deep($value) {
      return $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }

    if (get_magic_quotes_gpc()) {
      $_POST = array_map('stripslashes_deep', $_POST);
      $_GET = array_map('stripslashes_deep', $_GET);
      $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
      $_REQUEST = array_map('stripslashes_deep', $_REQUEST);

      if (isset($_SERVER['PHP_AUTH_USER']))
        stripslashes($_SERVER['PHP_AUTH_USER']);
      if (isset($_SERVER['PHP_AUTH_PW']))
        stripslashes($_SERVER['PHP_AUTH_PW']);
    }
  }
}
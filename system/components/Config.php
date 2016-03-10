<?php

class Config{
  private static $_settings;
  
  public static function init() {
    self::$_settings = get_config();
  }

  public static function setParam($param, $value) {
    $param = explode('->', $param);
    $settings = self::$_settings;
    $path = '';

    foreach ($param as $key) {
      if (!key_exists($key, $settings)) {
        trigger_error('В конфигурации не существует параметра ['.$key.']!', E_USER_ERROR);
      }

      $path .= "['$key']";
      $settings = $settings[$key];
    }

    if (!is_numeric($value)) {
      $value = "'$value'";
    }

    eval("self::\$_settings$path = $value;");

    $config = "<?php\n\n\$config = ".preg_replace (
        Array ('/(\\\\)+/', '/=>\s\n\s+array/i'),
        Array ('\\', '=> Array'),
        var_export(self::$_settings, TRUE).';'
      );

    file_put_contents(CONFIG_PATH, $config);
  }

  public static function getParam($param) {
    $param = explode('->', $param);
    $settings = self::$_settings;

    foreach ($param as $key) {
      if (!key_exists($key, $settings)) {
        trigger_error('В конфигурации не существует параметра ['.$key.']!', E_USER_ERROR);
      }

      $settings = $settings[$key];
    }

    return $settings;
  }

}
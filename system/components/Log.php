<?php

class Log {
  private static $_config;

  public static function init() {
    self::$_config = get_config();
    self::$_config = self::$_config['components']['log'];

    if (!self::$_config['display_errors']) {
      ini_set("display_errors", 0);
    }

    register_shutdown_function(Array('Log', 'FatalErrorHandler'));
    set_error_handler(Array('Log', 'myErrorHandler'));
  }

  public static function fatalErrorHandler() {
    if (!$error = error_get_last()) {
      return FALSE;
    }

    $catch_error_lavel = Array (
      E_ERROR,
      E_PARSE,
      E_CORE_ERROR,
      E_CORE_WARNING,
      E_COMPILE_ERROR,
      E_COMPILE_WARNING
    );

    if (!in_array($error['type'], $catch_error_lavel)) {
      return FALSE;
    }

    if (!error_reporting()) {
      return TRUE;
    }

    send_header_503();

    if (!self::$_config['display_errors']) {
      echo self::_getDisplayErrorMesaage($error['type'], $error['message'], $error['file'], $error['line']);
    }

    $error_msg = self::_getLogErrorMessage($error['type'], $error['message'], $error['file'], $error['line']);
    self::_sendErrorMessageOnEmail($error_msg);
    self::_writeErrorMessageInLogFile($error_msg);

    return TRUE;
  }

  public static function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!error_reporting()) {
      return TRUE;
    }

    if (in_array($errno, Array(E_ERROR, E_USER_ERROR))) {
      send_header_503();
    }

    $error_msg = self::_getLogErrorMessage($errno, $errstr, $errfile, $errline);
    self::_sendErrorMessageOnEmail($error_msg);
    self::_writeErrorMessageInLogFile($error_msg);

    if (self::$_config['display_errors']) {
      return FALSE;
    }

    echo self::_getDisplayErrorMesaage($errno, $errstr, $errfile, $errline);

    if (in_array($errno, Array(E_ERROR, E_USER_ERROR))) {
      exit();
    } 
  }

  public static function write($message, $trace = FALSE, $vars = FALSE) {
    $message =
      '=================================='.PHP_EOL.
      'Date: '.date('d-m-Y H:i:s').PHP_EOL.
      'Message: '.$message.PHP_EOL;

    if ($trace) {
      $message .=
        '------------------'.PHP_EOL.
        self::getDebugBacktrace().PHP_EOL;
    }

    if ($vars) {
      $message .= 
        '------------------'.PHP_EOL.
        self::getGlobalVars().PHP_EOL;
    }

    $message .= '=================================='.PHP_EOL;

    self::_sendErrorMessageOnEmail($message);
    self::_writeErrorMessageInLogFile($message);
  }

  public static function getGlobalVars() {
    return trim(
'VARS

$_SERVER = '.print_r($_SERVER, TRUE).'
$_GET = '.print_r($_GET, TRUE).'
$_POST = '.print_r($_POST, TRUE).'
$_COOKIE = '.print_r($_COOKIE, TRUE).'
$_SESSION = '.print_r($_SESSION, TRUE));
  }

  public static function getDebugBacktrace() {
    ob_start();
    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();
    
    return trim('TRACE'.PHP_EOL.PHP_EOL.$trace);
  }

  private static function _getDisplayErrorMesaage($errno, $errstr, $errfile, $errline) {
    $fatal_error_lavel = Array (
      E_ERROR,
      E_PARSE,
      E_USER_ERROR,
      E_CORE_ERROR,
      E_CORE_WARNING,
      E_COMPILE_ERROR,
      E_COMPILE_WARNING
    );

    $type = 'ПРЕДУПРЕЖДЕНИЕ';

    if (in_array($errno, $fatal_error_lavel)) {
      $type = 'ОШИБКА';
    }

    $errfile = basename($errfile);
    return "<b>{$type}:</b> {$errstr}<br />\r\n";
  }

  private static function _getLogErrorMessage($errno, $errstr, $errfile, $errline) {
    $types_error = Array (
      E_ERROR => 'E_ERROR',
      E_WARNING => 'E_WARNING',
      E_PARSE => 'E_PARSE',
      E_NOTICE => 'E_NOTICE',
      E_CORE_ERROR => 'E_CORE_ERROR ',
      E_CORE_WARNING => 'E_CORE_WARNING',
      E_COMPILE_ERROR => 'E_COMPILE_ERROR',
      E_COMPILE_WARNING => 'E_COMPILE_WARNING',
      E_USER_ERROR => 'E_USER_ERROR',
      E_USER_WARNING => 'E_USER_WARNING',
      E_USER_NOTICE => 'E_USER_NOTICE',
      E_STRICT => 'E_STRICT',
      E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
      //E_DEPRECATED        => 'E_DEPRECATED',
      //E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    );

$message =
'==================================
Date: '.date('d-m-Y H:i:s').'
Type: '.$types_error[$errno].'
Message: '.$errstr.'
File: '.$errfile.'
Line: '.$errline.'
------------------
'.self::getGlobalVars().'
------------------
'.self::getDebugBacktrace().'
=================================='.PHP_EOL;

    return $message;
  }

  private static function _sendErrorMessageOnEmail($message) {
    if (!self::$_config['email_notify']['enable']) {
      return FALSE;
    }

    $to = self::$_config['email_notify']['email'];
    $subject = 'Error Report: http://'.$_SERVER['HTTP_HOST'];
    $headers = 'From: admin@'.$_SERVER['HTTP_HOST']."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    $headers .= 'X-Mailer: PHP/'.phpversion();

    @mail($to, $subject, $message, $headers);
    return TRUE;
  }

  private static function _writeErrorMessageInLogFile($message) {
    if (!self::$_config['logging']['enable']) {
      return FALSE;
    }

    $log_file = ROOT.self::$_config['logging']['log_file']['path'];
    $max_size = self::$_config['logging']['log_file']['max_size'];
    preg_match('/^([0-9]+)(.)$/', $max_size, $size);

    $multiplier = Array(
      'b' => 1,
      'k' => 1024,
      'm' => 1024 * 1024,
      'g' => 1024 * 1024 * 1024
    );

    $mode = "a+";

    if (@filesize($log_file) > $size[1] * $multiplier[$size[2]]) {
      $mode = "w";
    }

    if (!$fopen = @fopen($log_file, $mode)) {
      echo self::_getDisplayErrorMesaage(0, 'Невозможно открыть log файл для записи сообщения об ошибке!', __FILE__, __LINE__);
      self::_sendErrorMessageOnEmail($message);
      return FALSE;
    }

    if (!@flock($fopen, LOCK_EX)) {
      echo self::_getDisplayErrorMesaage(0, 'Невозможно заблокировать log файл для записи сообщения об ошибке!', __FILE__, __LINE__);
      self::_sendErrorMessageOnEmail($message);
      return FALSE;
    }

    if (!@fwrite($fopen, $message)) {
      echo self::_getDisplayErrorMesaage(0, 'Невозможно записть сообщение об ошибке в log файл!', __FILE__, __LINE__);
      self::_sendErrorMessageOnEmail($message);
      return FALSE;
    };

    @fflush($fopen);
    @flock($fopen, LOCK_UN);
    @fclose($fopen);
  }
}
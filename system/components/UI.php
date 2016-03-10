<?php

class UI {

  private static

    $_cssList = array(),
    $_jsList = array(),
    $_jsInlineList = array();

  public static function addCSS($list) {
    if (is_array($list)) {
      foreach ($list as $item) {
        self::$_cssList[] = $item;
      }
    } else {
      self::$_cssList[] = $list;
    }
  }

  public static function addJS($list, $inline = false) {
    if (is_array($list)) {
      self::$_jsList = array_merge(self::$_jsList, $list);
    } else {
      self::$_jsList[] = $list;
    }

    if ($inline) {
      if (is_array($inline)) {
        self::$_jsInlineList = array_merge(self::$_jsInlineList, $inline);
      } else {
        self::$_jsInlineList = $inline;
      }
    }
  }

  public static function getCSS() {
    return self::$_cssList;
  }

  public static function getJS($inline = false) {
    if ($inline) {
      return self::$_jsInlineList;
    } else {
      return self::$_jsList;
    }
  }

}
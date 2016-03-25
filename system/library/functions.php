<?php

function safe($values) {
  if (is_array($values)) {
    foreach ($values as $key => $value) {
      $values[$key] = safe($value);
    }
  } else if ($values === null) {
    $values = 'NULL';
  } else if (is_bool($values)) {
    $values = $values ? 1 : 0;
  } else if (!is_numeric($values)) {
    $values = mysql_real_escape_string($values);
  }
  return $values;
}

function xls_conv($str = '') {
	$data = iconv('UTF-8', 'Windows-1251//IGNORE', $str);
	return $data;
}

function html_chars_conv($data, $key_ignor_list = '', $is_key='') {
  if (is_array($data)) {
    foreach ($data as $key => $val) {
      $data[$key] = html_chars_conv($val, $key_ignor_list, $key);
    }
  } else {
    if (is_array($key_ignor_list) && !in_array($is_key, $key_ignor_list)) {
      $data = htmlspecialchars($data, ENT_QUOTES);
    }
    if (!is_array($key_ignor_list)) {
      $data = htmlspecialchars($data, ENT_QUOTES);
    }
  }
  return $data;
}

function get_ext_file($file) {
  return end(explode(".", $file));
}

function get_image_info($file = NULL) {
  if (!is_file($file))
    return false;
  if (!$data = getimagesize($file) or !$filesize = filesize($file))
    return false;
  $extensions = array(1 => 'gif', 2 => 'jpg',
      3 => 'png', 4 => 'swf',
      5 => 'psd', 6 => 'bmp',
      7 => 'tiff', 8 => 'tiff',
      9 => 'jpc', 10 => 'jp2',
      11 => 'jpx', 12 => 'jb2',
      13 => 'swc', 14 => 'iff',
      15 => 'wbmp', 16 => 'xbmp');

  $result = array('width' => $data[0],
      'height' => $data[1],
      'extension' => $extensions[$data[2]],
      'size' => $filesize,
      'mime' => $data['mime']);
  return $result;
}

function image_resize($img_file, $target_file, $width, $height, $mode = 1) {
  if (!file_exists($img_file))
    return false;
  if (!$source_im_info = @getimagesize($img_file))
    return false;
  $valid_im_types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
  $img_open_func = 'imagecreatefrom' . $valid_im_types[$source_im_info[2]];
  $source_im = $img_open_func($img_file);
  $k1 = $width / imagesx($source_im);
  $k2 = $height / imagesy($source_im);
  $k = ($mode == 1) ? (($k1 > $k2) ? $k2 : $k1) : (($k1 < $k2) ? $k2 : $k1);
  $width = intval(imagesx($source_im) * $k);
  $height = intval(imagesy($source_im) * $k);
  $result_im = imagecreatetruecolor($width, $height);
  if (!@imagecopyresampled($result_im, $source_im, 0, 0, 0, 0, $width, $height, $source_im_info[0], $source_im_info[1]))
    return false;
  $img_close_func = 'image' . $valid_im_types[$source_im_info[2]];
  if (!$img_close_func($result_im, $target_file))
    return false;
  imagedestroy($source_im);
  imagedestroy($result_im);
  return true;
}

function mb_wordwrap($str, $len = 50, $break = ' ', $cut=true) {
  if (empty($str))
    return "";

  $pattern = "";
  if (!$cut)
    $pattern = "/(\S{" . $len . "})/u";
  else
    $pattern="/(.{" . $len . "})/u";

  return preg_replace($pattern, "$1" . $break, $str);
}

function cut_str($str, $len, $end = "...") {
	if (mb_strlen($str, "utf-8") <= $len) {
		return $str;
	} else {
		$str = mb_substr($str, 0, $len, "utf-8");
		return $str.$end;    
	}
} 
	
function get_upload_max_file_size($in_byte = FALSE) {

  function let_to_num($v) { //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    $l = substr($v, -1);
    $ret = substr($v, 0, -1);
    switch (strtoupper($l)) {
      case 'P':
        $ret *= 1024;
      case 'T':
        $ret *= 1024;
      case 'G':
        $ret *= 1024;
      case 'M':
        $ret *= 1024;
      case 'K':
        $ret *= 1024;
        break;
    }
    return $ret;
  }

  $max_upload_size = min(let_to_num(ini_get('post_max_size')), let_to_num(ini_get('upload_max_filesize')));

  if ($in_byte) {
    return $max_upload_size;
  }

  return ($max_upload_size / (1024 * 1024)) . 'MB';
}

function array2json($arr) {
  if (function_exists('json_encode'))
    return json_encode($arr); //Lastest versions of PHP already has this functionality.
  $parts = array();
  $is_list = false;

  //Find out if the given array is a numerical array
  $keys = array_keys($arr);
  $max_length = count($arr) - 1;
  if (($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
    $is_list = true;
    for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
      if ($i != $keys[$i]) { //A key fails at position check.
        $is_list = false; //It is an associative array.
        break;
      }
    }
  }

  foreach ($arr as $key => $value) {
    if (is_array($value)) { //Custom handling for arrays
      if ($is_list)
        $parts[] = array2json($value); /* :RECURSION: */
      else
        $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
    } else {
      $str = '';
      if (!$is_list)
        $str = '"' . $key . '":';

      //Custom handling for multiple data types
      if (is_numeric($value))
        $str .= $value; //Numbers
      elseif ($value === false)
        $str .= 'false'; //The booleans
      elseif ($value === true)
        $str .= 'true';
      else
        $str .= '"' . addslashes($value) . '"'; //All other things
        
// :TODO: Is there any more datatype we should be in the lookout for? (Object?)

      $parts[] = $str;
    }
  }
  $json = implode(',', $parts);

  if ($is_list)
    return '[' . $json . ']'; //Return numerical JSON
  return '{' . $json . '}'; //Return associative JSON
}

function translit($str) {
  $tr = Array (
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g',  'д' => 'd', 'е' => 'e',
    'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
    'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
    'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
    'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
    'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

    'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',  'Д' => 'D', 'Е' => 'E',
    'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
    'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
    'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
    'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
    'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA'
  );

  return strtr($str, $tr);
}

function create_file_name($name) {
  return preg_replace(Array('/[^a-z0-9\-_.\s]+/iu', '/\s/'), '_', translit($name));
}

function translit_path($name) {
  return preg_replace(Array('/[^a-z0-9\-_.\s]+/iu', '/\s/'), '-', mb_strtolower(translit($name)));
}

function full_trim($str) {                                                    
    return trim(preg_replace('/\s{2,}/', ' ', $str));
                                                     
}

function send_header_json() {
  header('Content-Type: application/json; charset=utf-8');
}

function valid_email($address) {
  return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function get_table($name) {
	return Config::getParam('modules->'.$name.'->table');
}

function get_array_month() {
	return array(
				0 => '',1 => 'Января',2 => 'Февраля',3 => 'Марта',4 => 'Апреля',5 => 'Мая',6 => 'Июня',
				7 => 'Июля',8 => 'Августа',9 => 'Сентября',10 => 'Октября',11 => 'Ноября',12 => 'Декабря'
				);
}

function get_array_sex() {
	return array(0 => '',1 => 'муж',2 => 'жен');
}

function array_select_day() {
	$data = array();
	for($i=0;$i<=31;$i++) {
		$data[$i]['id'] = $i; 
		$data[$i]['name'] = $i; 
	}
	return $data;
}

function array_select_month() {
	$data = array();
	
	$month = get_array_month();
										
	for($i=1;$i<=12;$i++) {
		$data[$i]['id'] = $i; 
		$data[$i]['name'] = $month[$i]; 
	}
	return $data;
}

function array_select_year() {
	$data = array();
	for($i=1950;$i<=date('Y');$i++) {
		$data[$i]['id'] = $i; 
		$data[$i]['name'] = $i; 
	}
	return $data;
}

function array_select_sex() {
	$data = array();
	
	$sex = get_array_sex();
										
	for($i=1;$i<=2;$i++) {
		$data[$i]['id'] = $i; 
		$data[$i]['name'] = $sex[$i]; 
	}
	return $data;
}

function get_birthday_user($id) {
	$item = Database::getRow(get_table('users'),$id);
	$day = (!empty($item['birthday_day'])) ? $item['birthday_day'] : '';
	$m = get_array_month();
	$month = mb_strtolower($m[@$item['birthday_month']], 'UTF-8');
	$year = (!empty($item['birthday_year'])) ? $item['birthday_year'] : '';
	return "$day $month $year";
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function insert_image($path,$size,$img) {
	$config = Config::getParam('modules->'.$path);
	if (!empty($img)) return $config['image'][$size]['path'].$img;
	else return '/img/no-photo.jpg';
}

/**
 * Генерирует случайную последовательность символов заданной длины
 * @param int $length
 * @return string
 */
function string_random($length=32, $seed=''){

    $string = md5(md5(session_id() . '$' . microtime(true) . '$' . rand(0, 99999)) . '$' . $seed);

    if ($length < 32) { $string = mb_substr($string, 0, $length); }

    return $string;

}

function js_response($response) {
	
	//создаем js массив
	$res = '<script type="text/javascript">';
	$res .= 'var data = new Object;';
	foreach ($response as $key => $item) {
		$res .= 'data.'.$key.' = "'.addslashes($item).'";';
	}
	$res .= 'window.parent.handleResponse(data);';
	$res .= '</script>';
	
	return $res;
	
}

function get_url_canonical() {
	$html = 'http://'.$_SERVER["SERVER_NAME"];
	$html_end = '';
	
	for($i=1;$i<5;$i++) 
		if (URL::getSegment($i)!='' and $i!=2 and URL::getSegment($i)!='index') 
			$html .= '/'.URL::getSegment($i);
	
	if (isset($_GET['page'])) 
		$html_end .= '?page='.$_GET['page'];
	
	return $html.$html_end;
}

function get_keys_items($items,$key) {
	$keys = '0';
	foreach($items as $item) {
		$keys .= ','.$item[$key];
	}
	return $keys;
}

function get_product_path($item) {
	
	return '/recept/'.$item['path'];
	
}

function get_user_path($item) {

	return '/users/id'.$item['id'];
	
}

function get_user_name($item) {
	
	return (!empty($item['name']) ? $item['name'] : 'Без имени');
	
}

function parse_by_povarenok_ru($site_href) {

	if (!isset($site_href) and empty($site_href)) return @$item;
	
	$item = array();
		
	//подгружаем библиотеку
	Load::library('simple_html_dom.php');
	//создаём новый объект
	$html = new simple_html_dom();

	$html = file_get_html($site_href);	
	//находим все ссылки на странице и...

	$ingredient_tag = '.recipe-ing';
	$instruction_tag = '.recipe-text';
	$instruction_tag2 = '.recipe-steps tr';
	
	$item['time'] = '';
	$item['short_description'] = '';
	$item['name'] = '';
	
	if (count($html->find('time[itemprop=totalTime]',0))) 
	$item['time'] = $html->find('time[itemprop=totalTime]',0)->plaintext;	

	if (count($html->find('span[itemprop=summary]',0))) 
	$item['short_description'] = $html->find('span[itemprop=summary]',0)->plaintext;		

	if (count($html->find('h1 > a',3)))
	$item['name'] = $html->find('h1 > a',3)->plaintext;		
	
	$i = 0;				
	if(count($html->find($ingredient_tag))) {	
		foreach($html->find($ingredient_tag) as $div) {
			for($i=0;$i<count($div->find('span[itemprop=name]'));$i++) {
				$ingredient = $div->find('span[itemprop=name]',$i)->plaintext;
				
				if (count($div->find('span[itemprop=amount]',$i))) {
					$value = $div->find('span[itemprop=amount]',$i)->plaintext;
					$kolvo = substr($value, 0, strpos($value, ' '));
					$measure = substr($value, strpos($value, ' '));
				} else {
					$kolvo = '';
					$measure = '';					
				}
				$item['ing'][$i]['ingredient'] = $ingredient;
				$item['ing'][$i]['kolvo'] = $kolvo;
				$item['ing'][$i]['measure'] = $measure;
			}
		}
	}
	
	$item['recept'] = '';	
	$i = 1;		
	if(count($html->find($instruction_tag2))) {	
		foreach($html->find($instruction_tag2) as $div){
			$item['recept'] .= $i++.") ".$div->last_child()->plaintext."\n";		
		}
	}

	if(count($html->find($instruction_tag))) {	
		foreach($html->find($instruction_tag) as $div){
			$item['recept'] .= $div->plaintext;		
		}
	}

	$html->clear(); 
	unset($html);
	
	return $item;
}

function get_ing_by_parse($ingredients) {
	$data = array();
	if (!isset($ingredients) and empty($ingredients)) return $data;
	$i=0;
	foreach($ingredients as $ing) {
		$id_ingredient = Database::getField(get_table('ingredients'),$ing['ingredient'],'name','id');
		$id_measure = Database::getField(get_table('measures'),trim($ing['measure']),'name','id');
		$data[$i]['id_ingredient'] = $id_ingredient;
		$data[$i]['kolvo'] = $ing['kolvo'];
		$data[$i]['id_measure'] = $id_measure;
		$i++;
	}
	return $data;
}








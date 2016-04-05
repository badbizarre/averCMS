<?php

function get_items_adminpanel($get,$count) {
	$data = array();
	
	$page  			= @$get['page'];      // Номер запришиваемой страницы
	$limit 			= @$get['rows'];      // Количество запрашиваемых записей
	$data['sidx']  	= @$get['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
	$data['sord']  	= @$get['sord'];      // Направление сортировки
		
	$data['searchField']   = @$get['searchField'];	    //имя столбца
	$data['searchOper']    = @$get['searchOper'];	    //содержит
	$data['searchString']  = @$get['searchString'];		//искомое слово		

		// Рассчитаем сколько всего страниц займут данные в БД
	$total_currency = ( $count > 0 && $limit > 0) ? ceil($count/$limit) : 0;

	if ($page > $total_currency) $page = $total_currency;

	$start = $limit * $page - $limit;

	if($start <0) $start = 0;

	$data['page']       = $page;
	$data['total']      = $total_currency;
	$data['records']    = $count;
	$data['limit']    	= $start.', '.$limit;
	$data['order'] 		= $data['sidx'].' '.$data['sord'];
	return $data;	
}

function get_url_admin_form($count) {

	$count++;
	$url = '';

	for($i=1;$i<$count;$i++) {
		$url .= '/'.URL::getSegment($i);
	}
	return $url;

}

/**
 * Возвращает отформатированную строку аттрибутов тега
 * @param array $attributes
 * @return string
 */
function html_attr_str($attributes){
    $attr_str = '';
    unset($attributes['class']);
    if (sizeof($attributes)){
        foreach($attributes as $key=>$val){
            $attr_str .= "{$key}=\"{$val}\" ";
        }
    }
    return $attr_str;
}

function html_form_file($text, $input) {

	$html = '<div class="form-group">';
	if (empty($text)) {
		$html .= '<div class="col-xs-12">';		
	} else {
		$html .= '<label class="col-xs-2 control-label">'.$text.'</label>
				<div class="col-xs-10">';
	}
	$html .= '		<div class="file-block">
						<span class="btn btn-primary btn-file">
							Открыть '.$input.'
						</span>
						<span class="label label-info form-label-file"></span>							
					</div>						
				</div>
			</div>';

	return $html;		
}

function html_form_image_crop($image,$name_module = '',$name_label = 'Изображение') {
	
	UI::addCSS(array(
		'/css/admin/plugins/cropper/cropper.css',
		'/css/admin/plugins/cropper/main.css'
	));
	
	UI::addJS(array(
		'/js/library/plugins/cropper/cropper.js',
		'/js/library/plugins/cropper/tooltip.min.js'
	));	
	
	if (empty($name_module)) $name_module = URL::getSegment(2);
	
	$config = Config::getParam('modules->'.$name_module);
	$width = $config['image']['small']['width'];
	$height = $config['image']['small']['height'];
	
	$html = '<script>
		$(function () {

			var $image = $(".img-container > img");
			var $dataX = $("#dataX");
			var $dataY = $("#dataY");
			var $dataHeight = $("#dataHeight");
			var $dataWidth = $("#dataWidth");
			var options = {
				  aspectRatio: '.$width.' / '.$height.',
				  zoomable: true,
				  minCropBoxWidth: '.$width.',
				  minCropBoxHeight: '.$height.',
				  preview: ".img-preview",
				  crop: function (e) {
					$dataX.val(Math.round(e.x));
					$dataY.val(Math.round(e.y));
					$dataHeight.val(Math.round(e.height));
					$dataWidth.val(Math.round(e.width));
				  }
				};
				
			$image.cropper(options);
			
			// Import image
			var $inputImage = $("#inputImage");
			var URL = window.URL || window.webkitURL;
			var blobURL;

			if (URL) {
			  $inputImage.change(function () {
				var files = this.files;
				var file;

				if (!$image.data("cropper")) {
				  return;
				}

				if (files && files.length) {
				  file = files[0];

				  if (/^image\/\w+$/.test(file.type)) {
					blobURL = URL.createObjectURL(file);
					$image.one("built.cropper", function () {
					  URL.revokeObjectURL(blobURL); // Revoke when load complete
					}).cropper("reset").cropper("replace", blobURL);
					$(".block-hide").show();
				  } else {
					$body.tooltip("Please choose an image file.", "warning");
				  }
				}
			  });
			} else {
			  $inputImage.parent().remove();
			}


		});
	</script>';	
	

	$html .= html_form_file($name_label,html_input('file','image','',array('id'=>'inputImage')));
	
	$html .= '<div class="form-group block-hide">'.
	html_input('hidden','datax','',array('id'=>'dataX')).
	html_input('hidden','datay','',array('id'=>'dataY')).
	html_input('hidden','width','',array('id'=>'dataWidth')).
	html_input('hidden','height','',array('id'=>'dataHeight')).'
					
	<div class="col-md-offset-2 col-md-2">
		<div class="docs-preview clearfix">				
			<div class="img-preview preview-md"></div>	
			<div class="img-preview preview-sm"></div>	
		</div>
	</div>

	<div class="col-md-8">					
		<div class="img-container hide-block">
			<img src="'.$image.'" alt="Picture" class="img-responsive">
		</div>
	</div>
	
	</div>';
	
	return $html;
}

function html_form_group($text,$input) {

	$html = '<div class="form-group"><label class="col-xs-2 control-label">'.$text.'</label>
			<div class="col-xs-10">'.$input.'</div>
		</div>';	
		
	return $html; 
}

function html_input($type, $name='', $value='', $attributes=array()) {

	$attr_str = html_attr_str($attributes);
    $class = 'form-control';
    if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }

	$html = '<input type="'.$type.'" value="'.$value.'" name="'.$name.'" class="'.$class.'" '.$attr_str.' >';
	
	return $html;			

}

function html_checkbox($name, $value=0, $attributes=array()) {

	if ($value) { $attributes['checked'] = 'checked'; }	
	$attr_str = html_attr_str($attributes);
    $class = '';
    if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }
	
	return '<div class="checkbox-inline i-checks">
				<label>
					<input type="checkbox" value="'.$value.'" name="'.$name.'" class="'.$class.'" '.$attr_str.'>
				</label>
			</div>';
	
}

function html_textarea($name, $value='', $attributes=array()) {

	$attr_str = html_attr_str($attributes);

    $class = 'form-control';
    if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }
	
	return '<textarea name="'.$name.'" class="'.$class.'" '.$attr_str.'>'.$value.'</textarea>';
	
}

function html_select($items, $name, $value='', $attributes=array()) {
	
	$attr_str = html_attr_str($attributes);
    $class = 'form-control';
	
	if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }
	
	$html = '<select name="'.$name.'" class="'.$class.'" '.$attr_str.'>
			<option value="0">нет</option>';
			foreach($items as $item) {
				
	if ($item['id']==$value) { $selected = 'selected'; } else { $selected = ''; }
	$html .= '<option value="'.$item['id'].'" '.$selected.'>'.$item['name'].'</option>';	
			}
	$html .= '</select>';
	
	return $html;	
		
}

function html_multi_select($items, $name, $values=array(), $attributes=array()) {
	
	$attr_str = html_attr_str($attributes);
    $class = 'form-control';
	
	if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }
	
	$html = '<select name="'.$name.'[]" class="'.$class.'" '.$attr_str.' multiple>
			<option value="0">нет</option>';
			foreach($items as $item) {
				
	if (in_array($item['id'],$values)) { $selected = 'selected'; } else { $selected = ''; }
	$html .= '<option value="'.$item['id'].'" '.$selected.'>'.$item['name'].'</option>';	
			}
	$html .= '</select>';
	
	return $html;	
		
}

function html_select_format($items, $name, $value='', $attributes=array()) {
	
	$attr_str = html_attr_str($attributes);
    $class = 'form-control';
	
	if (isset($attributes['class'])) { $class .= ' '.$attributes['class']; }
	
	$html = '<select name="'.$name.'" class="'.$class.'" '.$attr_str.'>
			<option value="0">нет</option>'.tfm_array($items,$value).'</select>';
	
	return $html;	
		
}

function get_tree($tree, $pid=0) {
    $html = '';
 
    foreach ($tree as $row) {
        if ($row['pid'] == $pid) {
			$clss = ($row['pid'] == 0) ? 'open' : '';
            $html .= '<li class="'.$clss.'"><a href="?id_tree='.$row['id'].'" class="linkrel" id="'.$row['id'].'"><i class="fa fa-folder"></i>'.$row['name'].'</a>'.get_tree($tree, $row['id']).'</li>';
        }
    }
 
    return '<ul id="pid'.$pid.'">'.$html.'</ul>';
} 

function tfm_array($items,$value,$pid = 1) {
	
	$html = '';

	foreach($items as $item) {
		if ($item['pid'] == $pid) {
			$level = ($item['level']-1)*20;
			if ($item['id']==$value) { $selected = 'selected'; } else { $selected = ''; }
			$html .= '<option value="'.$item['id'].'" style="padding-left:'.$level.'px" '.$selected.' >'.$item['name'].'</option>';	
			$html .= tfm_array($items,$value,$item['id']);
		}
	}							
	
	return $html;
	
}

function get_parents($table,$pid) {
    $html = '';
	
	$items = Database::getRows($table,'id asc',false,"id = $pid");
    foreach ($items as $item) {
		$html .= $item['id'].','.get_parents($table,$item['pid']);
    }

    return $html;	
}

function get_childs($table,$id) {
    $html = '';
	
	$items = Database::getRows($table,'id asc',false,"pid = $id");
    foreach ($items as $item) {
		$html .= $item['id'].','.get_childs($table,$item['id']);
    }

    return $html;	
}

function get_string_id_tree($table,$id_tree) {
	
	$html = $id_tree.','.get_childs($table,$id_tree);
	return substr($html, 0, -1);
	
}

function transform_date($date,$min_date = false) {

	$date = date('Y-m-d',strtotime($date));
	$date_now = date('Y-m-d');
	$yesterday = date('Y-m-d',mktime(0, 0, 0, date('m')  , date('d')-1, date('Y')));
	
	if ($date_now == $date) $result = 'cегодня';
	elseif ($yesterday == $date) $result = 'вчера';
	elseif ($min_date) {
		$month = Array('янв', 'фев', 'мар', 'апр', 'мая', 'июня', 'июля', 'авг', 'сен', 'окт', 'ноя', 'дек');
		$result = date('d', strtotime($date)).' '.$month[date('m', strtotime($date))-1];
	} else {
		$result = date('d.m.Y',strtotime($date));
	}

	return $result;
}

function transform_time($date) {
	return date('H:i',strtotime($date));
}

function html_btn_edit_admin($path,$id) {
	return '<a class="btn btn-white btn-sm" href="/adminpanel/'.$path.'/edit/?id='.$id.'"><i class="fa fa-edit"></i></a>';
}

function html_btn_view_admin($path,$id) {
	return '<a class="btn btn-white btn-sm" href="/adminpanel/'.$path.'/view/?id='.$id.'"><i class="fa fa-folder"></i></a>';
}

function html_btn_del_admin($path,$id) {
	return '<a class="btn btn-white btn-sm delete-item" href="#" rel="'.$id.'"><i class="fa fa-trash-o"></i></a>';
}

function html_label_active($active) {
	return (($active!=0) ? '<span class="label label-primary">Активен</span>' : '<span class="label label-default">Не активен</span>');
}

function check_active_url($url) {
	if (URL::getSegment(2)==$url) echo 'class="active"';
}

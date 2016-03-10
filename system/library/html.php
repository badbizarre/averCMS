<?php

function get_html_ingredient($id, $href = true) {
	$table_cat_ing = get_table('catalog_ingredients');
	$table_ingredients = get_table('ingredients');
	$table_measure = get_table('measures');
	$catalog_ingredients = Database::getRows($table_cat_ing,'id asc',false,"id_catalog = $id");
	$html = 'ИНГРЕДИЕНТЫ';
	foreach($catalog_ingredients as $elem) {
		$ingredient = Database::getRow($table_ingredients,$elem['id_ingredient']);
		
		if (empty($ingredient)) continue;
		
		$measure_name = Database::getField($table_measure,$elem['id_measure']);
		
		if ($elem['kolvo']>0 and $elem['id_measure']>0) $html_posfix =  ' - '.$elem['kolvo'].' '.$measure_name;
		elseif ($elem['id_measure']>0) $html_posfix = ' - '.$measure_name;
		elseif ($elem['kolvo']==0 and $elem['id_measure']==0) $html_posfix = '';
		
		if ($href) {
			$html .= '<div itemprop="ingredients">
				<a href="/ingredients/'.$ingredient['path'].'" title="'.$ingredient['name'].'">
				'.$ingredient['name'].'</a>'.$html_posfix.'
			</div>';			
		} else {
			$html .= '<div itemprop="ingredients">'.$ingredient['name'].'</a>'.$html_posfix.'</div>';			
		}

	}	
	return $html;
}

function get_crumbs_category($id_tree) {
	$string_tree = get_parents(get_table('catalog_tree'),$id_tree);
	$array_id = array_reverse(array_filter(explode(',',$string_tree)));
	$html = '';
	foreach($array_id as $id) {
		$tree = Database::getRow(get_table('catalog_tree'),$id);
		$html .= '<li><a href="/recepty/'.$tree['path'].'">'.$tree['name'].'</a></li>';
	}
	return $html;
}

function get_crumbs_article($id_tree) {
	$string_tree = get_parents(get_table('article_tree'),$id_tree);
	$array_id = array_reverse(array_filter(explode(',',$string_tree)));
	$html = '';
	foreach($array_id as $id) {
		$tree = Database::getRow(get_table('article_tree'),$id);
		$html .= '<li><a href="/article/'.$tree['path'].'">'.$tree['name'].'</a></li>';
	}
	return $html;
}

function count_product_tree($id_tree) {
			
	$where = 'active = 1 and id_tree IN ('.get_string_id_tree(get_table('catalog_tree'),$id_tree).')';
	
	$table = get_table('catalog').' AS t1 JOIN '.get_table('catalog_categories').' as t2 ON t1.id = t2.id_catalog';

	return Database::getCount($table,$where,"distinct id");
}

function get_category_name($id_product,$is_href = false) {
	
	$id_tree = Database::getField(get_table('catalog_categories'),$id_product,'id_catalog','id_tree');
	$tree = Database::getRow(get_table('catalog_tree'),$id_tree);
	if ($is_href) $result = '<a href="/recepty/'.$tree['path'].'" title="'.$tree['name'].'">'.$tree['name'].'</a>';
	else $result = $tree['name'];
	
	return $result;
	
}

function get_article_name($id_article,$is_href = false) {
	
	$id_tree = Database::getField(get_table('article_categories'),$id_article,'id_article','id_tree');
	$tree = Database::getRow(get_table('article_tree'),$id_tree);
	if ($is_href) $result = '<a href="/article/'.$tree['path'].'" title="'.$tree['name'].'">'.$tree['name'].'</a>';
	else $result = $tree['name'];
	
	return $result;
	
}

function get_users_variate($id_table,$id_variate,$table_name) {
		
	$where = 'id_table = '.$id_table.' and table_name="'.$table_name.'" and id_variate = '.$id_variate;	
	
	$id_user = cmsUser::sessionGet('user:id');
	
	if (!empty($id_user))  $where .=  " and id_user = $id_user";

	$result = Database::getRows(get_table('users_like'),'id desc',1,$where);

	return @$result[0];
	
}

function get_status_class($elem) {
		
	return (isset($elem) and !empty($elem['status'])) ? '' : '-empty';
	
}

function get_buttons_recept($item,$table_name) {
	
	$html = '';

	if ($table_name == 'catalog') $that_path = 'recepty';
	else $that_path = $table_name;
	
	$id_table = $item['id'];

	$count_note = ($item['count_note'] > 0) ? $item['count_note'] : '';
			
	$html .= '<a href="'.$that_path.'" class="btn btn-default btn-sm add-variate" data-variate="2" rel="'.$id_table.'" title="Добавить в заметки">
		<i class="glyphicon glyphicon-star'.get_status_class(get_users_variate($id_table,2,$table_name)).'"></i> 
		'.$count_note.'</a> ';

	$count_like = ($item['count_like'] > 0) ? $item['count_like'] : '';
	
	$html .= '<a href="'.$that_path.'" class="btn btn-default btn-sm add-variate" data-variate="1" rel="'.$id_table.'" title="Мне нравиться">
		<i class="glyphicon glyphicon-heart'.get_status_class(get_users_variate($id_table,1,$table_name)).'"></i>
		'.$count_like.'</a>';

	return $html;
	
}

function get_count_comment($kolvo) {

	$razryad = $kolvo;
	
	if ($kolvo > 20) $razryad = $kolvo % 10;
	$text = 'комментариев';
	if ( $razryad == 1 ) $text = "комментарий"; 
	if (( $razryad > 1 ) and ($razryad <= 4)) $text = "комментария"; 

	$result = $kolvo.' '.$text;
	
	if ($kolvo == 0) $result = "";
	
	return $result;

}

function get_comments_where($id_table,$table_name) {
	$where = 'id_table='.$id_table.' and table_name="'.$table_name.'"';
	$comments = Database::getRows(get_table('users_comment'),'id asc',false,$where);
	return $comments;
}

function html_comment_content($comments) {
	$html = '';
	$that_path = URL::getSegment(1);
	if (in_array($that_path,array('recept','index','cabinet'))) $that_path = 'recepty';
	$kolvo_end = 3;
	
	$id_user = cmsUser::sessionGet('user:id');

	if (empty($comments)) return $html;
	if (isset($raznica)) $html .= '<div class="comment-header"><a href="">'.get_count_comment($total).'</a></div>';	
	foreach($comments as $com_el) {
		
		$hide_comment = '';
		if ($id_user == $com_el['id_user'])	{
			$hide_comment = '<span class="edit-comment" rel="'.$com_el['id'].'" data-id="'.$com_el['id_table'].'" data-path="/'.$that_path.'/edit_form">Изменить</span>
					<span class="remove-comment" data-toggle="modal" data-target=".remove-comment-form" rel="'.$com_el['id'].'" ><span class="glyphicon glyphicon-remove" title="Удалить"></span></span>';	
		} elseif ($id_user != 0) {
			$hide_comment = '<span class="reply-comment" rel="'.$com_el['id'].'" data-id="'.$com_el['id_table'].'">Ответить</span>';
		}
		
		$pid_html = '';
		if (!empty($com_el['pid'])) {
			$row = Database::getRow(get_table('users_comment'),$com_el['pid']);
			$user = Database::getRow(get_table('users'),$row['id_user']);
			$pid_html = '<strong><a href="#">'.$user['name'].'</a>, </strong>';
		}		
		
		$user = Database::getRow(get_table('users'),$com_el['id_user']); 
		$html .= '<div class="comment-line">
					<a href="" class="comment-image">
						<img src="'.insert_image('users','small',$user['image']).'" alt="">
					</a>
					<div class="comment-user comid'.$com_el['id'].'">
						<div class="comment-title">
							<strong>'.$user['name'].'</strong>
							<div class="comment-date"><small>'.transform_date($com_el['date_create']).' '.transform_time($com_el['date_create']).'</small></div>
							<div class="pull-right hide-comment">'.$hide_comment.'</div>
						</div>		
						<div class="comment-text">'.$pid_html.$com_el['comment'].'﻿</div>
					</div>
				</div>';
			
	}
	return $html;
}

function get_dialog_delete($that_path) {

	$html = '<div class="modal fade" id="dialog-remove-comment" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<form class="form-horizontal form-remove-comment" role="form" action="/'.$that_path.'/update_comment" method="POST">
				<div class="modal-content">
					<div class="modal-body">
						'.html_input('hidden','action','remove').
						html_input('hidden','id','',array('id'=>'id_comment')).'
						Удалить этот комментарий навсегда?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn btn-danger">Удалить</button>
					</div>				
				</div>
			</form>	
		</div>
	</div>';

	return $html;
	
}

function get_comment_form_add($id,$that_path) {
	$item = Database::getRow(get_table('users_comment'),$id);
	
	$html = '';
	$html .= '<form action="/'.$that_path.'/update_comment" class="add-comment myform" method="POST">'
				.html_input('hidden','id_table',$id)
				.html_input('hidden','pid','')
				.html_input('hidden','action','add')
				.'<div class="form-group">
					<textarea name="comment" id=""></textarea>
				</div>
				<div>
					<button type="submit" class="btn btn-sm btn-success">Отправить</button>
					<button class="btn btn-sm btn-default comment-cancel" rel="'.$id.'">Отмена</button>
				</div>
			</form>';
	
	return $html;
}

function get_comment_form_edit($id,$that_path) {
	$item = Database::getRow(get_table('users_comment'),$id);
	
	$html = '';
	$html .= '<form action="/'.$that_path.'/update_comment" class="myform comm-edit" method="POST">'
			.html_input('hidden','id',$id)
			.html_input('hidden','action','edit')
			.'<div class="form-group">
				<textarea name="comment">'.$item['comment'].'</textarea>
			</div>
			<div>
				<button type="submit" class="btn btn-sm btn-success">Сохранить</button>
				<button class="btn btn-sm btn-default comment-cancel" rel="'.$item['id_table'].'">Отмена</button>
			</div>
	</form>';
	
	return $html;
}

function get_user_card($item) {
	
	$name = get_user_name($item);
	$city = (!empty($item['city']) ? $item['city'] : 'Без города'); 

	$btns = Friends::get_friend_button($item['id']);
	
	$html = '<div class="col-sm-3">
		<div class="item-preview item-user text-center">
			<div class="">
				<div>
					<a href="'.get_user_path($item).'" title="'.$item['name'].'">
						<img src="'.insert_image('users','small',$item['image']).'" alt="'.$item['name'].'" class="img-responsive img-circle center-block">
					</a>
				</div>				
				<div class="user-detail">
					<div class="user-detail__city"><small>'.$city.'</small></div>				
					<div class="user-detail__name">
						<a href="'.get_user_path($item).'" title="'.$item['name'].'">'.$name.'</a>
					</div>
				</div>
				<div>'.$btns.'</div>
				<div class="user-action">
					<div class="btn-group">
						<button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
							<span class="sr-only">Меню с переключением</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Написать сообщение</a></li>
							<li class="divider"></li>
							<li><a href="'.get_user_path($item).'">Открыть профиль</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>	
	</div>	';
			
	return $html;		
	
}
<?php
$razdels = Database::getRows(get_table('article_tree'),'prioritet desc',false,"pid=1 and active=1");
if ($razdels) {
	UI::addCSS('/css/article/razdel.css');

	$url = URL::getSegment(3);
	
	echo '<ul class="list-group left-menu">';
		foreach($razdels as $item) {
			if ($url==$item['path']) $cls = 'active'; else $cls = '';
			echo '<li><a href="/article/'.$item['path'].'" class="list-group-item '.$cls.'">'.$item['name'].'</a>';
		if (@$elems = Database::getRows(get_table('article_tree'),'prioritet desc',false,"pid=".$item['id']." and active=1")) {
			echo '<ul class="list-group hidden-sm hidden-xs">';
				foreach($elems as $elem){
				echo '<li><a href="/article/'.$elem['path'].'" class="list-group-item">'.$elem['name'].'</a></li>';
				}
			echo '</ul>';
		}
			echo '</li>';
		}
	echo '</ul>';

}

?>
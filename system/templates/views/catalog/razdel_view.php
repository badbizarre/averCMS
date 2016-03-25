<ul class="list-group left-menu">
	<li>
		<a href="/recepty/add_recept" class="list-group-item">
			<span class="glyphicon glyphicon-plus-sign"></span> 
			<strong>Добавить свой рецепт</strong>
		</a>
	</li>			
</ul>
<?php
$razdels = Database::getRows(get_table('catalog_tree'),'prioritet desc',false,"pid=1 and active=1");
if ($razdels) {
	UI::addCSS('/css/catalog/razdel.css');

	$url = URL::getSegment(3);
	
	echo '<ul class="list-group left-menu">';
		foreach($razdels as $item) {
			if ($url==$item['path']) $cls = 'active'; else $cls = '';
			echo '<li><a href="/recepty/'.$item['path'].'" class="list-group-item '.$cls.'"><span class="badge">'.count_product_tree($item['id']).'</span>'.$item['name'].'</a>';
		if (@$elems = Database::getRows(get_table('catalog_tree'),'prioritet desc',false,"pid=".$item['id']." and active=1")) {
			echo '<ul class="list-group hidden-sm hidden-xs">';
				foreach($elems as $elem){
				echo '<li><a href="/recepty/'.$elem['path'].'" class="list-group-item">'.$elem['name'].' ('.count_product_tree($elem['id']).')</a></li>';
				}
			echo '</ul>';
		}
			echo '</li>';
		}
	echo '</ul>';

}

?>

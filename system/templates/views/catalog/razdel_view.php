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
	UI::addCSS('/css/popup-sidebar.css');

	$url = URL::getSegment(3);
	
	echo '<div class="popup-sidebar">
	<ul class="popup-sidebar__nav">';
		foreach($razdels as $item) {
			$cls = ($url==$item['path']) ? 'active' : '';
			echo '<li class="popup-sidebar__item">
				<a href="/recepty/'.$item['path'].'" class="popup-sidebar__link '.$cls.'">'.$item['name'].'</a>';
					$elems = Database::getRows(get_table('catalog_tree'),'prioritet desc',false,"pid=".$item['id']." and active=1");
					if (@$elems) {
						$elem_p = Database::getRow(get_table('catalog_tree'),$url,'path');						
						$ul_cls = ($url==$item['path'] or $item['id'] == @$elem_p['pid']) ? 'active' : '';	
						echo '<ul class="popup-sidebar__item--sublist '.$ul_cls.' hidden-sm hidden-xs">';
							foreach($elems as $elem){
							$cls_2 = ($url==$elem['path'] or $elem['id'] == @$elem_p['pid']) ? 'active' : '';	
							echo '<li class="popup-sidebar__item">
								<a href="/recepty/'.$elem['path'].'" class="popup-sidebar__link '.$cls_2.'">'.$elem['name'].'</a>
							</li>';
							}
						echo '</ul>';
					}
			echo '</li>';
		}
	echo '</ul>
	</div>';

}

?>

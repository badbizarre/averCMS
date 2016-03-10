<?php 
	UI::addCSS(array(
		'/css/ingredients/list.css'
	));
	$array = array();
	foreach($items as $item) {
		$ings = Database::getRows(get_table('catalog_ingredients'),'id asc',false,'id_ingredient='.$item['id'],'id_catalog');
		if (count($ings)==0) continue;		
		$array[mb_substr($item['name'], 0, 1, 'UTF-8')][] = $item;
	}

?>
<div class="item-preview ingredients">
	<ul id="index-list" class="row">
		<?php 
		foreach($array as $key => $value) {
			echo '<li class="first-letter"><a href=""><strong>'.$key.'</strong></a></li>';
			foreach($value as $val) {
				$ings = Database::getRows(get_table('catalog_ingredients'),'id asc',false,'id_ingredient='.$val['id'],'id_catalog');
				if (count($ings)==0) continue;
				echo '<li><a href="/ingredients/'.$val['path'].'" title="'.$val['name'].' ('.count($ings).')">'.$val['name'].'</a></li>';
			}
		}
		?>
	</ul>
</div>
<?php if (@$tree['short_description']) : ?>
	<div class="item-preview">
		<h3><?php echo $tree['title']; ?></h3>
		<div class="">
			<?php echo nl2br($tree['short_description']); ?>
		</div>
	</div>
<?php endif; ?>

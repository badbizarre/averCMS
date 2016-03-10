<?php 
	UI::addCSS(array(
		'/css/cabinet/likes_note.css'
	));
?>
<div class="item-preview">

	<div class="forum-title">
		<div class="forum-desc text-right">
			<small>Всего рецептов: <?php echo $totals; ?></small>
		</div>
	</div>

	<?php foreach($items as $item): ?>
	<div class="forum-item">
		<div class="row">
			<div class="col-md-3">
				<a href="<?php echo insert_image('catalog','big',$item['image']); ?>" class="fancybox">
					<img src="<?php echo insert_image('catalog','small',$item['image']); ?>" alt="" class="img-responsive">
				</a>			
			</div>
			<div class="col-md-7">
				<a href="<?php echo get_product_path($item); ?>" class="forum-item-title"><?php echo $item['name']; ?></a>
				<div class="forum-sub-title"><?php echo get_category_name($item['id'],true); ?></div>
			</div>
			<div class="col-md-2 text-right">
				<small><?php echo transform_date($item['date_create'],true).' в '.transform_time($item['date_create']); ?></small>
			</div>
		</div>				
	</div>	
	<?php endforeach; ?>

</div>

	

<?php 
	UI::addCSS(array(
		'/css/catalog/block.css'
	));
?>
<ul class="row">
<?php foreach($items as $item): ?>
	<li class="item-content col-md-3 col-sm-4">
		<div class="item-preview item-block grid">
			<figure class="effect-bubba">		
				<img src="<?php echo insert_image('catalog_tree','small',$item['image']); ?>" alt="<?php echo $item['name']; ?>" class="img-responsive center-block">
				<figcaption>
					<h2><span><?php echo $item['name']; ?></span></h2>
					<p><?php echo $item['description']; ?></p>
					<a href="/recepty/<?php echo $item['path']; ?>">Показать</a>
				</figcaption>			
			</figure>
		</div>	
	</li>
<?php endforeach; ?>
</ul>

<?php $item = Pages::getPageByID(1); ?>    
<div class="block__preview">
	<h3><?php echo $item['title']; ?></h3>
	<div class="">
		<?php echo nl2br($item['short_description']); ?>
	</div>
</div>

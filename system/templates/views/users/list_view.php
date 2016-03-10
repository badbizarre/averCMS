<?php 
	$that_path = URL::getSegment(1);
	UI::addCSS(array(
		'/css/'.$that_path.'/list.css'
	));
if (@$items) : ?>
<div class="item-users">
	<div class="row">
	<?php foreach($items as $item) { echo get_user_card($item); } ?>
	</div>
</div>	
<ul class="pagination pagination-sm">
<?php echo $pagination; ?>
</ul>
<?php endif; ?> 

<?php
	if (empty($items)) return;
	
	foreach($items as $item):

	$name = get_user_name($item);
	$city = (!empty($item['city']) ? $item['city'] : 'Без города'); 

	$btns = Friends::get_friend_button($item['id']);
	
?>	
<div class="col-sm-4 col-md-4 col-lg-3">
	<div class="item-preview item-user text-center">
		<div class="">
			<div class="user-detail">				
				<div class="user-detail__name">
					<a href="<?php echo get_user_path($item); ?>" title="<?php echo $item['name']; ?>"><?php echo $name; ?></a>
				</div>
			</div>		
			<div class="user-image">
				<a href="<?php echo get_user_path($item); ?>" title="<?php echo $name; ?>">
					<img src="<?php echo insert_image('users','small',$item['image']); ?>" alt="<?php echo $name; ?>" class="img-responsive center-block">
				</a>
			</div>				
			<div><?php echo $btns; ?></div>

		</div>
	</div>	
</div>
<?php endforeach; ?>
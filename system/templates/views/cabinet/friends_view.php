<?php 
	$that_path = 'users';
	UI::addCSS(array(
		'/css/'.$that_path.'/list.css'
	));
?>
<div class="item-users">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#friends" data-toggle="tab">Друзья <?php echo Friends::get_badge_friends(count($friends)); ?></a></li>
		<li><a href="#all_requests" data-toggle="tab">Заявка в друзья <?php echo Friends::get_badge_friends(count($new_friends)); ?></a></li>
		<li><a href="#out_requests" data-toggle="tab">Исходящие заявки <?php echo Friends::get_badge_friends(count($out_friends)); ?></a></li>
		<li><a href="#old_requests" data-toggle="tab">Все подписчики <?php echo Friends::get_badge_friends(count($old_friends)); ?></a></li>
	</ul>
	
	<div class="tab-content">
		
		<div class="tab-pane active" id="friends">
  
			<div class="row">

			<?php foreach($friends as $item) { echo get_user_card($item); } ?>

			</div>
	
		</div>
	
		<div class="tab-pane" id="all_requests">
	
			<div class="row">
			
			<?php foreach($new_friends as $item) { echo get_user_card($item); } ?>
			
			</div>
			
		</div>
		
		<div class="tab-pane" id="out_requests">
	
			<div class="row">
			
			<?php foreach($out_friends as $item) { echo get_user_card($item); } ?>
			
			</div>
			
		</div>
				
		<div class="tab-pane" id="old_requests">
	
			<div class="row">
			
			<?php foreach($old_friends as $item) { echo get_user_card($item); } ?>
			
			</div>
			
		</div>
		
	</div>
</div>	

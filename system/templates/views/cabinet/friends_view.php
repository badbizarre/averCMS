<?php 
	$that_path = 'users';
	$that_path_3 = URL::getSegment(3);	
	UI::addCSS(array(
		'/css/'.$that_path.'/list.css'
	));
?>
<div class="item-users">

	<ul class="nav nav-tabs">
		<li <?php if (empty($that_path_3)) echo 'class="active"'; ?>><a href="/cabinet/friends">Друзья <?php echo Friends::get_badge_friends(count(Users::get_friends_list('friends'))); ?></a></li>
		<li <?php if ($that_path_3=='new') echo 'class="active"'; ?>><a href="/cabinet/friends/new">Заявка в друзья <?php echo Friends::get_badge_friends(count(Users::get_friends_list('new'))); ?></a></li>
		<li <?php if ($that_path_3=='out') echo 'class="active"'; ?>><a href="/cabinet/friends/out">Исходящие заявки <?php echo Friends::get_badge_friends(count(Users::get_friends_list('out'))); ?></a></li>
		<li <?php if ($that_path_3=='old') echo 'class="active"'; ?>><a href="/cabinet/friends/old">Все подписчики <?php echo Friends::get_badge_friends(count(Users::get_friends_list('old'))); ?></a></li>
	</ul>

	<div class="tab-content">
		
		<div class="tab-pane active">
  
			<div class="row">

				<?php Render::view($that_path.'/user_card',array('items'=>$items),TRUE); ?>

			</div>
	
		</div>
			
	</div>
</div>	

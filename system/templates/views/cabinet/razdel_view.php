<?php 
if (cmsUser::isSessionSet('user')) {

	$url = URL::getSegment(2);
		
	echo '<div class="widget-user item-preview">
		<div class="text-left">

			<ul class="user-menu">';
			
			if (empty($url) and URL::getSegment(1)=='cabinet') $cls = 'class="active"'; else $cls = '';
			echo '<li><a href="/cabinet" '.$cls.'>Моя страница</a></li>';	
			
			if ($url=='friends') $cls = 'active"'; else $cls = '';
			$count_new_friend = count(Friends::get_new_friends());
			if ($count_new_friend > 0) $html_count = '<span class="badge pull-right">'.$count_new_friend.'</span>';
			else $html_count = '';
			
			echo '<li><a href="/cabinet/friends" class="'.$cls.'">'.$html_count.'Мои друзья</a></li>';
						
			if ($url=='notifications') $cls = 'class="active"'; else $cls = '';
			echo '<li><a href="/cabinet/notifications" '.$cls.'>Мои ответы</a></li>';
						
			if ($url=='likes_note') $cls = 'class="active"'; else $cls = '';
			echo '<li><a href="/cabinet/likes_note" '.$cls.'>Мои заметки</a></li>';	
			
			if ($url=='settings') $cls = 'class="active"'; else $cls = '';
			echo '<li><a href="/cabinet/settings" '.$cls.'>Мои настройки</a></li>';
	
	echo '	</ul>

		</div>
	</div>';

}
?>

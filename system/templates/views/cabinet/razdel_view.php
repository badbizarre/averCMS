<link rel="stylesheet" href="/css/popup-sidebar.css" />
<?php 
if (cmsUser::isSessionSet('user')) {

	$url_main = URL::getSegment(1);
	$url = URL::getSegment(2);
		
	echo '<div class="popup-sidebar">
			<ul class="popup-sidebar__nav">';
			
			if (empty($url) and URL::getSegment(1)=='cabinet') $cls = 'active'; else $cls = '';
			echo '<li class="popup-sidebar__item"><a href="/cabinet" class="popup-sidebar__link '.$cls.'">Моя страница</a></li>';	

			if ($url=='friends') $cls = 'active'; else $cls = '';			
			echo '<li class="popup-sidebar__item"><a href="/cabinet/friends" class="popup-sidebar__link '.$cls.'">'.get_bridge_by_count(count(Friends::get_new_friends())).'Мои друзья</a></li>';
						
			if ($url=='notifications') $cls = 'active'; else $cls = '';
			echo '<li class="popup-sidebar__item"><a href="/cabinet/notifications" class="popup-sidebar__link '.$cls.'">Мои ответы</a></li>';
									
			if ($url_main=='messages') $cls = 'active'; else $cls = '';
			echo '<li class="popup-sidebar__item"><a href="/messages" class="popup-sidebar__link '.$cls.'">'.get_bridge_by_count(Messages::countNewMessage()).'Мои сообщения</a></li>';
						
			if ($url=='likes_note') $cls = 'active'; else $cls = '';
			echo '<li class="popup-sidebar__item"><a href="/cabinet/likes_note" class="popup-sidebar__link '.$cls.'">Мои заметки</a></li>';	
			
			if ($url=='settings') $cls = 'active'; else $cls = '';
			echo '<li class="popup-sidebar__item"><a href="/cabinet/settings" class="popup-sidebar__link '.$cls.'">Мои настройки</a></li>';
	
	echo '	</ul>
	</div>';

}
?>

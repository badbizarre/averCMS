
<?php if (cmsUser::isSessionSet('user')): ?>
<div class="widget-user item-preview">
	<div class="text-left">

		<?php $user = cmsUser::getUser(); ?>
		<div class="user-header">
			<img src="<?php echo insert_image('users','small',$user['image']); ?>" alt="" class="img-circle" style="height: 50px;">
			<strong> <?php echo $user['email']; ?></strong>
		</div>
		
		<ul class="user-menu">
			<li><a href="/cabinet/notifications">Мои ответы</a></li>
			<li><a href="/cabinet/likes_note">Мои заметки</a></li>
			<li><a href="/cabinet">Настройки</a></li>		
		</ul>

	</div>
</div>
<?php endif; ?>

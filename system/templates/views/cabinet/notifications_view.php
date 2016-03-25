<?php 
	UI::addCSS(array(
		'/css/cabinet/notifications.css',
		'/css/catalog/list.css'
	));
	
	if (empty($items)) echo '<div class="item-preview item-recept"><div class="title">Вы не оставляли еще комментарии</div></div>';
?>
<div class="row">

	<ul id="catalog-list">
	<?php foreach($items as $item): ?>
	
		<li class="item-content" id="update-<?php echo $item['id']; ?>">
	
			<div class="item-preview item-recept">

				<div class="title-head">
					<?php echo get_category_name($item['id'],true); ?>
					
					<div class="pull-right count-show"><small>Просмотров: <?php echo $item['count_show']; ?></small></div>
				</div>
				<div class="title">
					<div><a href="<?php echo get_product_path($item); ?>" class="title-href" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></a></div>
					<div class="title-small"><small><?php echo transform_date($item['date_create'],true).' в '.transform_time($item['date_create']); ?></small></div>
				</div>
				<div class="image"> 
					<a href="<?php echo insert_image('catalog','big',$item['image']); ?>" class="fancybox" title="<?php echo $item['name']; ?>">
						<img src="<?php echo insert_image('catalog','small',$item['image']); ?>" alt="<?php echo $item['name']; ?>" class="img-responsive center-block">
					</a>			
				</div>
				<div class="caption row">
			
					<div class="view-bot-left">
						<a href="<?php echo get_product_path($item); ?>" class="btn btn-default btn-sm">Просмотр</a>
					</div>
					
					<div class="view-bot-right">
						<div class="buttons-recept"><?php echo get_buttons_recept($item,'catalog'); ?></div>
					</div>

				</div>
					
				<?php $comments = Database::getRows(get_table('users_comment'),'id asc',false,'table_name="catalog" and id_table='.$item['id']); ?>
					
				<div class="comment-block <?php if (empty($comments)): ?>hide<?php endif; ?>">
					
					<div class="comment-content"><?php echo html_comment_content($comments); ?></div>
					
					<div class="comment-bottom">
						<div class="input-comment" title="Прокомментировать">Прокомментировать</div>
						<div class="form-comment hide">
							<a href="" class="comment-image">
								<?php $user = cmsUser::getUser(); ?>
								<img src="<?php echo insert_image('users','small',@$user['image']); ?>" alt="">
							</a>					
							<?php echo get_comment_form_add($item['id'],'recepty'); ?>
						</div>	
					</div>
				</div>

			</div>	

		</li>
		
	<?php endforeach; ?>
		
	<?php echo get_dialog_delete('recepty'); ?>	
	
	</ul>	
	
</div>

	

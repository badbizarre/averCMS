<?php 
	$that_path = URL::getSegment(1);
	UI::addCSS(array(
		'/css/catalog/list.css'
	));
if (@$items) : ?>
<?php if (isset($breadcrumbs)): ?>
<div class="breadcrumb"><?php echo $breadcrumbs; ?></div>
<?php endif; ?>
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
			<div class="description"><?php echo get_html_ingredient($item['id']); ?></div>
			<div class="image">
				<a href="<?php echo insert_image('catalog','big',$item['image']); ?>" class="fancybox" title="<?php echo $item['name']; ?>">
					<img src="<?php echo insert_image('catalog','small',$item['image']); ?>" alt="<?php echo $item['name']; ?>" class="img-responsive center-block">
				</a>
			</div>
			<?php if (!empty($item['short_description'])): ?>
			<div class="description desc__bottom"><?php echo $item['short_description']; ?></div>
			<?php endif; ?>
			<div class="caption row">
		
				<div class="view-bot-left">
					<a href="<?php echo get_product_path($item); ?>" class="btn btn-default btn-sm">Просмотр</a>
				</div>
				
				<div class="view-bot-right">
					<div class="buttons-recept"><?php echo get_buttons_recept($item,'catalog'); ?></div>
				</div>
				<?php 
				$comments = Database::getRows(get_table('users_comment'),'id asc',false,'table_name="catalog" and id_table='.$item['id']); 
				if (empty($comments)): ?>
				<div class="view-bot-center">
					<div class="input-comment top-comment" rel="<?php echo $item['id']; ?>" title="Прокомментировать">Прокомментировать</div>
				</div>				
				<?php endif; ?>
			</div>
			
			
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
	<?php endforeach;  ?>
		
	</ul>
			
	<?php echo get_dialog_delete('recepty'); ?>	
			
</div>
<ul class="pagination pagination-sm">
<?php echo $pagination; ?>
</ul>
<?php if (@$tree['short_description']) : ?>
	<div class="item-preview">
		<h2><?php echo $tree['title']; ?></h2>
		<div class="">
			<?php echo nl2br($tree['short_description']); ?>
		</div>
	</div>
<?php endif; ?>
	
<?php endif; ?> 

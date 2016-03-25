<?php 
   
	UI::addCSS(array(
		'/css/catalog/detailed.css',
	));
	
?>		
<div class="b-page-content" itemscope itemtype="http://schema.org/Recipe">
	<div class="breadcrumb"><?php echo $breadcrumbs; ?></div>
	<div style="display: none;" itemprop="name"><?php echo $item['name']; ?></div>
	<div style="display: none;" itemprop="recipeCategory"><?php echo get_category_name($item['id']); ?></div>
	<div class="item-preview">
		<div class="row">
			<div class="col-md-7">		
				<div class="">
					<a href="<?php echo insert_image('catalog','big',$item['image']); ?>" class="fancybox" rel="groupe" title="<?php echo $item['name']; ?>">
						<img src="<?php echo insert_image('catalog','big',$item['image']); ?>" itemprop="resultPhoto" alt="<?php echo $item['name']; ?>" class="img-responsive center-block">
					</a>
				</div>

			</div>
			<div class="col-md-5">

				<?php if (!empty($author)): ?>
				<div class="author-block">
					<div class="author-block__title">Автор</div>
					<div class="row author">
						<div class="col-sm-3">
							<a href="/users/id<?php echo $author['id']; ?>">
								<img src="<?php echo insert_image('users','big',$author['image']); ?>" class="img-responsive" />
							</a>
						</div>
						<div class="col-sm-9">
							<a href="/users/id<?php echo $author['id']; ?>"><?php echo $author['name']; ?></a>
							<div class="author__info">
								<?php echo $author['current_info']; ?>
							</div>
						</div>
					</div>
					
					
				</div>
				<?php endif; ?>
				
				<div class="">
					<?php echo $html_ingredient; ?>
				</div>

			</div>
			
			
		</div>
		
		<div class="row">
			<div class="col-md-12">

				<?php if (!empty($item['short_description'])): ?>
				<div class="">
					<div class="opisanie"><?php echo $item['short_description']; ?></div>
				</div>
				<?php endif; ?>
				
				<div class="">
					<div class="opisanie"><h4>Способ приготовления</h4> <div itemprop="recipeInstructions"><?php echo nl2br($item['recept']); ?></div></div>
				</div>
			
			</div>
		</div>

		<div class="item-content" id="update-<?php echo $item['id']; ?>">
				
				<h4>Оставить комментарий</h4>
				
				<div class="caption row">
		
					<div class="view-bot-right">
						<div class="buttons-recept"><?php echo get_buttons_recept($item,'catalog'); ?></div>
					</div>
					<?php if (empty($comments)): ?>
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
				
				<?php echo get_dialog_delete('recepty'); ?>	
				
		</div>
	
	</div>

</div>
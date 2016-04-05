<?php 
	UI::addCSS(array(
		'/css/cabinet/list.css',
	));	
?>
<div class="row">
	<div class="col-xs-12">
		
		<div class="item-preview">

			<!-- Tab panes -->
			<div class="tab-content">
		
				<div class="row">
					<div class="col-sm-3">
						<a href="<?php echo $image; ?>" class="fancybox">
							<img src="<?php echo $image; ?>" alt="" class="img-responsive">		
						</a>
						<div class="button-panel">
							<?php echo Messages::getMessageButton($item['id']); ?>
							<?php echo Friends::get_friend_button($item['id']); ?>
						</div>
					</div>
					
					<div class="col-sm-9">
						<div class="cabinet-title">
							<div class="row">
								<div class="col-sm-6"><?php echo $item['name']; ?></div>
								<div class="col-sm-6">
									<div class="text-right">
										<small>заходил <?php echo transform_date($item['last_visit'],true).' в '.transform_time($item['last_visit']); ?></small>
									</div>										
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12"><small><a href="#"><?php echo $item['current_info']; ?></a></small></div>
							</div>								
						</div>
						<div class="cabinet-description">
							<div class="row">
								<div class="col-sm-4"><small>Пол:</small></div>
								<div class="col-sm-8"><small><a href="#"><?php echo $sex; ?></a></small></div>
							</div>
							<div class="row">
								<div class="col-sm-4"><small>Дата рождения:</small></div>
								<div class="col-sm-8"><small><?php echo get_birthday_user($item['id']); ?></small></div>
							</div>
							<div class="row">
								<div class="col-sm-4"><small>Город:</small></div>
								<div class="col-sm-8"><small><a href="#"><?php echo $item['city']; ?></a></small></div>
							</div>
							<div class="row">
								<div class="col-sm-4"><small>О себе:</small></div>
								<div class="col-sm-8"><small><?php echo $item['about']; ?></small></div>
							</div>
							<div class="row">
								<div class="col-sm-4"><small>Интересы:</small></div>
								<div class="col-sm-8"><small><?php echo $item['interes']; ?></small></div>
							</div>

						</div>						
					</div>
				</div>
				
			</div>		

		</div>	

	</div>
</div>
							
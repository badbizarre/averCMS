<?php 
	UI::addCSS(array(
		'/css/admin/plugins/chosen/chosen.css',
		'/css/cabinet/list.css'
	));
	
	UI::addJS(array(
		'/js/library/plugins/chosen/chosen.jquery.js',
		'/js/library/plugins/chosen/init.js'
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
						<div class="edit-image text-center"><a href="" data-toggle="modal" data-target="#myModal">Изменить изображение</a></div>
						
						<!-- Modal -->
						<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
							<div class="modal-content">
								<form action="/cabinet/save_avatar/" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
									<div class="modal-body">
										<div class="row">
											<div class="col-xs-12">
											<?php echo html_form_image_crop($image,'users',false); ?>	
											</div>	
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
										<button type="submit" class="btn btn-primary">Сохранить изменения</button>
									</div>
									
								</form>
							</div>
						  </div>
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
								<div class="col-sm-4"><small>Email:</small></div>
								<div class="col-sm-8"><small><a href="#"><?php echo $item['email']; ?></a></small></div>
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
							
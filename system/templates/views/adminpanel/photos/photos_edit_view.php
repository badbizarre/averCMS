<?php 
	UI::addCSS(array(
		'/css/admin/plugins/chosen/chosen.css',
		'/css/admin/plugins/cropper/cropper.css',
		'/css/admin/plugins/cropper/main.css'
	));
	
	UI::addJS(array(
		'/js/library/plugins/chosen/chosen.jquery.js',
		'/js/library/plugins/cropper/cropper.js',
		'/js/library/plugins/cropper/main.js',
		'/js/library/plugins/cropper/tooltip.min.js'
	));
	$path = URL::getSegment(2);
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Редактирование</h5>				
			</div>
			<div class="ibox-content">
				<form method="POST" class="form-horizontal" action="<?php echo get_url_admin_form(2).'/save'; ?>" enctype="multipart/form-data">
					
					<div class="row">
						<div class="col-md-10">
							<?php echo html_input('hidden','id',@$_GET['id']); ?>
							
							<?php echo html_input('hidden','action',URL::getSegment(3)); ?>
							
							<?php echo html_form_group('Активен',html_checkbox('active',$item['active'])); ?>
							
							<?php echo html_form_group('Название',html_input('text','name',$item['name'])); ?>
				
							<?php echo html_form_group('Раздел',html_select_format($trees,'id_tree',$item['id_tree'])); ?>
				
							<div class="hr-line-dashed"></div>
	
							<?php echo html_form_image_crop($image); ?>			

							<div class="hr-line-dashed"></div>
							
							<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
									<a href="javascript:window.history.go(-1);" class="btn btn-white" >Отмена</a>
									<button class="btn brn-small btn-primary" type="submit">Сохранить</button>	
								</div> 								
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<img src="<?php echo insert_image($path,'small',$item['image']); ?>" alt="Picture" class="img-responsive">
							</div>	
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
 
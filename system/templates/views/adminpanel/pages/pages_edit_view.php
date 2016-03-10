<?php 
	UI::addCSS(array(
		'/css/admin/plugins/chosen/chosen.css'
	));
	
	UI::addJS(array(
		'/js/library/plugins/chosen/chosen.jquery.js',
		'/js/library/plugins/chosen/init.js'
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

					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#tab-1">Общее</a></li>
						<li class=""><a data-toggle="tab" href="#tab-2">Описание</a></li>
						<li class=""><a data-toggle="tab" href="#tab-3">Meta</a></li>
					</ul>
		  
			
					<div class="tab-content panel-body">
						
						<div id="tab-1" class="tab-pane active">
							<div class="row">
								<div class="col-md-12">

									<?php echo html_input('hidden','id',@$_GET['id']); ?>
									
									<?php echo html_input('hidden','action',URL::getSegment(3)); ?>
									
									<?php echo html_form_group('Активен',html_checkbox('active',$item['active'])); ?>
									
									<?php echo html_form_group('Название',html_input('text','name',$item['name'])); ?>
									
									<?php echo html_form_group('Путь',html_input('text','path',$item['path'],array('class'=>'translit'))); ?>
	
									<?php echo html_form_group('Приоритет',html_input('text','prioritet',$item['prioritet'])); ?>
																	
									<div class="hr-line-dashed"></div>
					
								</div>
							</div>								
						</div>
						<div id="tab-2" class="tab-pane">
							<div class="row">
								<div class="col-md-12">		
									<?php echo html_textarea('short_description',$item['short_description']); ?>
								</div>
							</div>
						</div>
						<div id="tab-3" class="tab-pane">
							<div class="row">
								<div class="col-md-12">	
	
									<?php echo html_form_group('Title',html_input('text','title',$item['title'])); ?>
									
									<?php echo html_form_group('Keywords',html_textarea('keywords',$item['keywords'])); ?>
																				
									<?php echo html_form_group('Description',html_textarea('description',$item['description'])); ?>
								</div>
							</div>
						</div>

						<div class="hr-line-dashed"></div>
						
						<div class="form-group">
							<div class="col-md-12">
								<a href="javascript:window.history.go(-1);" class="btn btn-white" >Отмена</a>
								<button class="btn brn-small btn-primary btn-ajax" data-url="<?php echo get_url_admin_form(2); ?>" type="button">Сохранить</button>	
							</div> 								
						</div>								
						
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
 
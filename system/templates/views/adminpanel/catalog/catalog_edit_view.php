<?php 
	UI::addCSS(array(
		'/css/admin/plugins/chosen/chosen.css',	
	));
	
	UI::addJS(array(
		'/js/library/plugins/chosen/chosen.jquery.js',
		'/js/library/plugins/chosen/init.js',
	));
	$path = URL::getSegment(2);
	$full_path = get_url_admin_form(2);

?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Редактирование</h5>				
			</div>
			<div class="ibox-content">
				<form method="POST" class="form-horizontal" action="<?php echo $full_path.'/save'; ?>" target="hiddenframe" enctype="multipart/form-data" >
									
					<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>
									
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#tab-1">Общее</a></li>
						<li class=""><a data-toggle="tab" href="#tab-2">Рецепт</a></li>
						<li class=""><a data-toggle="tab" href="#tab-3">Meta</a></li>
					</ul>
  
					<div class="tab-content panel-body">
						
						<div id="tab-1" class="tab-pane active">
							<div class="row">
								<div class="col-md-10">
								
									<?php echo html_input('hidden','id',@$_GET['id']); ?>
									
									<?php echo html_input('hidden','action',URL::getSegment(3)); ?>
									
									<?php echo html_form_group('Активен',html_checkbox('active',$item['active'])); ?>
									
									<?php echo html_form_group('Название',html_input('text','name',$item['name'])); ?>
									
									<?php echo html_form_group('Краткое описание',html_textarea('short_description',$item['short_description'])); ?>
									
									<?php echo html_form_group('Время (мин)',html_input('text','time',$item['time'])); ?>
									
									<?php echo html_form_group('Путь',html_input('text','path',$item['path'],array('class'=>'translit'))); ?>
						
									<?php echo html_form_group('Раздел',html_multi_select($trees,'id_tree',$values,array('class'=>'chosen-select','data-placeholder'=>'Выберите раздел...'))); ?>
							
									<div class="hr-line-dashed"></div>
					
									<?php echo html_form_image_crop($image); ?>

									<div class="hr-line-dashed"></div>
									
									<div id="clone-ingredient">
										
										<?php 
										if (isset($catalog_ingredients) and !empty($catalog_ingredients)):
										foreach($catalog_ingredients as $elem): 
										?>
									
										<div class="clone">
											<div class="form-group">
												<div class="col-xs-offset-2 col-xs-4">
													<label>Ингредиент</label>
													<?php echo html_select($ingredients,'id_ingredient[]',@$elem['id_ingredient'],array('class'=>'chosen-select','data-placeholder'=>'Выберите ингридиент...','data-action'=>$full_path.'/add_ingredients')); ?>
												</div>
												<div class="col-xs-2">
													<label>Количество</label>
													<?php echo html_input('text','kolvo[]',@$elem['kolvo']); ?>
												</div>
												<div class="col-xs-3">
													<label>Мера веса/объема</label>
													<?php echo html_select($measures,'id_measure[]',@$elem['id_measure'],array('class'=>'chosen-select','data-placeholder'=>'Выберите меру...')); ?>
												</div>
												<div class="col-xs-1">
													<a href="#" onclick="return removeIngrow(this)"class="btn btn-xs btn-danger ingredient-delete del-clone"><i class="fa fa-trash"></i></a>
												</div>
											</div>
										</div>
										
										<?php 
										endforeach;
										else:										
										?>										
									
										<div class="clone">
											<div class="form-group">
												<div class="col-xs-offset-2 col-xs-4">
													<label>Ингредиент</label>
													<?php echo html_select($ingredients,'id_ingredient[]',@$elem['id_ingredient'],array('class'=>'chosen-select','data-placeholder'=>'Выберите ингридиент...','data-action'=>$full_path.'/add_ingredients')); ?>
												</div>
												<div class="col-xs-2">
													<label>Количество</label>
													<?php echo html_input('text','kolvo[]',@$elem['kolvo']); ?>
												</div>
												<div class="col-xs-3">
													<label>Мера веса/объема</label>
													<?php echo html_select($measures,'id_measure[]',@$elem['id_measure'],array('class'=>'chosen-select','data-placeholder'=>'Выберите меру...')); ?>
												</div>
												<div class="col-xs-1">
													<a href="#" onclick="return removeIngrow(this)"class="btn btn-xs btn-danger ingredient-delete del-clone"><i class="fa fa-trash"></i></a>
												</div>
											</div>
										</div>
										
										<?php endif; ?>
										
									</div>
									
									<div class="form-group">
										<div class="col-xs-offset-2 col-xs-10">
											<a class="btn btn-xs btn-success add-clone" rel="clone-ingredient">Добавить еще</a>							
										</div>
									</div>
									
								</div>
							
								<div class="col-md-2">
									<div class="form-group">
										<img src="<?php echo insert_image($path,'small',$item['image']); ?>" alt="Picture" class="img-responsive">
									</div>	
								</div>
							</div>								
						</div>
						<div id="tab-2" class="tab-pane">
							<div class="row">
								<div class="col-md-12">		
									<?php echo html_textarea('recept',$item['recept'],array('class'=>'h-200')); ?>
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
								<button class="btn brn-small btn-primary btn-frame" type="submit">Сохранить</button>	
							</div> 								
						</div>								
						
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
 
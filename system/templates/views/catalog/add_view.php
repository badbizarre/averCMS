<?php 
	UI::addCSS(array(
		'/css/admin/plugins/chosen/chosen.css',		
		'/css/admin/font-awesome/css/font-awesome.css',		
		'/css/catalog/add.css',		
	));
	
	UI::addJS(array(
		'/js/library/plugins/chosen/chosen.jquery.js',
		'/js/library/plugins/chosen/init.js',
		'/js/admin/admin.js',
		'/js/admin/functions.js',
	));
	$path = URL::getSegment(1);

?>
<div class="item-preview">
	<div class="">

		<div class="alert alert-warning"><b>Внимание</b> Ваш рецепт будет опубликован на сайте после проверки модератором</div>
	
		<?php if (in_array(cmsUser::sessionGet('user:id'),array(1,2,3))): ?>
		<form method="POST" class="form-horizontal" action="">

			<?php echo html_form_group('Ссылка',html_input('text','url')); ?>
			
			<div class="form-group">
				<small class="col-xs-10 col-xs-offset-2 fg__small">
					Перейти на <a href="http://www.povarenok.ru/recipes/" target="_ablank" title="">сайт</a> где брать ссылки.<br/>
					Примечание: не по всем ссылкам могут быть добавлены данные, если данные не добавляются попробуйте другую ссылку.
				</small>
			</div>
					
			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<button class="btn brn-small btn-primary btn-frame" type="submit">Добавить данные</button>	
				</div> 								
			</div>	
			
		</form>
		<?php endif; ?>
	
		<form method="POST" class="form-horizontal" action="/<?php echo $path; ?>/save" target="hiddenframe" enctype="multipart/form-data" >
							
			<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>

			<?php echo html_input('hidden','id',@$_GET['id']); ?>

			<?php echo html_form_group('Название',html_input('text','name',$item['name'])); ?>
			
			<?php echo html_form_group('Краткое описание',html_textarea('short_description',$item['short_description'],array('class'=>'h-200'))); ?>
			
			<div class="form-group">
				<small class="col-xs-10 col-xs-offset-2 fg__small">
					Обязательно напишите 2-3 строки - что это за блюдо, его особенности, вкусовые качества.
					Этот текст будет показываться в качестве анонса к Вашему рецепту - и именно по этому 
					тексту люди будут определять, стоит читать Ваш рецепт - или нет.
				</small>
			</div>
			
			<?php echo html_form_group('Время (мин)',html_input('text','time',$item['time'])); ?>

			<?php echo html_form_group('Раздел',html_multi_select($trees,'id_tree',$values,array('class'=>'chosen-select','data-placeholder'=>'Выберите раздел...'))); ?>
			
			<div class="form-group">
				<small class="col-xs-10 col-xs-offset-2 fg__small">
					Можно выбирать несколько разделов.
				</small>
			</div>
						
			<?php echo html_form_group('Рецепт',html_textarea('recept',$item['recept'],array('class'=>'h-200'))); ?>
			
			<div class="form-group">
				<small class="col-xs-10 col-xs-offset-2 fg__small">
					Здесь пишите способ приготовления блюда. Рекомендуется шаги разделять энтерами и чередовать цифрами.<br/>
					Например:<br/>
					1. Духовку разогреть до 210 С. Ванильный сахар смешать с обычным сахаром. <br/>
					2. Всыпать просеянную муку и какао, перемешать.
				</small>
			</div>
											
			<div class="hr-line-dashed"></div>

			<?php echo html_form_image_crop($image,'catalog'); ?>
			
			<div class="form-group">
				<small class="col-xs-10 col-xs-offset-2 fg__small">
					Минимальный размер картинки <?php echo $img_size['width'].'x'.$img_size['height']; ?> (ШхВ)
				</small>
			</div>
					
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
							<?php echo html_select($ingredients,'id_ingredient[]',@$elem['id_ingredient'],array('class'=>'chosen-select','data-placeholder'=>'Выберите ингридиент...')); ?>
						</div>
						<div class="col-xs-2">
							<label>Количество</label>
							<?php echo html_input('text','kolvo[]',@$elem['kolvo']); ?>
						</div>
						<div class="col-xs-3">
							<label>Мера веса/объема</label>
							<?php echo html_select($measures,'id_measure[]',@$elem['id_measure'],array('class'=>'chosen-select','data-placeholder'=>'Выберите меру...')); ?>
						</div>
						<div class="col-xs-1 clone__delete">
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
							<?php echo html_select($ingredients,'id_ingredient[]',@$elem['id_ingredient'],array('class'=>'chosen-select','data-placeholder'=>'Выберите ингридиент...')); ?>
						</div>
						<div class="col-xs-2">
							<label>Количество</label>
							<?php echo html_input('text','kolvo[]',@$elem['kolvo']); ?>
						</div>
						<div class="col-xs-3">
							<label>Мера веса/объема</label>
							<?php echo html_select($measures,'id_measure[]',@$elem['id_measure'],array('class'=>'chosen-select','data-placeholder'=>'Выберите меру...')); ?>
						</div>
						<div class="col-xs-1 clone__delete">
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

		<div class="hr-line-dashed"></div>
		
		<div class="form-group">
			<div class="col-md-12">
				<a href="javascript:window.history.go(-1);" class="btn btn-default" >Назад</a>
				<button class="btn brn-small btn-primary btn-frame" type="submit">Сохранить</button>	
			</div> 								
		</div>								

		</form>

	</div>
</div>
 
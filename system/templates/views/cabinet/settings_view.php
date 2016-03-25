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

			<form action="/cabinet/save/" method="post" class="form-horizontal jsform" role="form">
					

				<?php echo html_input('hidden','action','edit'); ?>
							
				<?php echo html_form_group('Имя',html_input('text','name',$item['name'])); ?>
				
				<?php echo html_form_group('Пол',html_select(array_select_sex(),'sex',@$item['sex'],array('class'=>'chosen-select'))); ?>							
							
				<?php echo html_form_group('Email',html_input('email','email',$item['email'])); ?>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Дата рождения</label>
					<div class="col-sm-2">
						<?php echo html_select(array_select_day(),'birthday_day',@$item['birthday_day'],array('class'=>'chosen-select'));	?>							
					</div>
					<div class="col-sm-2">
						<?php echo html_select(array_select_month(),'birthday_month',@$item['birthday_month'],array('class'=>'chosen-select'));	?>
					</div>
					<div class="col-sm-2">
						<?php echo html_select(array_select_year(),'birthday_year',@$item['birthday_year'],array('class'=>'chosen-select'));	?>							
					</div>
					<div class="col-sm-4"></div>
				</div>
				
				<?php echo html_form_group('Город',html_input('city','city',$item['city'])); ?>	
				
				<?php echo html_form_group('Подпись',html_input('current_info','current_info',$item['current_info'])); ?>	
				
				<?php echo html_form_group('О себе',html_textarea('about',$item['about'])); ?>	
				
				<?php echo html_form_group('Интересы',html_textarea('interes',$item['interes'])); ?>	

				<div class="form-group">
					<div class="col-xs-offset-2 col-xs-10">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>	
				</div>						
		
			</form>

		</div>	

	</div>
</div>
							
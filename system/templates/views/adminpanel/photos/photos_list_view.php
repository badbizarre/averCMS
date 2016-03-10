<?php 
	UI::addCSS(array(
		'/css/admin/plugins/dropzone/basic.css',
		'/css/admin/plugins/dropzone/dropzone.css',
		'/css/admin/plugins/chosen/chosen.css'
	));
	
	UI::addJS(array(
		'/js/admin/'.URL::getSegment(2).'/'.URL::getSegment(2).'_table.js',
		'/js/library/plugins/dropzone/dropzone.js',
		'/js/library/plugins/chosen/chosen.jquery.js'
	));
?>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Загрузить несколько фото</h5>
				<div class="ibox-tools">
					<div class="btn-group">
						<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Действие <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="" class="no-href">Активировать</a></li>
							<li><a href="" class="no-href">Деактивировать</a></li>					
							<li class="divider"></li>
							<li><a href="" class="no-href" id="delete-items">Удалить</a></li>
						</ul>
					</div>
					<a href="" id="add-images" class="btn btn-primary btn-xs no-href"><i class="fa fa-upload"></i> Загрузить</a>					
				</div>	
			</div>	
			<div class="ibox-content">	
				<div class="row m-b-sm m-t-sm">
					<div class="col-md-1">
						<button type="button" id="loading-example-btn" class="btn btn-white btn-sm" ><i class="fa fa-refresh"></i> Обновить</button>
					</div>
					<div class="col-md-11">
						<div class="input-group"><input type="text" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
							<button type="button" class="btn btn-sm btn-primary"> Поиск!</button> </span></div>
					</div>
				</div>			
				<div class="jqGrid_wrapper">
					<table id="jqtable"></table>
					<div id="jqpager"></div>	
				</div>				
			</div>
		</div>
	</div>
</div>

	
	<!-- Модальное окно для загрузки фотографий -->
	<div class="modal fade" id="dialog-images" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
									
				<div class="modal-content">
					<form class="form-horizontal dropzone" role="form" action="<?php echo get_url_admin_form(2).'/addfile'; ?>" method="POST">
						<div class="dropzone-previews"></div>
							
						<div class="row">
							<div class="col-xs-9">
								<?php echo html_form_group('Раздел',html_select_format($trees,'id_tree','',array('id'=>'val_image'))); ?>
							</div>
							<div class="col-xs-3">
								<button type="submit" class="btn btn-primary">Загрузить</button>
							</div>
						</div>
						
														
						
					</form>
				</div>
			  
		</div>
	</div>	


	<div class="modal fade bs-example-modal-sm" id="dialog-del" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<form class="form-horizontal" role="form" action="<?php echo get_url_admin_form(2).'/delete'; ?>" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Предупреждение</h4>
					</div>	
					<div class="modal-body">
					
						<?php echo html_input('hidden','id_del','',array('id'=>'val_id_del')); ?>
				
						Удалить выбранные элементы?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						<button type="submit" class="btn btn-danger">Удалить</button>
					</div>				
				</div>
			</form>	
		</div>
	</div>


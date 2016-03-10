<?php 
	UI::addCSS(array(
		'/css/admin/plugins/treeview/jquery.treeview.css',
	));
	
	UI::addJS(array(
		'/js/library/plugins/treeview/jquery.cookie.js',
		'/js/library/plugins/treeview/jquery.treeview.js',
		'/js/admin/tree.js'
	));
?>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-title">
				<a href="" id="add-data-tree" class="btn btn-primary btn-xs no-href"><i class="fa fa-plus"></i> </a>
				<a href="" id="edit-data-tree" class="btn btn-primary btn-xs no-href"><i class="fa fa-edit"></i> </a>
				<a href="" id="del-data-tree" class="btn btn-primary btn-xs no-href"><i class="fa fa-trash"></i> </a>	
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-wrench"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="#">Config option 1</a>
						</li>
						<li><a href="#">Config option 2</a>
						</li>
					</ul>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>				
			</div>	
			<div class="ibox-content">
				<div class="filetree">
					<?php echo get_tree($trees); ?>
				</div>	
			</div>
		</div>
	</div>
</div>
	
	<div class="modal fade bs-example-modal-lg" id="dialog-tree-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<form class="form-horizontal" role="form_tree" action="<?php echo get_url_admin_form(2).'/edit_tree'; ?>" method="POST">

			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Свойства</h4>
				</div>
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-tree-1" data-toggle="tab">Информация</a></li>
						<li><a href="#tab-tree-2" data-toggle="tab">Описание</a></li>
						<li><a href="#tab-tree-3" data-toggle="tab">Мета</a></li>
					</ul>
					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane active" id="tab-tree-1">
							
								<?php echo html_input('hidden','id','',array('id'=>'val_tree_id')); ?>
								
								<?php echo html_input('hidden','pid','',array('id'=>'val_tree_pid')); ?>
								
								<?php echo html_input('hidden','level','',array('id'=>'val_tree_level')); ?>
							
								<?php echo html_input('hidden','action','',array('id'=>'val_tree_action')); ?>
							
								<?php echo html_form_group('Активен',html_checkbox('active','',array('id'=>'val_tree_active'))); ?>				
									
								<?php echo html_form_group('Наименование',html_input('text','name','',array('id'=>'val_tree_name'))); ?>
								
								<?php echo html_form_group('Путь',html_input('text','path','',array('class'=>'translit','id'=>'val_tree_path'))); ?>
												
								<?php echo html_form_group('Приоритет',html_input('text','prioritet','',array('id'=>'val_tree_prioritet'))); ?>

							</div>
							
							<div class="tab-pane" id="tab-tree-2">
								
								<?php echo html_form_group('Краткое описание',html_textarea('short_description','',array('class'=>'h-200','id'=>'val_tree_short_description'))); ?>
								
							</div>
							
							<div class="tab-pane" id="tab-tree-3">

								<?php echo html_form_group('Title',html_input('text','title','',array('id'=>'val_tree_title'))); ?>
								
								<?php echo html_form_group('Keywords',html_input('text','keywords','',array('id'=>'val_tree_keywords'))); ?>
								
								<?php echo html_form_group('Description',html_textarea('description','',array('id'=>'val_tree_description'))); ?>
								  
							</div>

						</div>			
					</div>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary">Сохранить изменения</button>
				</div>
			</div>
			</form>  
		</div>
	</div>

	<div class="modal fade bs-example-modal-sm" id="dialog-tree-del" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<form class="form-horizontal" role="form_tree" action="<?php echo get_url_admin_form(2).'/delete_tree'; ?>" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Предупреждение</h4>
					</div>	
					<div class="modal-body">
					
						<?php echo html_input('hidden','id_del','',array('id'=>'val_tree_id_del')); ?>
					
						Вы действительно хотите удалить выбранный элемент?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						<button type="submit" class="btn btn-danger">Удалить</button>
					</div>				
				</div>
			</form>	
		</div>
	</div>

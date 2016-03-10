<?php 
	$path = URL::getSegment(2);
	$this_path = '/adminpanel/'.$path;
	
	UI::addJS(array(
		'/js/admin/'.$path.'/'.$path.'_table.js',
	));
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Все пользователи</h5>
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
					<a href="<?php echo $this_path; ?>/add/" class="btn btn-primary btn-xs">Создать нового</a>
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


<div class="modal fade bs-example-modal-sm" id="dialog-del" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<form class="form-horizontal" role="form-del" action="<?php echo get_url_admin_form(2).'/delete'; ?>" method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Предупреждение</h4>
				</div>	
				<div class="modal-body">
				
					<?php echo html_input('hidden','id_del','',array('id'=>'val_id_del')); ?>
				
					<?php echo html_input('hidden','action_del','',array('id'=>'val_action_del')); ?>
					
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
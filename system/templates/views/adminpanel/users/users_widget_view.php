<?php 
	$count = Database::getCount(get_table('users'),'active = 0');
	$users = Database::getRows(get_table('users'),'date_create','desc',8,'active = 0');
?>
  <!-- USERS LIST -->
  <div class="box box-danger">
	<div class="box-header with-border">
	  <h3 class="box-title">Последние пользователи</h3>
	  <div class="box-tools pull-right">
		<span class="label label-danger"><?php echo $count; ?> новых пользователей</span>
		<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	  </div>
	</div><!-- /.box-header -->
	<div class="box-body no-padding">
	  <ul class="users-list clearfix">
		<?php foreach($users as $user): ?>
		<li>
		  <img src="/img/admin/user1-128x128.jpg" alt="User Image"/>
		  <a class="users-list-name" href="#"><?php echo $user['name']; ?></a>
		  <span class="users-list-date"><?php echo transform_date($user['date_create']); ?></span>
		</li>
		<?php endforeach; ?>
	  </ul><!-- /.users-list -->
	</div><!-- /.box-body -->
	<div class="box-footer text-center">
	  <a href="/adminpanel/users" class="uppercase">Все пользователи</a>
	</div><!-- /.box-footer -->
  </div><!--/.box -->	
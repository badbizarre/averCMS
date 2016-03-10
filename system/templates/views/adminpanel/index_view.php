<div class="row">
	<div class="col-lg-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Комментарии</h5>
				<div class="ibox-tools">
					<span class="label label-warning-light"><?php echo get_count_comment(count($comments)); ?></span>
				</div>
			</div>
			<div class="ibox-content">

				<div>
					<div class="feed-activity-list">

						<?php 
						$html = '';
						foreach($comments as $com_el) {  

							$user = Database::getRow(get_table('users'),$com_el['id_user']); 
							$image = insert_image('users','small',$user['image']);
							$date_create = transform_date($com_el['date_create']);
							$time_create = transform_time($com_el['date_create']);
							$com_childs = Database::getRows(get_table('users_comment'),'id desc',false,'pid='.$com_el['id']);
							$product = Database::getRow(get_table($com_el['table_name']),$com_el['id_table']);
							
							$html .= '<div class="feed-element">
							<a class="pull-left" href="profile.html">
								<img src="'.$image.'" class="img-circle" alt="image">
							</a>
							<div class="media-body ">
								<small class="pull-right">'.$date_create.'</small>
								<strong>'.$user['name'].'</strong> оставил комментарий <a href="/recept/'.$product['path'].'">'.$product['name'].'</a> <br>	
								<small class="text-muted">'.$date_create.' - '.$time_create.'</small>
								<p>'.$com_el['comment'].'</p>';
							if (!empty($com_childs)) {
								foreach($com_childs as $com_child) {
								
									$user = Database::getRow(get_table('users'),$com_child['id_user']);
									$html .= '<div class="well">
									<strong>'.$user['name'].'</strong> <small>ответил</small>
									<div>'.$com_child['comment'].'</div>
									</div>';

								}
							}
							$html .= '</div>
							</div>';

						}
						echo $html;
						?>
			
					</div>

					<button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Показать еще</button>

				</div>

			</div>
		</div>

	</div>
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-success pull-right">Всё время</span>
						<h5>Просмотры</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins"><?php echo Database::getSum(get_table('catalog'),'1','count_show'); ?></h1>
						<div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
						<small>Всего просмотров</small>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-info pull-right">Всё время</span>
						<h5>Понравилось</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins"><?php echo Database::getSum(get_table('catalog'),'1','count_like'); ?></h1>
						<div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
						<small>Всего лайков</small>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-success pull-right">Всё время</span>
						<h5>Поделились</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins"><?php echo Database::getSum(get_table('catalog'),'1','count_note'); ?></h1>
						<div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
						<small>Всего поделилось</small>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-success pull-right">Всё время</span>
						<h5>Пользователи</h5>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins"><?php echo Database::getCount(get_table('users')); ?></h1>
						<div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
						<small>Всего пользователей</small>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
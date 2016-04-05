<?php 
	$that_path = URL::getSegment(1);
	UI::addCSS(array(
		'/css/'.$that_path.'/dialog.css'
	));
?>
<script>
$(document).ready(function() {
	$('.dialog__wrap').scrollTop($('.dialog__wrap').height());
});
</script>
<div class="dialogs">
	<div class="item-preview">
		<div class="dialog__wrap scroll__bar--white">
		<?php 
		foreach($items as $item): 
		$sender = Users::getUser($item['from_user_id']); 
		$message = Messages::getMessage($item['id_message']);
		?>
			<div class="dialog__line <?php if ($item['is_read']==0) echo 'dialog__line--noread'; ?>">
				<div class="row">
					<div class="col-xs-1">
						<div class="dialog__image">
							<a href="/users/id<?php echo $sender['id']; ?>">
								<img src="<?php echo insert_image('users','small',@$sender['image']); ?>" alt="" class="img-responsive img-circle">
							</a>
						</div>
					</div>
					<div class="col-xs-11">			
						<div class="dialog__block-text">
							<div class="dialog__name-user">
								<a href="/users/id<?php echo $sender['id']; ?>">
									<?php echo $sender['name']; ?>
								</a>
							</div>
							<div class="dialog__date text-right">
								<?php echo transform_date($message['date_create']).' в '.transform_time($message['date_create']); ?>

								<span class="dialog__remove-btn btn-remove__message" rel="<?php echo $item['id']; ?>" data-toggle="modal">
									<span class="glyphicon glyphicon-remove" title="Удалить"></span>
								</span>	
			
							</div>
							<div class="dialog__comment"><?php echo nl2br($message['comment']); ?></div>
						</div>
					</div>
				</div>
			</div>
		
		<?php endforeach; ?>
		</div>
		<div class="dialog__controls-msg">
			<div class="row">
				<div class="col-xs-1">
					<?php $from_user = Users::getUser(cmsUser::sessionGet('user:id')); ?>
					<div class="dialog__controls-img">
						<a href="/users/id<?php echo $from_user['id']; ?>" title="<?php echo $from_user['name']; ?>">
							<img src="<?php echo insert_image('users','small',@$from_user['image']); ?>" alt="" class="img-responsive img-circle">
						</a>	
					</div>					
				</div>
				<div class="col-xs-10">
					<form class="jsform form-horizontal" role="form" action="/<?php echo $that_path; ?>/add_msg" method="POST">
						<?php 
						echo html_input('hidden','user_id',$id_recipient);
						echo html_textarea('comment','',array('class'=>"dialog__controls-textarea")); 
						?>
						<div class="dialog__controls-btns">
							<button type="submit" class="btn btn-success">Отправить</button>
						</div>				
					</form>				
				</div>
				<div class="col-xs-1">
					<?php $for_user = Users::getUser($id_recipient); ?>
					<div class="dialog__controls-img">
						<a href="/users/id<?php echo $for_user['id']; ?>" title="<?php echo $for_user['name']; ?>">
							<img src="<?php echo insert_image('users','small',@$for_user['image']); ?>" alt="" class="img-responsive img-circle">
						</a>	
					</div>	
				</div>
			</div>	
		</div>
	</div>
</div>

<div class="modal fade" id="dialog-remove__message" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<form class="form-horizontal jsform form-remove__message" role="form" action="/<?php echo $that_path; ?>/delete_msg" method="POST">
			<div class="modal-content">
				<div class="modal-body">
					<?php 
					echo html_input('hidden','msg_id');
					echo 'Удалить это сообщение навсегда?';
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-danger">Удалить</button>
				</div>				
			</div>
		</form>	
	</div>
</div>	

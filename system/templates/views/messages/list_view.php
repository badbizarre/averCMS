<?php 
	$that_path = URL::getSegment(1);
	UI::addCSS(array(
		'/css/'.$that_path.'/list.css'
	));
?>
<div class="messages">
	<div class="item-preview">
		<?php foreach($items as $item): 

		$recipient = Users::getUser($item['iuser_id']); 
		$sender = Users::getUser($item['im_user_id']); 			
	
		$last_message = Database::getRows(get_table('users_messages'),'id desc',1,'is_delete = 0 and id_dialog = '.$item['id']);
		if (empty($last_message)) continue;
		$last_message = $last_message[0];
		$message = Messages::getMessage($last_message['id_message']);
		?>
			<div class="message__line <?php if ($last_message['is_read']==0 and $last_message['for_user_id'] == $recipient['id']) echo 'message__line--noread'; ?>" id="dialog<?php echo $item['im_user_id']; ?>" rel="<?php echo $item['id']; ?>">
				<div class="row">
					<div class="col-xs-4">
						<div class="row">
							<div class="col-xs-4">
								<div class="message__image">
									<a href="/users/id<?php echo $sender['id']; ?>">
										<img src="<?php echo insert_image('users','small',@$sender['image']); ?>" alt="" class="img-responsive">
									</a>
								</div>							
							</div>
							<div class="col-xs-8">
								<div class="message__name-user">
									<a href="/users/id<?php echo $sender['id']; ?>">
										<?php echo $sender['name']; ?>
									</a>
								</div>
								<div class="message__date">
									<?php echo transform_date($message['date_create']).' в '.transform_time($message['date_create']); ?>
								</div>
								<span class="message__kolvo">
									<?php echo get_count_msg($item['msg_num']); ?>
								</span>
								
							</div>
						</div>

						
					</div>
					<div class="col-xs-8">		
						<div class="message__block-text <?php if ($last_message['is_read']==0 and $last_message['for_user_id'] != $recipient['id']) echo 'message__line--noread'; ?>">

							<span class="message__remove-btn btn-remove__dialog" rel="1" data-toggle="modal">
								<span class="glyphicon glyphicon-remove" title="Удалить"></span>
							</span>	
	
							<?php if ($last_message['for_user_id'] != $recipient['id']): ?>
	
							<div class="message__image--small">
								<img src="<?php echo insert_image('users','small',@$recipient['image']); ?>" alt="" class="img-responsive">
							</div>							
					
							<?php endif; ?>

							<div class="message__text">	
								<div class="message__comment"><?php echo nl2br($message['comment']); ?></div>							
							</div>

						</div>
					</div>
				</div>
			</div>
		
		<?php endforeach; ?>
	</div>
</div>	


<div class="modal fade" id="dialog-remove__dialog" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<form class="form-horizontal jsform form-remove__dialog" role="form" action="/<?php echo $that_path; ?>/delete_dialog" method="POST">
			<div class="modal-content">
				<div class="modal-body">
					<?php 
					echo html_input('hidden','dialog_id');
					echo 'Вы действительно хотите удалить всю переписку в этой беседе?<br/><br/>Отменить это действие будет невозможно.';
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

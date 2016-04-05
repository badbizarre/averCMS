<?php

	if (!cmsUser::isSessionSet('user')) {
		
	echo '<ul class="navbar-top-links text-right">		
		
		<li><a href="/registration">Регистрация</a></li>

		<li><a href="" class="count-info" data-toggle="modal" data-target="#dialog-login">Войти</a></li>

	</ul>';
	
	echo '<div class="modal fade" tabindex="-1" id="dialog-login" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-body">
						<form action="/registration/login/" method="post" class="form jsform" role="form">
							
							<p><strong>Форма входа</strong></p>
							
							<div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="Ваш Email" />
							</div>	

							<div class="form-group">
								<input type="password" name="password" class="form-control" placeholder="Ваш пароль" />
							</div>	
									
							<div class="checkbox">
								<label>
									  <input type="checkbox" name="remember"> Запомнить меня
								</label>
							</div>		

							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-sm btn-success">Войти</button>
									<a href="/registration/" class="btn btn-sm btn-default">Регистрация</a>
								</div>
							</div>
						
							
						</form>
						</div>
					</div>
				</div>
			</div>';
	
	} else {
		$user = cmsUser::getUser();

		echo '<div class="col-xs-8 text-center">
			<a href="/cabinet/notifications" class="my-answer">
				<span class="glyphicon glyphicon-envelope"></span> 
				<span class="answer__label">Мои ответы</span>
			</a>
		</div>		
		<div class="ava-user col-xs-4 text-right">
			<div class="dropdown-toggle" data-toggle="dropdown">
				<img src="'.insert_image('users','small',$user['image']).'" alt="" class="img-circle" style="height: 44px;">
			</div>
			<ul class="dropdown-menu" role="menu">
				<li><a href="/cabinet">Личный кабинет</a></li>
				<li><a href="/cabinet/likes_note">Мои заметки</a></li>
				<li><a href="/cabinet/settings">Мои настройки</a></li>
				<li class="divider"></li>
				<li><a href="/registration/logout">Выход</a></li>
			</ul>
		</div>';
		
	}
	

	
?>
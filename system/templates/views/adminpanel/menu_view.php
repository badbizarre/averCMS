<?php
$admin = Database::getRow(get_table('adminusers'),$_SESSION['isLoggedIn']['id']);
$that_path = URL::getSegment(2);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<img alt="image" class="img-circle" style="height:50px;" src="<?php echo insert_image('adminusers','small',$admin['image']); ?>" />
					 </span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION['isLoggedIn']['name']; ?></strong>
					 </span> <span class="text-muted text-xs block">Администратор <b class="caret"></b></span> </span> </a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<li <?php check_active_url('adminusers'); ?>><a href="/adminpanel/adminusers">Учетные данные</a></li>
						<li><a href="contacts.html">Contacts</a></li>
						<li><a href="mailbox.html">Mailbox</a></li>
						<li class="divider"></li>
						<li><a href="/adminpanel/?logout">Выход</a></li>
					</ul>
				</div>
				<div class="logo-element">
					Aver
				</div>
			</li>
			<li <?php if (in_array($that_path,array('photos','catalog','pages','navigation','article'))) echo 'class="active"'; ?>>
				<a href="/"><i class="fa fa-th-large"></i> <span class="nav-label">Контент</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li <?php check_active_url('photos'); ?> ><a href="/adminpanel/photos">Фотографии</a></li>
					<li <?php check_active_url('catalog'); ?> ><a href="/adminpanel/catalog">Каталог</a></li>
					<li <?php check_active_url('pages'); ?> ><a href="/adminpanel/pages">Страницы</a></li>
					<li <?php check_active_url('article'); ?> ><a href="/adminpanel/article">Статьи</a></li>
					<li <?php check_active_url('navigation'); ?> ><a href="/adminpanel/navigation">Навигация</a></li>
				</ul>
			</li>
			<li <?php if (in_array($that_path,array('ingredients','measures'))) echo 'class="active"'; ?>>
				<a href="/"><i class="fa fa-book"></i> <span class="nav-label">Справочники</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li <?php check_active_url('ingredients'); ?>><a href="/adminpanel/ingredients">Ингредиенты</a></li>
					<li <?php check_active_url('measures'); ?>><a href="/adminpanel/measures">Мера веса</a></li>
				</ul>			
			</li>
			<li <?php check_active_url('users'); ?>>
				<a href="/adminpanel/users"><i class="fa fa-users"></i> <span class="nav-label">Пользователи</span> </a>
			</li>
		</ul>

	</div>
</nav>
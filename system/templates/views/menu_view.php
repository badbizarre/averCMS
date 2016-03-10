<?php 
$pages = Database::getRows(get_table('navigation'),'prioritet desc',4,'pid = 0');
?>
<div class="navigation">
	<nav class="navbar navbar-default" role="navigation">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
				<span class="sr-only">ПоварёнОК</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="navbar-collapse">
			<ul class="nav navbar-nav">
				<?php 
				foreach ($pages as $page) {
					if (URL::getPath() == $page['path']) $cls = 'class="active"'; else $cls = '';
					$child_pages = Database::getRows(get_table('navigation'),'prioritet desc',4,'pid = '.$page['id']);
					if (!empty($child_pages)) {
						echo '<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$page['name'].' <b class="caret"></b></a>
							<ul class="dropdown-menu">';
							foreach($child_pages as $child_page) {
								echo '<li><a href="/'.$child_page['path'].'">'.$child_page['name'].'</a></li>';
							}
						echo '</ul>
						</li>';					
					} else {
					echo '<li '.@$cls.'>
							<a href="/'.$page['path'].'">'.$page['name'].'</a>
						</li>';   
					}				
				}
				?>	  
			</ul>	
		
		</div><!-- /.navbar-collapse -->
	</nav>
</div>
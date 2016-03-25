<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AverCMS | <?php echo @$title; ?></title>

    <link href="/css/admin/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<link href="/css/admin/plugins/iCheck/custom.css" rel="stylesheet">
	
	<link href="/css/admin/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
	<link href="/css/admin/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">
	
	<link href="/css/admin/plugins/toastr/toastr.min.css" rel="stylesheet">
	
    <script src="/js/library/jquery-2.1.1.js"></script>	
	<script src="/js/admin/functions.js"></script>
	<script src="/js/admin/jqgrid_function.js"></script>
		
	<!-- Theme style -->
	<?php foreach(UI::getCSS() as $css): ?>
		<link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
	<?php endforeach; ?>
	
    <link href="/css/admin/animate.css" rel="stylesheet">
    <link href="/css/admin/style.css" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
        <?php Render::view('adminpanel/menu', '', TRUE); ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
			<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
					<form role="search" class="navbar-form-custom" method="post" action="search_results.html">
						<div class="form-group">
							<input type="text" placeholder="Поиск..." class="form-control" name="top-search" id="top-search">
						</div>
					</form>
				</div>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
							<i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
						</a>
						<ul class="dropdown-menu dropdown-messages">
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-left">
										<img alt="image" class="img-circle" src="/img/admin/a7.jpg">
									</a>
									<div class="media-body">
										<small class="pull-right">46h ago</small>
										<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
										<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-left">
										<img alt="image" class="img-circle" src="/img/admin/a4.jpg">
									</a>
									<div class="media-body ">
										<small class="pull-right text-navy">5h ago</small>
										<strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
										<small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-left">
										<img alt="image" class="img-circle" src="/img/admin/profile.jpg">
									</a>
									<div class="media-body ">
										<small class="pull-right">23h ago</small>
										<strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
										<small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="text-center link-block">
									<a href="mailbox.html">
										<i class="fa fa-envelope"></i> <strong>Читать все</strong>
									</a>
								</div>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
							<i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
						</a>
						<ul class="dropdown-menu dropdown-alerts">
							<li>
								<a href="mailbox.html">
									<div>
										<i class="fa fa-envelope fa-fw"></i> You have 16 messages
										<span class="pull-right text-muted small">4 minutes ago</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="profile.html">
									<div>
										<i class="fa fa-twitter fa-fw"></i> 3 New Followers
										<span class="pull-right text-muted small">12 minutes ago</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="grid_options.html">
									<div>
										<i class="fa fa-upload fa-fw"></i> Server Rebooted
										<span class="pull-right text-muted small">4 minutes ago</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<div class="text-center link-block">
									<a href="notifications.html">
										<strong>See All Alerts</strong>
										<i class="fa fa-angle-right"></i>
									</a>
								</div>
							</li>
						</ul>
					</li>


					<li>
						<a href="/adminpanel/?logout">
							<i class="fa fa-sign-out"></i> Выход
						</a>
					</li>
				</ul>

			</nav>
        </div>

		<div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-lg-12">
				<h2><?php echo @$title; ?></h2>
				<ol class="breadcrumb">
					<li>
						<a href="/adminpanel">Главная</a>
					</li>
					<li class="active">
						<a href="<?php echo get_url_admin_form(2); ?>"><strong><?php echo @$title; ?></strong></a>
					</li>
				</ol>
			</div>
		</div>	

		<div class="wrapper wrapper-content animated fadeInRight">
		<div id="page-preloader"></div>
		<?php 
		if (isset($top_content)) echo $top_content; 		
		if (isset($content)) echo $content; 
		?>
		</div>
		
		<div class="footer" >
			<div class="pull-right">
				10GB of <strong>250GB</strong> Free.
			</div>
			<div>
				<strong>Copyright</strong> Example Company &copy; 2014-2015
			</div>
		</div>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/library/bootstrap.min.js"></script>
    <script src="/js/library/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/library/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/library/inspinia.js"></script>
    <script src="/js/library/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/js/library/plugins/jquery-ui/jquery-ui.min.js"></script>

	 <!-- jqGrid -->
	<script src="/js/library/plugins/jqGrid/i18n/grid.locale-ru.js"></script>
	<script src="/js/library/plugins/jqGrid/jquery.jqGrid.min.js"></script>
	
	 <!-- iCheck -->
	<script src="/js/library/plugins/iCheck/icheck.min.js"></script>
	
	
	<script src="/js/library/plugins/toastr/toastr.min.js"></script>

	<script src="/js/admin/admin.js"></script>
	
	<?php foreach(UI::getJS() as $js): ?>
		<script type="text/javascript" src="<?php echo $js; ?>" ></script>
	<?php endforeach; ?>
		
</body>
</html>

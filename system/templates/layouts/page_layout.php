<?php Pages::fetchContent(URL::getPath()); ?>
<!DOCTYPE html> 
<html lang="ru">
  <head>
    <meta name="generator" content="Aver CMS" />  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <title><?php if (isset($title)) { echo @$title; } else { echo Pages::getTitle(); } ?></title>
    <meta name="description" content="<?php  if (isset($description)) { echo @$description; } else { echo Pages::getDescription(); } ?>" />	
	<meta name="keywords" content="<?php if (isset($keywords)) { echo @$keywords; } else { echo Pages::getKeywords(); } ?>" />
    <meta name="robots" content="index, follow" />	
	
	<link rel="canonical" href="<?php echo get_url_canonical(); ?>">
	<link rel="stylesheet" type="text/css" href="/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="/css/bs/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/css/site.css" />
    <link rel="stylesheet" type="text/css" href="/css/navbar.css" />

	<?php foreach(UI::getCSS() as $css): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>" />
	<?php endforeach; ?>

	<script type="text/javascript" src="/js/library/jquery-2.1.1.js"></script>

	<script type="text/javascript" src="/js/site/plugins/autocolumnlist/jquery.autocolumnlist.js"></script>
	<script type="text/javascript" src="/js/site/plugins/autocolumnlist/autocolumnlist.init.js"></script>
	
	<link href="/css/admin/plugins/toastr/toastr.min.css" rel="stylesheet">
	<script src="/js/library/plugins/toastr/toastr.min.js"></script>
		
	<script type="text/javascript" src="/js/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript" src="/js/fancybox/init.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/fancybox/jquery.fancybox.css" />

	 <!-- iCheck -->
	<link href="/css/admin/plugins/iCheck/custom.css" rel="stylesheet">	 
	<script src="/js/library/plugins/iCheck/icheck.min.js"></script>	

	 <!-- chosen -->
	<link rel="stylesheet" type="text/css" href="/css/admin/plugins/chosen/chosen.css" />	
	<link rel="stylesheet" type="text/css" href="/css/admin/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="/css/chosen.css" />	
	<script src="/js/library/plugins/chosen/chosen.jquery.js"></script>	
	<script src="/js/library/plugins/chosen/init.js"></script>	

	<?php foreach(UI::getJS() as $js): ?>
		<script type="text/javascript" src="<?php echo $js; ?>" ></script>
	<?php endforeach; ?>
	
	<script type="text/javascript" src="/js/site/main.js"></script>
	<script type="text/javascript" src="/js/site/aver-comment.js"></script>
	<script type="text/javascript" src="/js/site/aver-messages.js"></script>

</head>

<body>

	<div id="wrap">
	
		<div class="back-top"><a href="">Наверх</a></div>
		
		<div class="container main">

			<div class="page-wrapper">
			
				<div class="header">
				
					<div class="row">
						<div class="col-sm-3">		
						
							<a class="logo" href="/">ПоварёнОК</a>
						
						</div>
						
						<div class="col-sm-5">

							<form  role="search" method="GET" action="/recepty/search/">

								<div class="input-group">
							
									<input type="text" name="q" value="<?php echo @$_GET['q']; ?>" class="form-control pull-left" placeholder="Название блюда">
									
									<span class="input-group-btn">
									
										<button type="submit" class="btn btn-default">Найти</button>
						
									</span>	
									
								</div>
						
							</form>	
		
						</div>
						
						<div class="col-sm-4 top__right-regist">
						
							<?php Render::view('registration/top_block', '', TRUE); ?>	
							
						</div>
					</div>

				</div>
				
				<?php Render::view('menu', '', TRUE); ?>
										
				<div class="middle row">	

					<?php 
						
						if (isset($left)) {		
							
							echo '<div class="col-md-3 col-xs-12" id="left-block">'.$left.'</div>';
							
							echo '<div class="col-md-9 col-xs-12">';
						
						} else {
							
							echo '<div class="col-md-12 col-xs-12">';
						
						}
						
						echo '<h1>';
						
						if (isset($h1)) { echo @$h1; } else { echo Pages::getName(); }
						
						echo '</h1>';

						if (isset($content)) {
									
							echo $content;
								
						} elseif (Pages::getContent()){
								  
							echo Pages::getContent();
								
						} else {
								  
							echo '&nbsp;';
								  
						} 
							
					?>
				
					</div>	
				
				</div>
			
			</div>
		
			<div class="footer">

				<div class="text-center">
					<div class="footer-copyright">
						&copy; 2015–<?php echo date('Y'); ?> <a href="http://povarenok.by/">Povarenok.by</a>	
					</div>
					<div class="footer-va__schetchiki">
						<div class="footer-va__body">
							<?php Render::view('schetchik', '', TRUE); ?>			
						</div>		
					</div>
				</div>
				
			</div>
				
		</div>

    </div>
	<script src="/js/library/bootstrap.js"></script>

</body>

</html>
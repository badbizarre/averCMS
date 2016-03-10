<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AverCMS | Вход</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="/css/admin/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/css/admin/login.css" rel="stylesheet" type="text/css" />
 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
		<div class="login-logo">
			<b>Aver</b>CMS
		</div><!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Войти, чтобы начать сеанс</p>
			<form method="post">
				<?php if (isset($error['message'])) echo '<div class="alert alert-danger">'.$error['message'].'</div>'; ?>
			  <div class="form-group has-feedback">
				<input type="login" class="form-control" name="login" placeholder="Логин"/>
				<span class="glyphicon glyphicon glyphicon-user form-control-feedback"></span>
			  </div>
			  <div class="form-group has-feedback">
				<input type="password" class="form-control" name="password" placeholder="Пароль"/>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			  <div class="row">
				<div class="col-xs-offset-8 col-xs-4">
				  <button type="submit" class="btn btn-primary btn-block btn-flat">Вход</button>
				</div><!-- /.col -->
			  </div>
			</form>

		</div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <script src="/js/library/jquery-2.1.1.js"></script>
    <script src="/js/library/bootstrap.min.js"></script>
  
  </body>
</html>
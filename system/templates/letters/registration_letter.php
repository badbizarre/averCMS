<!DOCTYPE html> 
<html lang="ru">
	<title>ПоварёнОК</title>
	<meta charset="utf-8">
</head>
<body>
<?php 
	$code  = mt_rand(1000000,9999999);
	$_SESSION['tmp']['code'] = $code;
	$id = $_SESSION['tmp']['user_id'];
?>	
<a href="/registration/active_user<?php echo '?id='.$id.'&code='.$code; ?>">Подтвердить активацию</a>
	
</br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>

</body>

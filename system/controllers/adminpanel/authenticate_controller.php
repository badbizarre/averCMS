<?php

class Authenticate_Controller {
	public function __construct() {
		
		if (isset($_GET['logout'])) {
			unset($_SESSION['isLoggedIn']);
			header('location: /');
			exit();
		}

		$error = Array();
		
		if (isset($_POST['login']) and isset($_POST['password'])) {
				
			if (!empty($_POST['login']) and !empty($_POST['password'])) {
			
				if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } 
				if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }

				if ($_POST['login'] == $login && $_POST['password'] == $password) {

					$login = trim(stripslashes(htmlspecialchars($login)));
					$password = trim(stripslashes(htmlspecialchars($password)));

					$password = md5($password);
					
					$user = Database::getRow(get_table('adminusers'),$login,'login');
				
					if (!empty($user)) {
							
						if ($user['active']==0) {

							$error['message'] = "Ваша учетная запись не активна";
							
						} else {				
							
							if (empty($user['password'])) {
								$error['message'] = 'Пользователь с введенным логином не существует';
							} else { 
								if ($user['password']==$password) { 
									$_SESSION['isLoggedIn'] = $user;
									header('location:' . $_SERVER['REQUEST_URI']);
									exit();
								} else {  //если пароли не сошлись
									$error['message'] = 'Не верный логин или пароль.';
								}
							}	
						
						}
							
					} else {
						$error['message'] = 'Данной учетной записи не существует';
					}

				} 
			} else {
				
				$error['message'] = 'Введите логин и пароль';

			}
			
		}

		if (!isset($_SESSION['isLoggedIn'])) {
			Render::layout('adminpanel/login', Array('error' => $error));
			exit();
		}
  
	}
	
}
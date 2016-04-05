<?php

class Registration_Controller {
	private $_table, $_content;

	public function __construct() {	
	
		$this->_table = get_table('users');
	
		$this->_content['left'] = Render::view('cabinet/razdel').Render::view('catalog/razdel');
  		
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view ('registration/list');
	
		Render::layout('page',  $this->_content);
	}

	public function addAction() {
  
		$data = array();
		$response = array();

		if (isset($_POST['email']) and !empty($_POST['email'])) {
				
			if (isset($_POST['password']) and !empty($_POST['password'])) {
				
				$user = Database::getRow($this->_table,$_POST['email'],'email');
				
				if (empty($user['id'])) {

					$data= array(
						'email' => $_POST['email'],	
						'password' => md5($_POST['password']),	
						'active' => 1,
						'date_create' => date('Y-m-d')					
					);

					Database::insert($this->_table,$data);
					
					$id = Database::getLastId($this->_table);
					
					cmsUser::sessionSet('user', array(
						'id' => $id
					));		
				
					$response['succes'] = TRUE;	
					$response['message'] = 'Регистрация прошла успешно.';
					$response['url'] = '/cabinet/settings';
		
				} else { 
					$response['succes'] = FALSE;
					$response['message'] = 'Такой Email уже существует';
					
				}
				
			} else {
				
				$response['succes'] = FALSE;
				$response['message'] = 'Заполните поле пароль!';					
	
			}
				
		} else {
			
			$response['succes'] = FALSE;
			$response['message'] = 'Заполните поле Email!';	
			
		}
		
		echo json_encode($response);

	}
	
	public function loginAction() {

		$response = array();	
		$response['succes'] = FALSE;
		
		if (isset($_POST['email']) and isset($_POST['password'])) {
   
			if ((!empty($_POST['email'])) and (!empty($_POST['password'])))  {

				$remember = isset($_POST['remember']) ? 1 : 0;
			
				if (cmsUser::login($_POST['email'], $_POST['password'], $remember)) {
				
					$response['succes'] = true;
					$response['url'] = $_SERVER['HTTP_REFERER'];					
					
				} else {
					
					$response['succes'] = false;
					$response['message'] = "Email или пароль не верные!";
					
				}

			
			} else {
				$response['message'] = "Вы ввели не всю информацию, заполните все поля!";
			}
			
		}
		
		echo json_encode($response);

	}

	
	public function active_userAction() {
  
		if (isset($_GET['code']) and isset($_GET['id'])) {
			
			$sess = $_SESSION['tmp'];
			
			if ($sess['user_id']==$_GET['id'] and $sess['code']==$_GET['code']) {
				
				Database::update($this->_table,array('active'=>1),'id='.$sess['user_id']);
				
				unset($_SESSION['tmp']);
				
				cmsUser::sessionSet('user', array(
					'id' => $sess['user_id']
				));
							
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /", TRUE, 302);

			}

		} else {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: /registration/", TRUE, 302);			
		}
  
	}	
	
	public function logoutAction() {
  
		cmsUser::logout();
		header('location: /');
  
	}
	
	public function loginvkAction() {
					
		// Пример использования класса:
		if (!empty($_GET['error'])) {
			// Пришёл ответ с ошибкой. Например, юзер отменил авторизацию.
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: /registration/", TRUE, 302);
			exit;
		} elseif (empty($_GET['code'])) {
			// Самый первый запрос
			OAuthVK::goToAuth();
		} else {
			// Пришёл ответ без ошибок после запроса авторизации
			if (!OAuthVK::getToken($_GET['code'])) {
				die('Error - no token by code');
			}

			/*
			 * На данном этапе можно проверить зарегистрирован ли у вас ВК-юзер с id = OAuthVK::$userId
			 * Если да, то можно просто авторизовать его и не запрашивать его данные.
			 */

			$user = OAuthVK::getUser();
			print_r($user);
			/*
			 * Вот и всё - мы узнали основные данные авторизованного юзера.
			 * $user в этом примере состоит из трёх полей: uid, first_name, last_name.
			 * Делайте с ними что угодно - регистрируйте, авторизуйте, ругайте...
			 */
		}
		
	}

}
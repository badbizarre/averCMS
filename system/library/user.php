<?php

class cmsUser {

    public $id;

	private static $instance, $_table = 'np_users';

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	public function get($key){
		return self::getInstance()->$key;
	}
	
    public static function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }

    public function __construct(){

        if (self::isSessionSet('user:id')){

            // уже авторизован
            $this->id  = self::sessionGet('user:id');

        } elseif (self::getCookie('auth')) {

            // пробуем авторизовать по кукису
            $this->id  = self::autoLogin(self::getCookie('auth'));

        } else {

            // не авторизован
            $this->id = 0;

        }

        //
        // если авторизован, заполняем объект данными из базы
        //
        if ($this->id){

			$user = Database::getRow(self::$_table,$this->id);

			if (!$user or !$user['active']){
				self::logout();
				return;
			}

            // заполняем объект данными из базы
            foreach($user as $field=>$value){
                $this->{$field} = $value;
            }

            // создаем online-сессию
            //self::createSession($this->id);

        }

    }	

//============================================================================//
//============================================================================//

    /**
     * Авторизует пользователя по кукису
     * @param str $auth_token
     */
    public static function autoLogin($auth_token){

        if (!preg_match("/^([a-zA-Z0-9]{32})$/i", $auth_token)){ return false; }

        $user = Database::getRow(self::$_table,$auth_token,'auth_token');


        if (!$user){ return false; }

        self::sessionSet('user', array(
            'id' => $user['id']
        ));

        return $user['id'];

    }
    /**
     * Авторизует пользователя
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function login($email, $password, $remember=false) {

        if (!preg_match("/^([a-zA-Z0-9\._-]+)@([a-zA-Z0-9\._-]+)\.([a-zA-Z]{2,4})$/i", $email)){
            return false;
        }

        $user = Database::getRow(self::$_table,$email,'email');

        if (empty($user['id'])) { return false; } 

        if ($user['password'] != md5($password)) { return false; } 
		
        self::sessionSet('user', array(
            'id' => $user['id']
        ));
		
        $auth_token = string_random(32, $email);
			
        if ($remember) self::setCookie('auth', $auth_token, 8640000); 

        Database::update(self::$_table, array('auth_token'=>$auth_token), 'id='.$user['id']);

        return $user['id'];

    }

    /**
     * Выход пользователя
     *
     */
    public static function logout() {

        self::sessionUnset('user');
        self::unsetCookie('auth');

        return true;

    }

//============================================================================//
//============================================================================//

    public static function createSession($user_id){

        $model = new cmsModel();

        $insert_data = array(
            'session_id' => session_id(),
            'user_id' => $user_id
        );

        $update_data = array(
            'date_created' => null
        );

        $model->insertOrUpdate('sessions_online', $insert_data, $update_data);

        if ($user_id){

            $model->filterEqual('id', $user_id)->
                    updateFiltered('{users}', array('is_online' => 1));

        }

    }
	

//============================================================================//
//============================================================================//

    public static function sessionSet($key, $value){

        if (!strstr($key, ':')){
            $_SESSION[$key] = $value;
        } else {
            list($key, $subkey) = explode(':', $key);
            $_SESSION[$key][$subkey] = $value;
        }

    }

    public static function sessionPush($key, $value){
        $_SESSION[$key][] = $value;
    }

    public static function sessionGet($key, $is_clean=false){

        if (!self::isSessionSet($key)){ return false; }

        if (!strstr($key, ':')){
            $value = $_SESSION[$key];
        } else {
            list($key, $subkey) = explode(':', $key);
            $value = $_SESSION[$key][$subkey];
        }

        if ($is_clean) { self::sessionUnset($key); }

		Database::update(self::$_table, array('last_visit'=>date('Y-m-d H:i:s')), 'id='.$value);
		
        return $value;

    }

    public static function isSessionSet($key){
        if (!strstr($key, ':')){
            return isset($_SESSION[$key]);
        } else {
            list($key, $subkey) = explode(':', $key);
            return isset($_SESSION[$key][$subkey]);
        }
    }

    public static function sessionUnset($key){
        if (!strstr($key, ':')){
            unset($_SESSION[$key]);
        } else {
            list($key, $subkey) = explode(':', $key);
            unset($_SESSION[$key][$subkey]);
        }
    }

    /**
     * Устанавливает куки
     *
     * @param str $name Имя кукиса
     * @param str $value Значение
     * @param int $time Время жизни, в секундах
     * @param str $path Путь на сервере
     * @param str $domain Разрешенный домен
     *
     * */
    public static function setCookie($key, $value, $time=3600, $path='/', $http_only=true){
        setcookie('acms['.$key.']', $value, time()+$time, $path, null, false, $http_only);
        return;
    }


    public static function setCookiePublic($key, $value, $time=3600, $path='/'){
        return self::setCookie($key, $value, $time, $path, false);
    }

    public static function unsetCookie($key){
        setcookie('acms['.$key.']', '', time()-3600, '/');
        return;
    }

    /**
     * Проверяет наличие кукиса и возвращает его значение
     *
     * @param str $name Имя кукиса
     * @return str или false
     */
    public static function getCookie($key){
        if (isset($_COOKIE['acms'][$key])){
            return $_COOKIE['acms'][$key];
        } else {
            return false;
        }
    }

    public static function hasCookie($key){
        return isset($_COOKIE['acms'][$key]);
    }

    /**
     * Получаем данные пользователя из БД
     *
     */	
	
    public static function getUser(){
        return Database::getRow(self::$_table,self::sessionGet('user:id'));
    }
	
	
}



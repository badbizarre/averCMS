<?php

class Utils {
    public static function redirect($uri = '') {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$uri, TRUE, 302);
        exit;
    }
}

class OAuthVK {

	const APP_ID = 5099014; 					//ID приложения
    const APP_SECRET = 'B6fv0AclLmMmLya1Zm1I'; 	//Защищенный ключ
    const URL_CALLBACK = 'http://127.0.0.1/registration/loginvk'; 	//URL, на который произойдет перенаправление после авторизации  
    const URL_ACCESS_TOKEN = 'https://oauth.vk.com/access_token';
    const URL_AUTHORIZE = 'https://oauth.vk.com/authorize';
    const URL_GET_PROFILES = 'https://api.vk.com/method/getProfiles';

    private static $token;
    public static $userId;
    public static $userData;

    private static function printError($error) {
        echo '#' . $error->error_code . ' - ' . $error->error_msg;
    }

    /**
     * @url https://vk.com/dev/auth_sites
     */
    public static function goToAuth()
    {
        Utils::redirect(self::URL_AUTHORIZE .
            '?client_id=' . self::APP_ID .
            '&scope=offline,email' .
            '&redirect_uri=' . urlencode(self::URL_CALLBACK) .
            '&response_type=code');
    }

    public static function getToken($code) {
        $url = self::URL_ACCESS_TOKEN .
            '?client_id=' . self::APP_ID .
            '&client_secret=' . self::APP_SECRET .
            '&code=' . $_GET['code'] .
            '&redirect_uri=' . urlencode(self::URL_CALLBACK);

        if (!($res = @file_get_contents($url))) {
            return false;
        }

        $res = json_decode($res);
        if (empty($res->access_token) || empty($res->user_id)) {
            return false;
        }

        self::$token = $res->access_token;
        self::$userId = $res->user_id;

        return true;
    }

    /**
     * Если данных недостаточно, то посмотрите что можно ещё запросить по этой ссылке
     * @url https://vk.com/pages.php?o=-1&p=getProfiles
     */
    public static function getUser() {

        if (!self::$userId) {
            return false;
        }

        $url = self::URL_GET_PROFILES.
            '?uid=' . self::$userId .
            '&access_token=' . self::$token;

        if (!($res = @file_get_contents($url))) {
            return false;
        }

        $user = json_decode($res);

        if (!empty($user->error)) {
            self::printError($user->error);
            return false;
        }

        if (empty($user->response[0])) {
            return false;
        }

        $user = $user->response[0];
        if (empty($user->uid) || empty($user->first_name) || empty($user->last_name)) {
            return false;
        }

        return self::$userData = $user;
    }
}



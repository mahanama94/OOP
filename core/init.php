<?php
session_start();

/**
 * 
 */
$GLOBALS['config'] = array(
		'mysql' => array(
				'host' => '127.0.0.1',
				'username' => 'root',
				'password' => '',
				'db' => 'oop'
		),
		'remember' => array(
				'cookieName' => 'hash',
				'cookieExpiry' => 604800
		),
		'session' => array(
				'sessionName' => 'user',
				'tokenName' => 'token'
		)
		
);


/**
 * Autoloading clases when required
 * 
 */
spl_autoload_register(function($class){
	require_once '../classes/'.$class.'.php';
});

require_once '../functions/sanitize.php';

/**
 * Checks for the Cookies and creates a user accordingly
 */
if(Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))){
	$hash = Cookie::get(Config::get("remember/cookie_name"));
	$hashCheck = DB::getInstance()->get("user_session", array(" hash = $hash "));
	
	if($hashCheck->count()){
		 $user = new User($hashCheck->first->id);
		 $user->login;
	}
}

?>
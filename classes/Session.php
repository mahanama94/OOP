<?php

class Session{
	
	/**
	 * checks the existance of the session variable corresponding to the
	 * argument passed
	 * 
	 * @param name of the variable $name
	 */
	public static function exists($name){
		return isset($_SESSION[$name]) ? true: false;
	}
	
	/**
	 * sets the session variable corresponding to the argument passed to the
	 * value specified
	 * 
	 * @param name of sesssion variable $name
	 * @param value of session variable $value
	 */
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
	
	/**
	 * returns value of the session variable corresponding to the value passed
	 * 
	 * @param name of session variable $name
	 */
	public static function get($name){
		return $_SESSION[$name];
	}
	
	/**
	 * unsets the session variable corresponding to the value passed
	 * 
	 * @param name of the session variable $name
	 */
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
	
	/**
	 * Sets a flash message for a session
	 * Once opened, deletes the session varibale deletes itself
	 * 
	 * @param name of session variable $name
	 * @param value of the session variable $string
	 * @return value of the vaiable unknown| "" if not set string
	 */
	public static function flash($name, $string =""){
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		}
		else{
			self::put($name, $string);
		}
		return "";
	}
	
}

?>
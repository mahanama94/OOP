<?php

class Cookie{
	
	/**
	 *	Returns the esistance of cookie
	 * @param key  $name
	 */
	public static function exists($name){
		return isset($_COOKIE[$name]);
	}
	
	/**
	 * return the value corresponding to the name in the cookie
	 * 
	 * @param name of the key $name 
	 * @return corresponding value in the cookie
	 */
	public static function get($name){
		return $_COOKIE[$name];
	}

	/**
	 * creates a cookie
	 * @param name $name
	 * @param value $value
	 * @param expiry time from the current time $expiry
	 * @return true for success else false boolean
	 */
	public static function put($name, $value, $expiry){
		if(setcookie($name, $value, time() + $expiry)){
			return true;
		}
		return false;
	}
	
	/**
	 * Deletes the cookies
	 */
	public static function delete(){
		self::put($name, "", time() -1);
	}
}

?>
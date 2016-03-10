<?php
class Hash{
	
	/**
	 *returns the hash for the string passed with salt passed
	 * 
	 * @param string for hashing $string
	 * @param string for dalt $salt
	 * @return hased string string
	 */
	public static function make($string, $salt = ""){
		 return hash("sha256", $string, $salt);
	}
	
	/**
	 * returns a salt for a given length
	 * 
	 * @param length of the salt $length
	 */
	public static function salt($length){
		return mcrypt_create_iv($length);
	}
	
	public static function unique(){
		return self::make(uniqid());
	}
}
?>
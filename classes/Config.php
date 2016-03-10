<?php
/**
 * Basic retrieving data on the basic configurations for the application
 * 
 * @author Rajith Bhanuka
 *
 */

class Config{
	
	/**
	 * returns data specified for the configuration of the application from the 
	 * basic configuration data
	 * 
	 * ex
	 * 		get('mysql/host')
	 * 
	 * @param path of configuration data $path
	 * @return value of config data |boolean false if not avaialable
	 */
	public static function get($path = null){
		if($path){
			$config = $GLOBALS['config'];
			$path = explode("/", $path);
			
			foreach($path as $bit){
				if(isset($config[$bit])){
					$config = $config[$bit];
				}
			}
			
			return $config;
		}
		
		return false;
	}
}

?>
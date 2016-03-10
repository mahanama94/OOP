<?php

class Input{
	
	/**
	 * checks the existance get or post data
	 * default post
	 * 
	 * @param method get or post $type
	 * @return true if available else false boolean
	 */
	public static function exists( $type = "post"){
		
		switch($type){
			case "post":
				return (!empty($_POST))? true : false;
			break;
			
			case "get":
				return (!empty($_GET))? true : false;
			break;
			
			default:
				return false;
			break;
		}
	}
	
	/**
	 * returns the value corresponding to the paramter passed from the 
	 * POST/ GET superglobals array
	 * Priority set for POST
	 * 
	 * @param value of the key in superglobal $item
	 * @return value if found else "" string
	 */
	public static function get($item){
		if(isset($_POST[$item])){
			return $_POST[$item];
		}
		else if (isset($_GET[$item])){
			return $_GET[$item];
		}
		else{
			return "";
		}
	}
	
	
}
?>
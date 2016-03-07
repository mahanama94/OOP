<?php
require_once '../core/init.php';

//echo Config::get('mysql/host');
//$user = DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array('alex'));
$user = DB::getInstance()->get("users", array(" username = 'alex' "));

if(!$user->count()){
	echo "No user";
}
else{
	echo "User";
}
?>
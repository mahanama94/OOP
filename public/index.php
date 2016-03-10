<?php
require_once '../core/init.php';

//echo Config::get('mysql/host');
//$user = DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array('alex'));
/*$user = DB::getInstance()->get("users", array(" username = 'alex' "));

if(!$user->count()){
	echo "No user";
}
else{
	echo "User";
}*/

//$user = DB::getInstance()->insert('users', array('username' => 'Billy','password' => 'password2','salt' => 'salt2'));

//$user = DB::getInstance()->query($sql)
//echo var_dump($user);

$users = DB::getInstance()->update('users', " username = 'alex' ", array('password' => 'bla'));

echo var_dump($users);
/*if(!$users->count()){
	echo "No user";
}
else{
	foreach($users->result() as $user){
		echo $user->username, ',<br>';	
	}
}*/

?>
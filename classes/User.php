<?php

class User{

	private $_db, $_data, $_sessionName, $_cookieName, $_isLoggedIn;
	
	/**
	 * 
	 * @param unknown $user
	 */
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				if($this->find($user)){
					$this->_isLoggedIn = true;
				}
				else{
					//process logout
				}
			}
		}
		else{
			$this->find($user);
		}
	}
	
	/**
	 * 
	 * @param array $fields
	 */
	public function create($fields = array()){
		if($this->_db->insert('users', $fields)->error()){
			throw Exception("There was an error in creating user");
		}
	}
	
	/**
	 * 
	 * @param unknown $user
	 * @return boolean
	 */
	public function find($user = null){
		
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', ' $field = $user ');
		
			if($data->count){
				$this->_data = $data->first();
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param unknown $username
	 * @param unknown $password
	 * @param unknown $remember
	 */
	public function login($username= null, $password = null, $remember= false){
		
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}
		
		else{
			$user = $this->find($username);
			if($user){
				if($this->data()->password == Hash::make($password, $this->data()->salt)){
					//login successfull
					Session::put($this->_sessionName, $this->_data()->id);
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_session', Array(" userId = $this->data()->id "));
						
						if(!$hashCheck->count()){
							$this->_db->insert(' user_session ', Array("userId"=> $this->_data->id, " hash "=> $hash));
						}
						
						else{
							$hash = $hashCheck->first()->hash;
						}
						
						Cookie::put($this->_cookieName, $hash,Config::get("remember/cookie_expiry"));
					}
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * 
	 * @param array $fields
	 * @param unknown $id
	 * @throws Exception
	 */
	public function update($fields = array(), $id = null){
		
		if(!$id){
			$id = $this->data()->id;
		}
		
		if(!$this->_db->update("users", " $fields ")){
			throw new Exception("there was a problem updating ");
		}
	}
	
	
	/**
	 * Deletes the session
	 */
	public function logout(){
		
		$this->_db->delete("user_session" , " id = $this->data->id ");
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}
	
	/**
	 * returns the data in the user as an associative array
	 */
	public function data(){
		return $this->_data;
	}
	
	/**
	 * returns logged in status of the user
	 */
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
	
	/**
	 * reurns wether user exists or not
	 */
	public function exists(){
		return !empty($this->_data) ? true: false;
	}
	
	/**
	 * 
	 * @param unknown $key
	 */
	public function hasPermission($key){
		$group = $this->_db->get(" groups ", $this->data()->group);
		
		if($group->count()){
			
			$permissions = json_decode($group->first()->permissions, true);
			
			if($permissions[$key]== true){
				return true;
			}
		}
		return false;
	}
}
?>
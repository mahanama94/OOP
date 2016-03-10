<?php

class User{

	private $_db, $_data, $_sessionName;
	
	/**
	 * 
	 * @param unknown $user
	 */
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
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
	 * @return boolean
	 */
	public function login($username= null, $password = null ){
		$user = $this->find($username);
		
		if($user){
			if($this->data()->password == Hash::make($password, $this->data()->salt)){
				//login successfull
				Session::put($this->_sessionName, $this->_data()->id);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 
	 */
	public function data(){
		return $this->_data;
	}
}
?>
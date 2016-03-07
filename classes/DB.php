<?php

class DB{
	
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false , 
			$_result, 
			$_count =0;
	
	/**
	 * Constructor - Singleton
	 */
	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:dbname='.Config::get('mysql/db').';host='.Config::get('mysql/host'),Config::get('mysql/username'), Config::get('mysql/password'));
		}
		catch(PDOException $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * returns the DB instance if available
	 * else invokes the constructor to create an instance and returns
	 *  
	 */
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;	
	}
	
	
	/**
	 *
	 */
	public function error(){
		return $this->_error;
	}
	
	/**
	 * 
	 */
	public function count(){
		return $this->_count;
	}
	
	/**
	 * 
	 * @param unknown $sql
	 * @param array $params
	 */
	public function query($sql, $params = array()){
		$this->_error = false;
		
		if($this->_query = $this->_pdo->prepare($sql)){
			
			$x = 1;
			if(count($params)){	
				foreach($params as $param){
					
					$this->_query->bindValue($x, $param);
					$x++;
					
				}
			}
			
			
			if($this->_query->execute()){
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();				
			}
			else{
				
				$this->_error = true;
				$this->_result = null;
				$this->_count = 0;
			}
		}
		
		return $this;
	}
	
	/**
	 * performs the action specified passed as parameters and returns A DB object corresponding 
	 * to the action specified. 
	 * 
	 * Multiple conditions will be connected through an and connection
	 * 
	 * @param Action to perform 				$action
	 * @param Corresponding table in database 	$table
	 * @param Conditions for action as an Array	$conditions
	 */
	public function action($action, $table, $conditions = array()){
		$sql = "$action FROM $table ";
		$counter = 0;
		foreach($conditions as $condition){
			if($counter!=0){
				$sql .= " AND $condition ";
			}
			else{
				$sql .= " WHERE $condition ";
			}
		}
		if(!$this->query($sql)->error()){
				return $this;
		}
		return $this;
	}
	
	/**
	 * Retrieves data from the database corresponding to the conditions provided as the parameters
	 * 
	 * @param Corresponding table	$table
	 * @param conditions for getting as an array $conditions
	 * @return returns a DB object correspoding to the request DB
	 */
	public function get($table, $conditions = array()){
		return $this->action("SELECT * ", $table,$conditions );
	}
	
	/**
	 * Deletes data from the database corresponding to the conditions provided as the parameters
	 * 
	 * @param Corresponding table	$table
	 * @param conditions for deleting as an array $conditions
	 * @return returns a DB object correspoding to the request DB
	 */
	public function delete($table, $where = array()){
		return $this->action("DELETE ", $table,$conditions );
	}
}
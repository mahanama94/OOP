<?php

/**
 * Database wrapper class 
 * provides the functionality of querying from the database through a PDO
 * Provides database connecitivity to the database configured in Config.php
 * using the credentials provided 
 * 
 * Last update on 08/03/2015
 * 
 * @author Rajith Bhanuka
 *
 */
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
	 * returns the status of error correspoding to the query
	 */
	public function error(){
		return $this->_error;
	}
	
	/**
	 * returns the count of the query
	 */
	public function count(){
		return $this->_count;
	}
	
	/**
	 * Returns the result of the query perdormed
	 */
	public function result(){
		return $this->_result;
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
			
			//echo var_dump($this->_query);
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
	
	/**
	 * Inserts data in to the database corresponding to the table provided as parameters
	 * 
	 * ex
	 * 		insert('users', array('username' => 'Dale','password' => 'password','salt' => 'salt'))
	 * 
	 * @param  corresponding table $table
	 * @param values for the fields as an associative array $fields
	 * @return true on success else false boolean
	 */
	public function insert($table, $fields = array()){
		if(count($fields)){
			$keys = array_keys( $fields);
			$values = null;
			$x = 1;
			
			foreach($fields as $filed){
				$values .= " ? ";
				if($x< count($fields)){
					$values .=", ";
				}
				$x++;
			}
			
			$sql = "INSERT INTO $table ( ".implode(" , ", $keys) .") VALUES ({$values})";
			
			//echo $sql;
			//echo var_dump($fields);
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
		}
		return false; 
	}
	
	/**
	 * updates data in the table in the database provided as parameters
	 * 
	 * ex 
	 * 		update('users', " username = 'alex' ", array('password' => 'bla'));
	 * 
	 * @param table name $table
	 * @param condition(s) $condition
	 * @param values for the fields as an associative array $fields
	 * @return true on success else false boolean
	 */
	public function update($table, $condition = "", $fields = array()){
		
		$set = "";
		$x = 1;
		foreach($fields as $name=>$value){
			$set .= " $name = ? ";
			if($x < count($fields)){
				$set .= " , ";
			}
			$x++;
		}
		
		$sql = "UPDATE $table SET $set ";
		
		if(!$condition == ""){
			$sql .=" WHERE $condition";
		}
		
		echo $sql;
		
		if(!$this->query($sql,$fields)->error()){
			return true;
		}
		return false;
		
		
	}
}
?>
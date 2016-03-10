<?php

class Validate{
	
	private $_passed = false,
			$_errors = array(),
			$_db = null;
		
	
	public function __construct(){
		$this->_db = DB::getInstance();	
	}
			
	public function check($source, $constraints = array()){
		foreach ($constraints as $item => $rules){
			foreach ($rules as $rule => $value){
				
				$value = trim($source[$item]);
				
				if($rule == "required" && empty($value)){
					$this->addError($item, " is required required ");
				}
				else if (!empty($value)){		
					switch($rule){
						case 'min':
							if(strlen($value)< $value){
								$this->addError($item, " must be minimum of $value characters");
							}
						break;
						case 'max':
							if(strlen($value)> $value){
								$this->addError($item, " must be maximum of $value characters");
							}
						break;
						case 'matches':
							if($value != $source[$value]){
								$this->addError($item, " must match $value ");
							}
						break;
						case 'unique':
							$result = $this->_db->get($value, array(" $item = $value "));
							if($result->count()){
								$this->addError($item, " already exists ");
							}
						break;
					}
				}
			}
		}
		
	
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		
		return $this;
	}
	
	private function addError($item, $error){
		$this->_errors[$item] = $error;
	}
	
	public function errors(){
		return $this->_errors;
	}
	
	public function passed() {
		return $this->_passed;
	}
}

?>
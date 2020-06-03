<?php

abstract class Manager {
	
	protected $db;
	
	public function __construct($db){
		$this->setDb($db);
	}
	
	public function setDb($db) {
		$this->db = $db;
	}
	
	abstract public function count();
	abstract public function get($info);

}

?>
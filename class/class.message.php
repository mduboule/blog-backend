<?php

class Message {

	protected $name,
			  $email,
			  $content,
			  $dateCreated,
			  $id,
			  $ip;
			  
	public function __construct (array $donnees) {
		$this->hydrate($donnees);
	}
	
	// Fonction qui permet de remplir les attributs d'une instanciation à partir d'un
	// tableau array()
	public function hydrate(array $donnees) {
		foreach ($donnees as $key => $value) {
			$method = 'set'.ucfirst($key); 
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
			else echo 'Méthode non existante : ' . $method;
		}
	}	
	
	///////////////////////
	///SETTERS & GETTERS///
	///////////////////////
	
	public function getName() {return $this->name;}
	public function getEmail() {return $this->email;}
	public function getContent() {return $this->content;}
	public function getDateCreated() {return $this->dateCreated;}
	public function getId() {return $this->id;}
	public function getIp() {return $this->ip;}


	public function setName($name) {
		$this->name = (is_string($name)) ? $name : null;
	}
	
	public function setEmail($email) {
		$this->email = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : null;
	}

	public function setContent($content) {
		$this->content = (is_string($content)) ? $content : null;
	}
	
	public function setDateCreated($date) {
		$this->dateCreated = $date;
	}

	public function setId($id) {
		$id = (int) $id;
		if ($id > 0) {
			$this->id = $id;
        }
    }
    
    public function setIp($ip) {
		$this->ip = (is_string($ip)) ? $ip : null;
    }
}

?>
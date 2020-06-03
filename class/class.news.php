<?php

class News {

	protected $title,
			  $content,
			  $status,
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
	
	public function getShortTitle($len) {
		if (strlen($this->title) > $len) {			
  	 		$str = substr($this->title,0,$len) . "..." ;
			return $str;		
		}
		return $this->title;
	}
	
	public function getShortContent($len) {
		if (strlen($this->content) > $len) {
  	 		$str = substr($this->content,0,$len) . "..." ;
  	 		return $str;
		}
		return $this->content;
	}
	
	public function getShortContent2($len) {

		$str = $this->content;
			
		// Si on trouve un double crochet ouvrant et que celui ci arrive avant la limite fixée
		// par $len
		if (strpos($str, '[[') !== false && strpos($str, '[[') < $len) {
		
				$newLen = $len;
				$end = 0;
				$i = 0; // variable compteur de 'vrais' mots
	
			do {		
				
				$excess = 0;
				
				// On enregistre la position du début en partant des derniers crochets
				// fermés (ou de 0)
				$start = strpos($str, '[[', $end);
				
				// Comptabilise l'ensemble des caractères non contenus dans les crochets
				$i += $start;
				
				// On compte le nombre de caractères que le texte réel du lien contient 
				$linkTextLen = strpos($str, ']]', $end) - strpos($str, ' ', $start) - 1;
				
				// On récupère les mots du liens
				$linkText = substr($str, strpos($str, ' ', $start) + 1, $linkTextLen);
				
				// Calcule la quantité de caractères en trop (si on compte tous les caractères
				// des mots du lien)
				$excess = ($i + $linkTextLen) - $newLen;
							
				// Si les mots du lien font dépasser la limite
				if ($excess > 0) {
					// Détermine la position des mots du lien
					$startLinkText = strpos($str, $linkText, $start);
					// Raccourci les mots du lien
					$shortLinkText = substr($linkText,0, $linkTextLen - $excess);
					// Remplace l'ensemble des mots du lien par la version abrégée
					$str = substr_replace($str, $shortLinkText, $startLinkText) . ']]';
				}
				
				// On enregistre la position de fin
				$end = strpos($str, ']]', $end) + 1;
				
				// Déduit les caractères liés au liens du total de caractère de la chaine
				// en sachant que tous les caractères rajouté seront réduit lors de l'utilisation
				// de la fonction addLink()	
				$newLen += $end - $start;
				
			// On poursuit tant que :
			//	- $i n'a pas dépassé la limite fixée par $newLen
			//	- on est pas arrivé à la fin de $str
			//	- qu'il existe encore des liens à examiner, peut-être raccourcir
			} while ($i < $newLen && $i < strlen($str) && strpos($str, '[[', $end) !== false);
			
			return ($excess > 0) ? substr($str,0,$newLen) . "..." : $str;
		}
		else {
			return (strlen($str) > $len) ? substr($str,0,$len) . "..." : $str;
		}
	}		
	
	///////////////////////
	///SETTERS & GETTERS///
	///////////////////////
	
	public function getTitle() {return $this->title;}
	public function getContent() {return $this->content;}
	public function getStatus() {return $this->status;}
	public function getDateCreated() {return $this->dateCreated;}
	public function getId() {return $this->id;}
	public function getIp() {return $this->ip;}


	public function setTitle($title) {
		$this->title = (is_string($title)) ? $title : null;
	}
	
	public function setContent($content) {
		$this->content = (is_string($content)) ? $content : null;
	}
	
	public function setStatus($status) {
		if ($status == 'on' OR $status == 'off') {
			$this->status = $status;
		}
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
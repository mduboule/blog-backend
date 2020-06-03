<?php

/*
 * Class Concert, détermine un concert donné par une date jour-mois-année, un nom 
 * de groupe, des musiciens, un lieu, un id (automatique), l'heure à laquelle est 
 * enregistré le concert (automatique) et l'adresse IP (automatique) de l'ordinateur 
 * qui enregistre le concert.
 *
 * Cette class est gérée et connectée à SQL à travers la class ConcertsManager.
 * 
 * © Marius Duboule, Avril 2012
 * 
 * Updates :
 * 20 october 2012 : creat getUSTime(), modif getHours()
 * 22 juin 2013 : nouvel attribut status à la classe concert
 */

class Concert {
	
	protected $day, 
			  $month, 
			  $year,
			  $time,
			  $band, 
			  $musicians, 
			  $place, 
			  $price,
			  $website,
			  $details,
			  $status,
			  $id,
			  $timeCreated, 
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
		}
	}
	
	// Renvoi false si l'attribut facultatif musicians n'a pas été rempli
	public function sansMusiciens() {
		return isset($this->musicians) ? false : true;
	}
	
	public function getMonthEN() {
		switch($this->month) {
			case 1: return 'January'; break;
			case 2: return 'February'; break;
			case 3: return 'March'; break;
			case 4: return 'April'; break;
			case 5: return 'May'; break;
			case 6: return 'June'; break;
			case 7: return 'July'; break;
			case 8: return 'August'; break;
			case 9: return 'September'; break;
			case 10: return 'October'; break;
			case 11: return 'November'; break;
			case 12: return 'December'; break;
			default: return 'Undefined';
		}
	}
	
	public function getMonthFR() {
		switch($this->month) {
			case 1: return 'janvier'; break;
			case 2: return 'février'; break;
			case 3: return 'mars'; break;
			case 4: return 'avril'; break;
			case 5: return 'mai'; break;
			case 6: return 'juin'; break;
			case 7: return 'juillet'; break;
			case 8: return 'août'; break;
			case 9: return 'septembre'; break;
			case 10: return 'octobre'; break;
			case 11: return 'novembre'; break;
			case 12: return 'décembre'; break;
			default: return 'non défini';
		}
	}
	
	public function getShortBand($len) {
		if (strlen($this->band) > $len) {			
  	 		$str = substr($this->band,0,$len) . "…" ;
			return $str;		
		}
		return $this->band;
	}
	
	public function getShortPlace($len) {
		if (strlen($this->place) > $len) {			
  	 		$str = substr($this->place,0,$len) . "…" ;
			return $str;		
		}
		return $this->place;
	}
	
	public function getShortMonth() {
		switch($this->month) {
			case 1: return 'jan'; break;
			case 2: return 'feb'; break;
			case 3: return 'mar'; break;
			case 4: return 'apr'; break;
			case 5: return 'may'; break;
			case 6: return 'jun'; break;
			case 7: return 'jul'; break;
			case 8: return 'aug'; break;
			case 9: return 'sep'; break;
			case 10: return 'oct'; break;
			case 11: return 'nov'; break;
			case 12: return 'dec'; break;
		}
	}
	
	// Renvoie la date en format : 03-24-1992
	public function getFullDate() {
		$day = ($this->day < 10) ? '0' . $this->day : $this->day;
		$month = ($this->month < 10) ? '0' . $this->month : $this->month;
		
		return $month . '-' . $day . '-' . $this->year;		
	}
	
	// Renvoie la date en format : 03 dec 2001
	public function getFullDate2() {
		$day = ($this->day < 10) ? '0' . $this->day : $this->day;
		
		return $day . ' ' . $this->getShortMonth() . ' ' . $this->year;
	}
	
	public function getSeconds() {
		$time = explode(':', $this->time);
		return $time[2];
	}
	
	public function getMinutes() {
		$time = explode(':', $this->time);
		return $time[1];
	}
	
	public function getHours() {
		$time = explode(':', $this->time);
		return (int)$time[0];
	}
	
	/* Return 15h instead of 13:00:00 or or 8h45
	 * instead of 8:45:00
	 */
	public function getEuropeanTime() {
		$minutes = $this->getMinutes() == '00' ? '' : $this->getMinutes(); 
		return $this->getHours() . 'h' . $minutes;
	}
	
	/* Return 3pm instead of 13:00:00 or or 8:45am
	 * instead of 8:45:00
	 */
	public function getUSTime() {
		$minutes = ($this->getMinutes() != "00") ? $this->getMinutes() : "";
		if ($this->getHours() > 12) {
			if ($minutes == "") {
				$time = ($this->getHours() - 12) . "pm";
			}
			else {
				$time = ($this->getHours() - 12) . ":" . $this->getMinutes() . "pm";
			}
		}
		else if ($this->getHours() < 12) {
			if ($minutes == "") {
				$time = $this->getHours() . "am";
			}
			else {
				$time = $this->getHours() . ":" . $this->getMinutes() . "am";
			}
		}
		return $time;
	}
	
	///////////////////////
	///SETTERS & GETTERS///
	///////////////////////
	
	public function getDay() {return $this->day;}
	public function getMonth() {return $this->month;}
	public function getYear() {return $this->year;}
	public function getTime() {return $this->time;}
	public function getBand() {return $this->band;}
	public function getMusicians() {return $this->musicians;}
	public function getPlace() {return $this->place;}
	public function getPrice() {return $this->price;}
	public function getWebsite() {return $this->website;}
	public function getDetails() {return $this->details;}
	public function getStatus() {return $this->status;}
	public function getId() {return $this->id;}
	public function getTimeCreated() {return $this->timeCreated;}
	public function getIp() {return $this->ip;}

	public function setDay($day) {
		$day = (int) $day;
		$this->day = ($day > 0 && $day < 32) ? $day : null;
	}
	
	public function setMonth($month) {
		$month = (int) $month;	
		$this->month = ($month > 0 && $month < 13) ? $month : null;
	}
	
	public function setYear($year) {
		$year = (int) $year;
		$this->year = ($year > 0) ? $year : null;
	}
	
	/*public function setTime($hours, $minutes) {
		if ($hours >= 0 and $hours <= 24 and $minutes >= 0 and $minutes <= 60) {
			$time = $hours . ':' . $minutes . ':00';
			$this->time = $time;
		}
	}*/
	
	public function setTime($time) {
		$this->time = $time;
	}
	
	public function setBand($band) {
		$this->band = (is_string($band)) ? $band : null;
	}
	
	public function setMusicians($musicians) {
		$this->musicians = (is_string($musicians)) ? $musicians : null;
	}
	
	public function setPlace($place) {
		$this->place = (is_string($place)) ? $place : null;
	}
		
	public function setPrice($price) {
		$this->price = (is_string($price)) ? $price : null;
	}
	
	public function setTimeCreated($time) {$this->timeCreated = $time;}
	
	public function setWebsite($url) {
		$this->website = ereg('^^(http(s)?://)?([a-zA-Z0-9-]+.)?([a-zA-Z0-9-]+.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(:[0-9]+)?(/[a-zA-Z0-9-]*/?|/[a-zA-Z0-9]+\.[a-zA-Z0-9]{1,4})?$', $url) ? $url : null;
	}
	
	public function setDetails($details) {
		$this->details = (is_string($details)) ? $details : null;
	}
	
	public function setStatus($status) {
		$this->status = ($status == 'on' OR $status == 'off') ? $status : null;
	}
	
	public function setIp($ip) {$this->ip = $ip;}
	
	public function setId($id) {
		$id = (int) $id;
		if ($id > 0) {
			$this->id = $id;
        }
    }
}

?>
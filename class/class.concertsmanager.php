<?php

class ConcertsManager extends Manager {
	
	public function test() {
		$sql = $this->db->query("SELECT time FROM concerts WHERE id = 29");
		$time = $sql->fetch(PDO::FETCH_ASSOC);
		echo $time['time'];
	}
      
    public function exists($info) {
		if (is_int($info)) {
			return (bool) $this->db->query('SELECT COUNT(*) FROM concerts WHERE id = '.$info)->fetchColumn();
		}
		// Si on envoie un objet de type Concert
		else if(is_object($info)) {
			$sql = $this->db->prepare("SELECT COUNT(*) FROM concerts WHERE day = :day AND month = :month AND year = :year AND time = :time AND band = :band AND musicians = :musicians AND place = :place AND website = :website AND price = :price AND details = :details");
			$sql->bindValue(':day', $info->getDay());
			$sql->bindValue(':month', $info->getMonth());
			$sql->bindValue(':year', $info->getYear());
			$sql->bindValue(':band', $info->getBand());
			$sql->bindValue(':website', $info->getWebsite());
			$sql->bindValue(':time', $info->getTime());
			$sql->bindValue(':price', $info->getPrice());
			$sql->bindValue(':details', $info->getDetails());
			$sql->bindValue(':musicians', $info->getMusicians());
			$sql->bindValue(':place', $info->getPlace());
			$sql->execute();

			return (bool) $sql->fetchColumn();
		}
		
		/*echo (is_int($info)) ? "is int" : "";
		echo (is_bool($info)) ? "is bool" : "";
		echo (is_string($info)) ? "is string" : "";*/

	} 
	
	public function add(Concert $concert) {
		$sql = $this->db->prepare("INSERT INTO concerts SET day = :day, month = :month, year = :year, time = :time, band = :band, musicians = :musicians, place = :place, website = :website, price = :price, details = :details, timeCreated = CURRENT_TIMESTAMP(), ip = :ip");
		$sql->bindValue(':day', $concert->getDay());
		$sql->bindValue(':month', $concert->getMonth());
		$sql->bindValue(':year', $concert->getYear());
		$sql->bindValue(':band', $concert->getBand());
		$sql->bindValue(':website', $concert->getWebsite());
		$sql->bindValue(':time', $concert->getTime());
		$sql->bindValue(':price', $concert->getPrice());
		$sql->bindValue(':details', $concert->getDetails());
		$sql->bindValue(':musicians', $concert->getMusicians());
		$sql->bindValue(':place', $concert->getPlace());
		$sql->bindValue(':ip', $_SERVER["REMOTE_ADDR"]);
		$sql->execute();

		$concert->hydrate(array(
			'id' => $this->db->lastInsertId(),
		));
	}
        
	public function delete(Concert $concert) {
			$this->db->exec("DELETE FROM concerts WHERE id = " . $concert->getId());
	}
	
	public function update(Concert $concert) {
		$sql = $this->db->prepare("UPDATE concerts SET day = :day, month = :month, year = :year, time = :time, band = :band, musicians = :musicians, place = :place, website = :website, price = :price, details = :details, timeCreated = CURRENT_TIMESTAMP(), ip = :ip WHERE id = :id");
		$sql->bindValue(':day', $concert->getDay());
		$sql->bindValue(':month', $concert->getMonth());
		$sql->bindValue(':year', $concert->getYear());
		$sql->bindValue(':band', $concert->getBand());
		$sql->bindValue(':website', $concert->getWebsite());
		$sql->bindValue(':time', $concert->getTime());
		$sql->bindValue(':price', $concert->getPrice());
		$sql->bindValue(':details', $concert->getDetails());
		$sql->bindValue(':musicians', $concert->getMusicians());
		$sql->bindValue(':place', $concert->getPlace());
		$sql->bindValue(':ip', $_SERVER["REMOTE_ADDR"]);
		$sql->bindValue(':id', $concert->getId());
		$sql->execute();        
	}
	
	public function turnOff(Concert $concert) {
		$sql = $this->db->prepare("UPDATE concerts SET status = 'off' WHERE id = :id");
		$sql->bindValue(':id', $concert->getId());
		$sql->execute();        		
	}
	
	public function turnOn(Concert $concert) {
		$sql = $this->db->prepare("UPDATE concerts SET status = 'on' WHERE id = :id");
		$sql->bindValue(':id', $concert->getId());
		$sql->execute();        		
	}
	
	public function count() {
		return $this->db->query('SELECT COUNT(*) FROM concerts')->fetchColumn();
	}
	
	public function countByYear($y) {
		$sql = $this->db->prepare('SELECT COUNT(*) FROM concerts WHERE year = :year');
		$sql->bindValue(':year', $y);
		$sql->execute();
		
		return $sql->fetchColumn();
	}
	
	// All concerts to come except the ones with status = 'off'
	public function getListToCome() {
		$concerts = array();

		$d = date('d');
		$m = date('m');
		$y = date('Y');
				
		$sql = $this->db->prepare("SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month = :month AND day >= :day AND status = 'on' 
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month > :month AND status = 'on' 
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year > :year AND status = 'on' 
								   ORDER BY year ASC, month ASC, day ASC, time");
		$sql->bindValue(':year', $y);
		$sql->bindValue(':month', $m);
		$sql->bindValue(':day', $d);
		$sql->execute();

		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;

	}
	
	// All concerts to come including the ones with status = 'off'
	public function getFullListToCome() {
		$concerts = array();

		$d = date('d');
		$m = date('m');
		$y = date('Y');
				
		$sql = $this->db->prepare("SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month = :month AND day >= :day
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month > :month
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year > :year
								   ORDER BY year ASC, month ASC, day ASC, time");
		$sql->bindValue(':year', $y);
		$sql->bindValue(':month', $m);
		$sql->bindValue(':day', $d);
		$sql->execute();

		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;
		
	}
	
	// All past concerts except the ones with status = 'off'
	public function getListPastConcerts() {
		$concerts = array();

		$d = date('d');
		$m = date('m');
		$y = date('Y');
				
		$sql = $this->db->prepare("SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month = :month AND day < :day AND status = 'on' 
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month < :month AND status = 'on' 
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year < :year AND status = 'on' 
								   ORDER BY year DESC, month DESC, day DESC, time");
		$sql->bindValue(':year', $y);
		$sql->bindValue(':month', $m);
		$sql->bindValue(':day', $d);
		$sql->execute();

		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;

	}
	
	// All past concerts including the ones with status = 'off'	
	public function getFullListPastConcerts() {
		$concerts = array();

		$d = date('d');
		$m = date('m');
		$y = date('Y');
				
		$sql = $this->db->prepare("SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month = :month AND day < :day
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year = :year AND month < :month
								   UNION
								   SELECT * 
								   FROM concerts 
								   WHERE year < :year
								   ORDER BY year DESC, month DESC, day DESC, time");
		$sql->bindValue(':year', $y);
		$sql->bindValue(':month', $m);
		$sql->bindValue(':day', $d);
		$sql->execute();

		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;
		
	}
		
	public function getListByMonthYear($month, $year) {
		$concerts = array();
		
		$sql = $this->db->prepare("SELECT day, month, year, time, musicians, place, band, website, price, details, id FROM concerts WHERE year = :year AND month = :month AND status = 'on' ORDER BY day ASC, time");
		$sql->bindValue(':year', $year);
		$sql->bindValue(':month', $month);
		$sql->execute();
		
		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;
	}
	
	public function getListByYear($year) {
		$concerts = array();
		
		$sql = $this->db->prepare("SELECT day, month, year, time, musicians, place, band, website, price, details, id FROM concerts WHERE year = :year AND status = 'on' ORDER BY year DESC, month DESC, day DESC, time");
		$sql->bindValue(':year', $year);
		$sql->execute();
		
		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;
	}
	
	public function getList() {
		$concerts = array();
		
		$sql = $this->db->query("SELECT day, month, year, time, musicians, place, band, website, price, details, id, ip, timeCreated FROM concerts WHERE status = 'on' ORDER BY year DESC, month DESC, day DESC, time");
		
		while ($donnees = $sql->fetch(PDO::FETCH_ASSOC)) {
                $concerts[] = new Concert($donnees);
        }
        
		return $concerts;
	}
	
	
	// Cette fonction retourne n'importe quel concert même si son status est désactivé (égal à 'off')
	public function get($id) {	
		if ($this->exists((int) $id)) {
			$concert = array();
			$sql = $this->db->prepare("SELECT day, month, year, time, musicians, place, band, website, price, id, details, timeCreated, ip FROM concerts WHERE id = :id");
			$sql->bindValue(':id', $id);
			$sql->execute();
			
			$concert = $sql->fetch(PDO::FETCH_ASSOC);
			
			return new Concert($concert);			
        }
    	else return false;
    }
}

?>
<?php

class PicturesManager extends Manager {

	public function add(Picture $pic) {
		$sql = $this->db->prepare("INSERT INTO pictures SET name = :name, description = :description, status = :status, dateCreated = CURRENT_TIMESTAMP()");
		$sql->bindValue(':name', $pic->getName());
		$sql->bindValue(':description', $pic->getDescription());
		$sql->bindValue(':status', 'on');
		$sql->execute();
			
		$pic->hydrate(array(
			'id' => $this->db->lastInsertId(),
		));
	}
        
	public function delete(Picture $pic) {
		// Suppression de la DB
		$this->db->exec("DELETE FROM pictures WHERE id = " . $pic->getId());
		// Suppression du fichier et de l'avatar sur le serveur
		$path = "img/gallery/" . $pic->getName();
		unlink($path);
		$path = "img/gallery/s-" . $pic->getName();
		unlink($path);
	}
	
	public function update(Picture $pic) {
		$sql = $this->db->prepare("UPDATE pictures SET description = :description, dateCreated = :dateCreated WHERE id = :id ");
		$sql->bindValue(':description', $pic->getDescription());
		$sql->bindValue(':id', $pic->getId());
		$sql->bindValue(':dateCreated', $pic->getDateCreated());
		$sql->execute();        
	}
	
	public function toggle(Picture $pic) {
		$switch = ($pic->getStatus() == 'on') ? 'off' : 'on';
		$this->db->exec("UPDATE pictures SET status = '" . $switch . "' WHERE id = " . $pic->getId());
	}
	
	public function count() {
		return $this->db->query('SELECT COUNT(*) FROM pictures')->fetchColumn();
	}
	
	public function exists($info) {
		if (is_int($info)) {
			return (bool) $this->db->query('SELECT COUNT(*) FROM pictures WHERE id = '.$info)->fetchColumn();
		}
		else {
            $sql = $this->db->prepare('SELECT COUNT(*) FROM pictures WHERE name = :name');
            $sql->execute(array(':name' => $info));
        	return (bool) $sql->fetchColumn();
        }
	}
	
	public function getList() {
		$pics = array();
		
		$sql = $this->db->prepare("SELECT name, description, status, dateCreated, id FROM pictures ORDER BY dateCreated ASC");
		$sql->execute();
		
		while ($data = $sql->fetch(PDO::FETCH_ASSOC)) {
            $pics[] = new Picture($data);
        }
        
		return $pics;
	}
	
	public function get($id) {	
		
		$id = (int) $id;
				
		if ($this->exists($id)) {
			$data = array();
			$sql = $this->db->prepare("SELECT name, description, status, dateCreated, id FROM pictures WHERE id = :id");
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			
			return new Picture($data);
		}
		return false;		
    }
}

?>
<?php

class NewsManager extends Manager {

	public function add(News $news) {
		$sql = $this->db->prepare("INSERT INTO news SET title = :title, content = :content, status = :status, ip = :ip, dateCreated = CURRENT_TIMESTAMP()");
		$sql->bindValue(':title', $news->getTitle());
		$sql->bindValue(':content', $news->getContent());
		$sql->bindValue(':status', $news->getStatus());
		$sql->bindValue(':ip', $_SERVER["REMOTE_ADDR"]);
		$sql->execute();
	
		$news->hydrate(array(
			'id' => $this->db->lastInsertId(),
		));
	}
        
	public function delete(News $news) {
			$this->db->exec("DELETE FROM news WHERE id = " . $news->getId());
	}
	
	public function update(News $news) {
		$sql = $this->db->prepare("UPDATE news SET title = :title, content = :content WHERE id = :id ");
		$sql->bindValue(':title', $news->getTitle(), PDO::PARAM_INT);
		$sql->bindValue(':content', $news->getContent(), PDO::PARAM_INT);
		$sql->bindValue(':id', $news->getId(), PDO::PARAM_INT);
		$sql->execute();        
	}
	
	public function toggle(News $news) {
		$switch = ($news->getStatus() == 'on') ? 'off' : 'on';
		$this->db->exec("UPDATE news SET status = '" . $switch . "' WHERE id = " . $news->getId());
	}
	
	public function count() {
		return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
	}
	
	public function exists($info) {
		if (is_int($info)) {
			return (bool) $this->db->query('SELECT COUNT(*) FROM news WHERE id = '.$info)->fetchColumn();
		}
	}
	
	public function getList() {
		$news = array();
		
		$sql = $this->db->prepare("SELECT title, content, status, dateCreated, id, ip FROM news ORDER BY id ASC");
		$sql->execute();
		
		while ($data = $sql->fetch(PDO::FETCH_ASSOC)) {
            $news[] = new News($data);
        }
        
		return $news;
	}
	
	public function get($id) {	
		
		$id = (int) $id;
				
		if ($this->exists($id)) {
			$data = array();
			$sql = $this->db->prepare("SELECT title, content, status, dateCreated, ip, id FROM news WHERE id = :id");
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			
			return new News($data);
		}
		return false;		
    }
}

?>
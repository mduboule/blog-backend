<?php

class BlogsManager extends Manager {

	public function add(Blog $blog) {
		$sql = $this->db->prepare("INSERT INTO blog SET title = :title, content = :content, status = :status, ip = :ip, dateCreated = CURRENT_TIMESTAMP()");
		$sql->bindValue(':title', $blog->getTitle());
		$sql->bindValue(':content', $blog->getContent());
		$sql->bindValue(':status', $blog->getStatus());
		$sql->bindValue(':ip', $_SERVER["REMOTE_ADDR"]);
		$sql->execute();
	
		$blog->hydrate(array(
			'id' => $this->db->lastInsertId(),
		));
	}
        
	public function delete(Blog $blog) {
			$this->db->exec("DELETE FROM blog WHERE id = " . $blog->getId());
	}
	
	public function update(Blog $blog) {
		$sql = $this->db->prepare("UPDATE blog SET title = :title, content = :content WHERE id = :id ");
		$sql->bindValue(':title', $blog->getTitle(), PDO::PARAM_INT);
		$sql->bindValue(':content', $blog->getContent(), PDO::PARAM_INT);
		$sql->bindValue(':id', $blog->getId(), PDO::PARAM_INT);
		$sql->execute();        
	}
	
	public function toggle(Blog $blog) {
		$switch = ($blog->getStatus() == 'on') ? 'off' : 'on';
		$this->db->exec("UPDATE blog SET status = '" . $switch . "' WHERE id = " . $blog->getId());
	}
	
	public function count() {
		return $this->db->query('SELECT COUNT(*) FROM blog')->fetchColumn();
	}
	
	public function exists($info) {
		if (is_int($info)) {
			return (bool) $this->db->query('SELECT COUNT(*) FROM blog WHERE id = '.$info)->fetchColumn();
		}
	}
	
	public function getList() {
		$blog = array();
		
		$sql = $this->db->prepare("SELECT title, content, status, dateCreated, id, ip FROM blog ORDER BY id ASC");
		$sql->execute();
		
		while ($data = $sql->fetch(PDO::FETCH_ASSOC)) {
            $blog[] = new Blog($data);
        }
        
		return $blog;
	}
	
	public function get($id) {	
		
		$id = (int) $id;
				
		if ($this->exists($id)) {
			$data = array();
			$sql = $this->db->prepare("SELECT title, content, status, dateCreated, ip, id FROM blog WHERE id = :id");
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			
			return new Blog($data);
		}
		return false;		
    }
}

?>
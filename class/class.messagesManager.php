<?php

class MessagesManager extends Manager {

	public function add(Message $message) {
		$sql = $this->db->prepare("INSERT INTO message SET name = :name, content = :content, email = :email, ip = :ip, dateCreated = CURRENT_TIMESTAMP()");
		$sql->bindValue(':name', $message->getName());
		$sql->bindValue(':content', $message->getContent());
		$sql->bindValue(':email', $message->getEmail());
		$sql->bindValue(':ip', $_SERVER["REMOTE_ADDR"]);
		$sql->execute();
	
		$message->hydrate(array(
			'id' => $this->db->lastInsertId(),
		));
	}
        
	public function delete(Message $message) {
			$this->db->exec("DELETE FROM message WHERE id = " . $message->getId());
	}
	
	public function update(Message $message) {
		$sql = $this->db->prepare("UPDATE message SET name = :name, content = :content WHERE id = :id ");
		$sql->bindValue(':name', $message->getname(), PDO::PARAM_INT);
		$sql->bindValue(':content', $message->getContent(), PDO::PARAM_INT);
		$sql->bindValue(':id', $message->getId(), PDO::PARAM_INT);
		$sql->execute();        
	}
	
	public function send(Message $message) {
		$to = "mduboule@gmail.com";
		$date = date('r');
		
		$subject = "Nouveau message de " . $message->getName() . " !";
		
		$content = $message->getContent();
		$content .=	"\r\n\r\n\r\n Sent from mariusduboule.com  (" . $date . ")";
	
		$headers = "From: \"" . $message->getName() . "\" <" . $message->getEmail() . ">\r\n";
		$headers .=  "X-Mailer: PHP/" . phpversion() . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n";
			
			if ( mail($to, stripslashes($subject), $content, $headers)) {
				return true;
			}
			return false;
	}
		
	public function count() {
		return $this->db->query('SELECT COUNT(*) FROM message')->fetchColumn();
	}
	
	public function exists($info) {
		if (is_int($info)) {
			return (bool) $this->db->query('SELECT COUNT(*) FROM message WHERE id = '.$info)->fetchColumn();
		}
	}
	
	public function getList() {
		$message = array();
		
		$sql = $this->db->prepare("SELECT name, content, email, dateCreated, id, ip FROM message ORDER BY id ASC");
		$sql->execute();
		
		while ($data = $sql->fetch(PDO::FETCH_ASSOC)) {
            $message[] = new Message($data);
        }
        
		return $message;
	}
	
	public function get($id) {	
		
		$id = (int) $id;
				
		if ($this->exists($id)) {
			$data = array();
			$sql = $this->db->prepare("SELECT name, content, email, dateCreated, ip, id FROM message WHERE id = :id");
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			
			return new Message($data);
		}
		return false;		
    }
}

?>
<?php

	// Pour modifier une image
	if (isset($_GET['editPicture'])) {
		$i = (int) htmlspecialchars($_GET['editPicture']);
		$editPicture = true;
		$pictureToBeEdited = $picturesManager->get($i);

	}
	
	// Pour supprimer une image
	else if (isset($_POST['deletePicture'])) {
		$id = htmlspecialchars($_GET['updatePicture']);
		if ($picturesManager->exists((int) $id)) {
			$delete = $picturesManager->get($id);
			$picturesManager->delete($delete);			
			$pictureCom = 'suppress';
		}
	}
	
	// Pour créer un concert
	if (isset($_POST['createPicture']) && isset($_FILES["uploaded_image"]["size"]) && $_FILES["uploaded_image"]["size"] != null) {
		if ($_FILES["uploaded_image"]["error"] > 0) {
			$pictureCom = $_FILES["uploaded_image"]["error"];
			echo "problème";
		}
		else {
			// Si le fichier est plus grand que 1'562'874 bytes soit 1.5MB
			if ($_FILES["uploaded_image"]["size"] < 1562874) {
			
				$picture = new Picture(array('description' => htmlspecialchars($_POST['description']), 'name' => $_FILES["uploaded_image"]["name"])); 

				if (!$picturesManager->exists($picture->getName())) {
				
					// Enregistrement de l'image originale sur le serveur 
					if((bool) $picture->setImageType($_FILES['uploaded_image']['tmp_name'])) {
						$picture->setImage($_FILES['uploaded_image']['tmp_name']);
						$picture->save($_FILES['uploaded_image']['name']);

						// Création de l'avatar et enregistrement
						if ($picture->getWidth() > 100 or $picture->getHeight() > 100) {
							if ($picture->getWidth() > $picture->getHeight()) {
								$picture->resizeToWidth(100);
							}
							else {
								$picture->resizeToHeight(100);
							}
						}
						$picture->save("s-" . $_FILES['uploaded_image']['name']);
						$picturesManager->add($picture);
						$pictureCom = 'ok';
					}
					else {
						$pictureCom = "wrongType";
					}
				}
				else {
					$pictureCom = "existsAlready";
				}
			}
			else {
				$pictureCom = "fileTooBig";
			}
		}	
	}
	
	// Si un des champs obligatoires n'a pas été rempli
	else if (isset($_POST['createPicture']) && (!isset($_FILES["uploaded_image"]["size"]) or $_FILES["uploaded_image"]["size"] == null)) {
								
		$fileMissing = true;
		$pictureCom = 'fileMissing';
		
		$description = isset($_POST['description']) ? $_POST['description'] : null;
	}
	
	// En cas de modifications d'une description d'image
	if (isset($_POST['editPicture']) && isset($_POST['description']) && $_POST['description'] != null) {	
		
		$originalPicture = $picturesManager->get((int) htmlspecialchars($_GET['updatePicture']));
		
		$updatedPicture = new Picture(array('description' => htmlspecialchars($_POST['description']),
											'id' => htmlspecialchars($_GET['updatePicture']),
											'dateCreated' => $originalPicture->getDateCreated())); 

		$picturesManager->update($updatedPicture);
		$pictureCom = 'updated';
	}
?>
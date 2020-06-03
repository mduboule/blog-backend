<?php
		// Pour modifier une news
		if (isset($_GET['editNews'])) {
			$i = (int) htmlspecialchars($_GET['editNews']);
			$editNews = true;
			$newsToBeEdited = $newsManager->get($i);
		}
		// Pour supprimer une news
		else if (isset($_GET['deleteNews'])) {
			$id = htmlspecialchars($_GET['deleteNews']);
			if ($newsManager->exists((int) $id)) {
				$delete = $newsManager->get($id);
				$newsManager->delete($delete);
				$newsCom = 'suppress';
			}
		}
		// Pour créer une news
		if (isset($_POST['createNews']) && isset($_POST['title_news']) && $_POST['title_news'] != null
										&& isset($_POST['content_news']) && $_POST['content_news'] != null) {
			
			$news = new News(array('title' => htmlspecialchars(stripslashes($_POST['title_news']), ENT_QUOTES), 
								   'content' => htmlspecialchars(stripslashes($_POST['content_news']), ENT_QUOTES), 
								   'status' => 'on'));
			$newsManager->add($news);
			$newsCom = 'ok';
		}
		// Si un des champs n'a pas été rempli
		else if (isset($_POST['createNews']) && ((!isset($_POST['title_news'])) OR ($_POST['title_news'] == null) OR
												 (!isset($_POST['content_news'])) OR ($_POST['content_news'] == null))){
			$fieldMissing = true;
			$newsCom = 'fieldmissing';
			
			$title = isset($_POST['title_news']) ? stripslashes($_POST['title_news']) : null;
			$content = isset($_POST['content_news']) ? stripslashes($_POST['content_news']) : null;
		}
		// Pour modifier le statut de la news ON ou OFF (toggle)
		else if (isset($_GET['toggle']) && $_GET['toggle'] != null) {
			$id = (int) $_GET['toggle'];
			$toggledNews = $newsManager->get($id);
			if ($toggledNews != false) {
				$newsManager->toggle($toggledNews);
			}
		}
		
		// En cas de modifications de news
		if (isset($_POST['modifyNews']) && isset($_POST['title_news']) && $_POST['title_news'] != null
										 && isset($_POST['content_news']) && $_POST['content_news'] != null) {
		
			$updatedNews = new News(array('title' => htmlspecialchars(stripslashes($_POST['title_news']), ENT_QUOTES), 
								  		 'content' => htmlspecialchars(stripslashes($_POST['content_news']), ENT_QUOTES),
								  		 'id' => htmlspecialchars($_GET['updateNews'])));

			$newsManager->update($updatedNews);
			$newsCom = 'updated';
		}
		// En cas de modifications de news mais avec un champ manquant
		else if (isset($_POST['modifyNews']) && (!isset($_POST['title_news']) OR $_POST['title_news'] == null OR
												 !isset($_POST['content_news']) OR $_POST['content_news'] == null)) {
		
			$newsUpdateFieldMissing = true;
			$newsCom = 'updateFieldmissing';
			
			$title = isset($_POST['title_news']) ? stripslashes($_POST['title_news']) : null;
			$content = isset($_POST['content_news']) ? stripslashes($_POST['content_news']) : null;
			
			$i = (int) htmlspecialchars($_GET['updateNews']);
			$newsToBeEdited = $newsManager->get($i);			
		}
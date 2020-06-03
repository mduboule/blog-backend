<?php
		// Pour modifier une blog
		if (isset($_GET['editBlog'])) {
			$i = (int) htmlspecialchars($_GET['editBlog']);
			$editBlog = true;
			$blogToBeEdited = $blogManager->get($i);
		}
		// Pour supprimer une blog
		else if (isset($_GET['deleteBlog'])) {
			$id = htmlspecialchars($_GET['deleteBlog']);
			if ($blogManager->exists((int) $id)) {
				$delete = $blogManager->get($id);
				$blogManager->delete($delete);
				$blogCom = 'suppress';
			}
		}
		// Pour créer une blog
		if (isset($_POST['createBlog']) && isset($_POST['title_blog']) && $_POST['title_blog'] != null
									&& isset($_POST['content_blog']) && $_POST['content_blog'] != null) {
			
			$blog = new Blog(array('title' => htmlspecialchars(stripslashes($_POST['title_blog']), ENT_QUOTES), 
								   'content' => htmlspecialchars(stripslashes($_POST['content_blog']), ENT_QUOTES), 
								   'status' => 'on'));
			$blogManager->add($blog);
			$blogCom = 'ok';
		}
		// Si un des champs n'a pas été rempli
		else if (isset($_POST['createBlog']) && ((!isset($_POST['title_blog'])) OR (!isset($_POST['content_blog'])) OR
													($_POST['title_blog'] == null) OR ($_POST['content_blog'] == null))){
			$fieldMissing = true;
			$blogCom = 'fieldmissing';
			
			$title = isset($_POST['title_blog']) ? stripslashes($_POST['title_blog']) : null;
			$content = isset($_POST['content_blog']) ? stripslashes($_POST['content_blog']) : null;
		}
		// Pour modifier le statut de la blog ON ou OFF (toggle)
		else if (isset($_GET['toggle']) && $_GET['toggle'] != null) {
			$id = (int) $_GET['toggle'];
			$toggledBlog = $blogManager->get($id);
			if ($toggledBlog != false) {
				$blogManager->toggle($toggledBlog);
			}
		}
		
		// En cas de modifications de blog
		if (isset($_POST['modifyBlog']) && isset($_POST['title_blog']) && $_POST['title_blog'] != null
										 && isset($_POST['content_blog']) && $_POST['content_blog'] != null) {
		
			$updatedBlog = new Blog(array('title' => htmlspecialchars(stripslashes($_POST['title_blog']), ENT_QUOTES), 
								  		 'content' => htmlspecialchars(stripslashes($_POST['content_blog']), ENT_QUOTES),
								  		 'id' => htmlspecialchars($_GET['updateBlog'])));

			$blogManager->update($updatedBlog);
			$blogCom = 'updated';
		}
		// En cas de modifications de blog mais avec un champ manquant
		else if (isset($_POST['modifyBlog']) && (!isset($_POST['title_blog']) OR $_POST['title_blog'] == null
										 OR !isset($_POST['content_blog']) OR $_POST['content_blog'] == null)) {
		
			$blogUpdateFieldMissing = true;
			$blogCom = 'updateFieldmissing';
			
			$title = isset($_POST['title_blog']) ? stripslashes($_POST['title_blog']) : null;
			$content = isset($_POST['content_blog']) ? stripslashes($_POST['content_blog']) : null;
			
			$i = (int) htmlspecialchars($_GET['updateBlog']);
			$blogToBeEdited = $blogManager->get($i);			
		}
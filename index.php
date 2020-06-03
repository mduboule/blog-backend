<?php

	/*
	 * Backend qui permet de modifier une DB pour plusieurs aspects d'un site 
	 * web : News, Concerts, Blog, Gallerie de photos.
	 *
	 * Cette page php fait appel à plusieurs classes contenues dans le dossier
	 * 'class' voisin.
	 *
	 * File: index.php
	 * Author: Marius Duboule
	 * Copyright: 2012 © Marius Duboule
	 * Date: 10/31/12
	 *
	 * Version: 1.2
	 */

	////////////////
	/// CODE SQL ///
	///////////////
	
	/*
	
	CREATE TABLE IF NOT EXISTS `concerts` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`day` smallint(2) COLLATE utf8_general_ci NOT NULL,
	`month` smallint(2) COLLATE utf8_general_ci NOT NULL,
	`year` smallint(4) COLLATE utf8_general_ci NOT NULL,
	`time` time NOT NULL,
	`band` varchar(60) COLLATE utf8_general_ci NOT NULL,
	`musicians` varchar(300) COLLATE utf8_general_ci NOT NULL,
	`price` varchar(20) COLLATE utf8_general_ci,
	`place` varchar(60) COLLATE utf8_general_ci NOT NULL,
	`website` varchar(400) COLLATE utf8_general_ci,
	`timeCreated` timestamp,
	`details` varchar(200) COLLATE utf8_general_ci,
	`status` enum('on','off') COLLATE utf8_general_ci NOT NULL DEFAULT 'on',
	`ip` varchar(15) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	
	CREATE TABLE IF NOT EXISTS `news` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(100) COLLATE utf8_general_ci NOT NULL,
	`content` varchar(300) COLLATE utf8_general_ci NOT NULL,
	`ip` varchar(25) COLLATE utf8_general_ci NOT NULL,
	`status` enum('on','off') COLLATE utf8_general_ci NOT NULL DEFAULT 'on',
	`dateCreated` timestamp,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	
	CREATE TABLE IF NOT EXISTS `message` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(100) COLLATE utf8_general_ci NOT NULL,
	`content` varchar(10000) COLLATE utf8_general_ci NOT NULL,
	`ip` varchar(25) COLLATE utf8_general_ci NOT NULL,
	`name` varchar(100) COLLATE utf8_general_ci NOT NULL,
	`dateCreated` timestamp,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

	CREATE TABLE IF NOT EXISTS `blog` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(100) COLLATE utf8_general_ci NOT NULL,
	`content` varchar(2000) COLLATE utf8_general_ci NOT NULL,
	`ip` varchar(20) COLLATE utf8_general_ci NOT NULL,
	`status` enum('on','off') COLLATE utf8_general_ci NOT NULL DEFAULT 'on',
	`dateCreated` timestamp,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

	CREATE TABLE IF NOT EXISTS `pictures` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(150) COLLATE utf8_general_ci NOT NULL,
	`description` varchar(200) COLLATE utf8_general_ci NOT NULL,
	`status` enum('on','off') COLLATE utf8_general_ci NOT NULL DEFAULT 'on',
	`dateCreated` timestamp,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	
	*/

	//////////////
	//// CODE ////
	//////////////
	
	session_start();	
	
	$admin = false; // Détermine l'accès au backend
	$wrongPsw = false; // Détermine si un faux mot de passe a été fourni
	$fieldmissing = false; // Détermine si un champ est manquant lors de la création d'une news
	$concertUpdateFieldMissing = false; // Détermine si un champ est manquant lors de la modification d'un concert
	$newsUpdateFieldMissing = false; // Détermine si un champ est manquant lors de la modification d'une news
	$editNews = false; // Détermine si l'utilisateur essaie de modifier une news
	$pictureCom = ""; // Initialisation de la variable qui communique les erreurs de la partie pictures
	
	$password = "269f4e98fc91bd628904ce5072db0193";

	include 'config.php';
	include 'inc/db.inc';

	require 'class/class.manager.php';
	require 'class/class.concert.php';
	require 'class/class.concertsmanager.php';
	require 'class/class.news.php';
	require 'class/class.newsmanager.php';
	require 'class/class.blog.php';
	require 'class/class.blogsmanager.php';
	require 'class/class.picture.php';
	require 'class/class.picturesmanager.php';
	
	include 'tools/tools.php';
	
	if (isset($_POST['psw'])) {
		$pswSent = htmlspecialchars($_POST['psw']);
	}
		
	//Pour la première venue
	if (isset($pswSent) and (md5($pswSent) === $password)) {
		$_SESSION["valid"] = true;
		$admin = true;
	}
	//Pour les venues suivantes
	else if (isset($_SESSION["valid"]) and !$_GET['cmd'] == 'logout') {
		$admin = true;
	}
	//En cas de faux psw
	else if (isset($pswSent) and md5($pswSent) !== $password) {
		$wrongPsw = true;
	}
	//En cas de non psw
	else if (isset($_POST['attempt']) and !isset($pswSent)) {
		$wrongPsw = true;
	}
	
	if ($admin) {
	
		$concertsManager = new ConcertsManager($db);
		$newsManager = new NewsManager($db);
		$blogManager = new BlogsManager($db);
		$picturesManager = new PicturesManager($db);
		
		include 'inc/newsHeader.php';
		include 'inc/concertsHeader.php';
		include 'inc/blogHeader.php';
		include 'inc/picturesHeader.php';
		
	}
	
	// Choix du module à afficher
	if (isset($_GET['display']) and $_GET['display'] != null) {
		$display = htmlspecialchars($_GET['display']);
		switch($display) {
			case 'news' : $displayNews = true;
						  $displayConcerts = false;
						  $displayBlog = false;
						  $displayPictures = false;
			break;
			
			case 'concerts' : $displayConcerts = true;
							 $displayNews = false;
							 $displayBlog = false;
							 $displayPictures = false;
			break;
			
			case 'blog' : $displayBlog = true;
							$displayConcerts = false;
							$displayFlyers = false;
							$displayPictures = false;
			break;
			
			case 'pictures' : $displayPictures = true;
							$displayBlog = false;
							$displayConcerts = false;
							$displayFlyers = false;
			break;
		}
	}
	header ('Content-type:text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo PAGE_TITLE; ?></title>
		<meta name="robots" content="noindex" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="css/style.css" />
		<script type="text/javascript" src="tools/functions.js"></script>
		<script type="text/javascript" src="../tools/jquery-1.7.2.js"></script>
		<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	</head>
	<body>
	
	<script type="text/javascript">
		//<![CDATA[
		bkLib.onDomLoaded(function() {
		new nicEditor({buttonList : ['bold','italic','underline', 'strikethrough', 'left', 'center', 'right', 'ol', 'ul', 'indent', 'outdent', 'link', 'unlink', 'fontSize',]}).panelInstance('content_news');
		new nicEditor({buttonList : ['bold','italic','underline', 'strikethrough', 'left', 'center', 'right', 'ol', 'ul', 'indent', 'outdent', 'link', 'unlink', 'fontSize',]}).panelInstance('content_blog');
		});
		//]]>
	</script>	
	
<?php 
	if (!$admin) { 
		if ($wrongPsw)  {
?>
			
				<div class="center-psw">
					<h2 class="left">Admin</h2>
					<div class="subcontent">
						<p>This area is password protected.</p>
						<p class="error">Wrong password.</p>					
						<form method="post" action="?display=news">
							<table>
								<tr>
									<td>
										<label for="psw">Password :</label>
									</td>
									<td>
										<input type="password" name="psw" id="psw" />
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" name="Send" value="Send" />
									</td>
								</tr>
							</table>
						</form>			
					<a href="/">Back to website</a>		
					</div> <!-- subcontent -->
					<div class="specifications">
						<span class="version">1.2</span>
						<a href="http://osotoweb.com" target="_blank">osotoweb.com</a>
					</div>
				</div> <!-- center-psw -->
			
<?php
			session_destroy();
			exit;
			}
			else {
?>
				
				<div class="center-psw">	
					<h2 class="left">Admin</h2>
					<div class="subcontent">
						<p>This area is password protected.</p>
						<form method="post" action="?display=news">
							<table>
								<tr>
									<td>
										<label for="psw">Password :</label>
									</td>
									<td>
										<input type="password" name="psw" id="psw" />
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" name="Send" value="Send" />
									</td>
								</tr>
							</table>
						</form>					
					<a href="/">Back to website</a>		
					</div> <!-- subcontent -->
					<div class="specifications">
						<a href="http://osotoweb.com" target="_blank">osotoweb.com</a>
						<span class="version">1.2</span>
					</div>
				</div> <!-- center-psw -->
<?php
		session_destroy();
		exit;
		}
	}		
	if ($admin) {
?>
		
		<div class="main-container">
			<div id="admin">
				<p id="admin_title"><?php echo PAGE_TITLE;?></p>
				<div class="admin-nav">
					<p id="admin_logout"><a class="link" href="index.php?cmd=logout">Log out</a></p>
					<ul>
	 					<?php
	 					echo (MOD_NEWS) ? '<li style="border-left: 1px solid #b4b4b4"><a href="?display=news" onClick="$(\'#blog\').hide();$(\'#pictures\').hide();$(\'#concerts\').hide();$(\'#news\').show();">News</a></li>' : "";
	 					echo (MOD_CONCERT) ? '<li><a href="?display=concerts" onClick="$(\'#blog\').hide();$(\'#pictures\').hide();$(\'#news\').hide();$(\'#concerts\').show();">Concerts</a></li>' : "";
	 					echo (MOD_BLOG) ? '<li><a href="?display=blog" onClick="$(\'#concerts\').hide();$(\'#news\').hide();$(\'#pictures\').hide();$(\'#blog\').show();">Blog</a></li>' : "";
	 					echo (MOD_GALLERY) ? '<li><a href="?display=pictures" onClick="$(\'#concerts\').hide();$(\'#news\').hide();$(\'#blog\').hide();$(\'#pictures\').show();">Pictures</a></li>' : "";
	 					?>
	 				</ul>
	 			</div>
			</div> <!-- admin -->

	 		<div id="main-center" class="main-center">	 			
<?php
		// Module NEWS
		echo '<div id="news" style="margin: 0; padding: 0;' . (($displayNews) ? "" : 'display: none;') . '">';
			include 'inc/modNews.php';
		echo '</div>';
		// Module CONCERTS
		echo '<div id="concerts" style="margin: 0; padding: 0;' . (($displayConcerts) ? "" : 'display: none;') . '">';
			include 'inc/modConcerts.php';
		echo '</div>';
		// Module BLOG
		echo '<div id="blog" style="margin: 0; padding: 0;' . (($displayBlog) ? "" : 'display: none;') . '">';
			include 'inc/modBlog.php';
		echo '</div>';
		// Module PICTURES
		echo '<div id="pictures" style="margin: 0; padding: 0;' . (($displayPictures) ? "" : 'display: none;') . '">';
			include 'inc/modPictures.php';
		echo '</div>';
	}
?>

			</div> <!-- main-center -->
		</div> <!-- main-container -->
	</body>
</html>
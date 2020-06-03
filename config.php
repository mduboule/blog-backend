<?php

	/*
	 * Fichier de configuration du backend. Merci de remplir les informations ci-dessous
	 * qui permettent l'accès au base de données et définissent les onglets nécessaire.
	 *
	 *
	 * File: config.php
	 * Author: Marius Duboule
	 * Copyright: 2012 © Marius Duboule
	 * Date: 10/31/12
	 */

		// PARAMETRES GENERAUX
		// Titre de la page d'administration
		define("PAGE_TITLE", "User - ADMIN");

		// Langue de la page d'administration ['fr', 'en']
		define("LANGUAGE", "fr");

		// Horloge au format 24h (false) ou au format américain sur 12h am/pm (true)
		define("US_CLOCK", false);


		// ACCES SQL
		// Chemin vers le serveur SQL, sauf contre-indication 'localhost' ou '127.0.0.1'
		define("PARAM_HOTE", "localhost");

		// Port utilisé, sauf contre-indication '3306'
		define("PARAM_PORT", "3306");

		// Nom de la base de donnée
		define("PARAM_NOM_DB", "Votre base de données");

		// Nom d'utilisateur utilisé pour se connecter
		define("PARAM_UTILISATEUR", "Votre nom d'utilisateur");

		// Mot de passe de l'utilisateur pour se connecter
		define("PARAM_MOT_DE_PASSE", "Votre mot de passe");


		// MODULES A ACTIVER indiquer 'true' ou 'false' sans guillemets
		// Pour activer/désactiver le module concert
		define("MOD_CONCERT", true);

		// Pour activer/désactiver le module de news
		define("MOD_NEWS", true);

		// Pour activer/désactiver le module de blog
		define("MOD_BLOG", true);

		// Pour activer/désactiver le module de gallerie photo
		define("MOD_GALLERY", true);

		// Pour activer/désactiver le module de flyer (non disponible pour l'instant)
		//define("MOD_FLYER", false);

		// Pour activer/désactiver le module de newsletter (non disponible pour l'instant)
		//define("MOD_NEWSLETTER", false);

?>
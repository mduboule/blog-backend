<?php 

function verifyURL($url) {
	if(preg_match('/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/', $url)) {
		return true;
	}
	return false;
}

function transformLink($link) {
	//On extrait l'url en partant de la deuxième position jusqu'à l'apparition du premier espace
	$url = substr($link, 2, (strpos($link, ' ') -2));
	//On extrait le text du lien en partant du premier espace (+1) jusqu'à l'avant avant dernier caractère (-2)
	$txt = substr($link, (strpos($link, ' ') + 1), -2);
	
	$a = '<a href="' . $url . '">' . $txt . '</a>';
		return $a;
}

/*
 * Transforme une chaine de type '**un texte en gras**'
 * en un chaine de type '<strong>un texte en gras</strong>'
 */
function transformBold($str) {
	$content = substr($str, 2, strlen($str) -4);
	$bold = '<strong>' . $content . '</strong>';
		return $bold;
}

/*
 * Transforme une chaine de type '||un texte en italic||'
 * en un chaine de type '<em>un texte en gras</em>'
 */
function transformItalic($str) {
	$content = substr($str, 2, strlen($str) -4);
	$italic = '<em>' . $content . '</em>';
		return $italic;
}

/*
 * Transforme une chaine de type '_un texte souligné_'
 * en un chaine de type '<span class="underline">un texte souligné</span>'
 */
function transformUnderline($str) {
	$content = substr($str, 1, strlen($str) -3);
	$underline = '<span class="underline">' . $content . '</span>';
		return $underline;
}

//Remplace la syntaxe [[http://www.test.com text du lien]] par un vrai lien html dans une chaine de caractères
function addLinks($var) {
	$txt = $var;
	$header = null;
	$start = null;
	$end = null;
	$stop = false;
	$linkOpen = '[[';
	$linkClose = ']]';

	$start = strpos($txt, $linkOpen);	
	
	while (!$stop) {		
	
		//Si des caractères précédent la chaine à transformer
		//alors on les isole (dans $header) et on supprime le 
		//$header de la chaine $txt.
		if($start !== false) {
			$header = substr($txt, 0, $start);
			$txt = strstr($txt, $linkOpen);
		}	
		else {
			$header = "";
		}
		
		$end = strpos($txt, $linkClose);
		
		//Si il n'y a plus (ou pas !) de caractère ouvrant ou fermant
		if ($end == $start) {
			return $txt;
		}
		
		$link = substr($txt, 0, $end + 2);
		$txt = substr($txt, $end + 2, (strlen($txt) - ($end + 2)));
		// Reconstruit la variable $txt
		$txt = $header . transformLink($link) . $txt;
		// Chercher la prochaine apparition du caractère ouvrant
		$start = strpos($txt, $linkOpen);	
		if (($start == 0) or ($start == "") or ($start == false)) {
			$stop = true;
		}
	}
	return $txt;
}

function addBold($var) {
	$txt = $var;
	$header = null;
	$start = null;
	$end = null;
	$stop = false;
	$char = '**';

	$start = strpos($txt, $char);	
		
	while (!$stop) {		
	
		//Si des caractères précédent la chaine à transformer
		//alors on les isole (dans $header) et on supprime le 
		//$header de la chaine $txt.
		if($start != 0) {
			$header = substr($txt, 0, $start);
			$txt = strstr($txt, $char);
		}	
		else {
			$header = "";
		}
		
		$end = strpos($txt, $char, 3);
					
		//Si il n'y a plus (ou pas !) de caractère ouvrant ou fermant
		if ($end == $start) {
			return $txt;
		}
		
		$bold = substr($txt, 0, $end + 2);
		// Supprime la chaine à 'bolder' de $txt
		$txt = substr($txt, $end + 2, (strlen($txt) - ($end + 2)));
		// Reconstruit la variable $txt
		$txt = $header . transformBold($bold) . $txt;
		// Chercher la prochaine apparition du caractère ouvrant
		$start = strpos($txt, $char);	
		if (($start == 0) or ($start == "") or ($start == false)) {
			$stop = true;
		}
	}
	return $txt;
}

function addItalic($var) {
	$txt = $var;
	$header = null;
	$start = null;
	$end = null;
	$char = '||';
	$stop = false;

	$start = strpos($txt, $char);	
	
	while (!$stop) {		
	
		//Si des caractères précédent la chaine à transformer
		//alors on les isole (dans $header) et on supprime le 
		//$header de la chaine $txt.
		if($start != 0) {
			$header = substr($txt, 0, $start);
			$txt = strstr($txt, $char);
		}	
		else {
			$header = "";
		}
		
		$end = strpos($txt, $char, 3);
					
		//Si il n'y a plus (ou pas !) de caractère ouvrant ou fermant
		if ($end == $start) {
			return $txt;
		}
		
		$italic = substr($txt, 0, $end + 2);
		// Supprime la chaine à 'transformer' de $txt
		$txt = substr($txt, $end + 2, (strlen($txt) - ($end + 2)));
		// Reconstruit la variable $txt
		$txt = $header . transformItalic($italic) . $txt;
		// Chercher la prochaine apparition du caractère ouvrant
		$start = strpos($txt, $char);
		if (($start == 0) or ($start == "") or ($start == false)) {
			$stop = true;
		}
	}
	return $txt;
}

function addUnderline($var) {
	$txt = $var;
	$header = null;
	$start = null;
	$end = null;
	$char = '_';
	$stop = false;

	$start = strpos($txt, $char);	
		
	while (!$stop) {		
	
		//Si des caractères précédent la chaine à transformer
		//alors on les isole (dans $header) et on supprime le 
		//$header de la chaine $txt.
		//
		if($start != 0) {
			$header = substr($txt, 0, $start);
			$txt = strstr($txt, $char);
		}	
		else {
			$header = "";
		}
		
		$end = strpos($txt, $char, 2);
					
		//Si il n'y a plus (ou pas !) de caractère ouvrant ou fermant
		if ($end == $start) {
			return $txt;
		}
		
		$underline = substr($txt, 0, $end + 2);
		// Supprime la chaine à 'souligner' de $txt
		$txt = substr($txt, $end + 1, (strlen($txt) - ($end + 1)));
		// Reconstruit la variable $txt
		$txt = $header . transformUnderline($underline) . $txt;
		// Chercher la prochaine apparition du caractère ouvrant
		$start = strpos($txt, $char);	
		if (($start == 0) or ($start == "") or ($start == false)) {
			$stop = true;
		}
	}
	return $txt;
}

function addFonts($txt) {
	$temp = $txt;
	$temp = addLinks($temp);
	$temp = addBold($temp);
	$temp = addItalic($temp);
	$temp = addUnderline($temp);
		return $temp;
}

?>
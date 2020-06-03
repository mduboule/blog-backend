<?php

	include '../../inc/db.inc';

	$sql = sprintf("INSERT INTO concerts 
					(day, month, year, band, musicians, place, ip, time) VALUES 
					('" . htmlspecialchars($_POST["day"]) . "', '" . htmlspecialchars($_POST["month"]) . "', '" . htmlspecialchars($_POST["year"]) . "', 
					'" . htmlspecialchars($_POST["band"]) . "', '" . htmlspecialchars($_POST["playingWith"]) . "',
					'" . htmlspecialchars($_POST["place"]) . "', '" . $_SERVER["REMOTE_ADDR"] . "', CURRENT_TIMESTAMP())");
					$res = mysql_query($sql) or die(mysql_error());
	
	header('Location: ../settings.php');
	exit();
					
?>
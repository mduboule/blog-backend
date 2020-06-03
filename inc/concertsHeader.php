<?php

	// Pour modifier un concert
	if (isset($_GET['editConcert'])) {
		$i = (int) htmlspecialchars($_GET['editConcert']);
		$editConcert = true;
		$concertToBeEdited = $concertsManager->get($i);
		
		$day = $concertToBeEdited->getDay();
		$month = $concertToBeEdited->getMonth();
		$year = $concertToBeEdited->getYear();

	}
	
	// Pour supprimer un concert
	else if (isset($_GET['deleteConcert'])) {
		$id = htmlspecialchars($_GET['deleteConcert']);
		if ($concertsManager->exists((int) $id)) {
			$delete = $concertsManager->get($id);
			$concertsManager->delete($delete);
			$concertCom = 'suppress';
		}
	}
	
	// Pour créer un concert
	if (isset($_POST['createConcert']) && isset($_POST['day']) && $_POST['day'] != null
									   && isset($_POST['month']) && $_POST['month'] != null
									   && isset($_POST['year']) && $_POST['year'] != null
									   && isset($_POST['concertHour']) && $_POST['concertHour'] != null
									   && isset($_POST['concertMinute']) && $_POST['concertMinute'] != null
									   && isset($_POST['band']) && $_POST['band'] != null
									   && isset($_POST['place']) && $_POST['place'] != null) {

		if (US_CLOCK) {
			$hour = htmlspecialchars($_POST['concertHour']) + (($_POST['daytime'] == 'pm') ? 12 : 0);
		}
		else {
			$hour = htmlspecialchars($_POST['concertHour']);
		}
		$time = $hour . ':' . htmlspecialchars($_POST['concertMinute']) . ':00';		


		$concert = new Concert(array('day' => htmlspecialchars($_POST['day']), 
							 	     'month' => htmlspecialchars($_POST['month']), 
							 	     'year' => htmlspecialchars($_POST['year']), 
							 	     'time' => $time,
							 	     'band' => htmlspecialchars($_POST['band']),
							 	     'musicians' => htmlspecialchars($_POST['musicians']), 
							 	     'place' => htmlspecialchars($_POST['place']), 
							 	     'website' => htmlspecialchars($_POST['website']), 
							 	     'price' => htmlspecialchars($_POST['price']), 
							 	     'details' => htmlspecialchars($_POST['details']))); 
		
		if (!$concertsManager->exists($concert)) {
			$concertsManager->add($concert);
			$concertCom = 'ok';
		}
	}
	
	// Si un des champs obligatoires n'a pas été rempli
	else if (isset($_POST['createConcert']) && (!isset($_POST['day']) or $_POST['day'] == null
											or !isset($_POST['month']) or $_POST['month'] == null
											or !isset($_POST['year']) or $_POST['year'] == null
											or !isset($_POST['concertHour']) or $_POST['concertHour'] == null
											or !isset($_POST['concertMinute']) or $_POST['concertMinute'] == null
											or !isset($_POST['band']) or $_POST['band'] == null
											or !isset($_POST['place']) or $_POST['place'] == null)) {
								
		$concertFieldMissing = true;
		$concertCom = 'fieldMissing';
		
		$day = isset($_POST['day']) ? $_POST['day'] : null;
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$year = isset($_POST['year']) ? $_POST['year'] : null;
		$concertMinute = isset($_POST['concertMinute']) ? $_POST['concertMinute'] : null;
		$concertHour = isset($_POST['concertHour']) ? $_POST['concertHour'] : null;
		$band = isset($_POST['band']) ? $_POST['band'] : null;
		$musicians = isset($_POST['musicians']) ? $_POST['musicians'] : null;
		$place = isset($_POST['place']) ? $_POST['place'] : null;
		$website = isset($_POST['website']) ? $_POST['website'] : null;
		$price = isset($_POST['price']) ? $_POST['price'] : null;
		$details = isset($_POST['details']) ? $_POST['details'] : null;
		
		if (US_CLOCK) {
			if (isset($_POST['concertHour'])) {
				$hour = htmlspecialchars($_POST['concertHour']) + (($_POST['daytime'] == 'pm') ? 12 : 0);
			}
			else $hour = null;
		}
		else {
			if (isset($_POST['concertHour'])) {
				$hour = htmlspecialchars($_POST['concertHour']);
			}
			else $hour = null;
		}
	}
	
	// En cas de modifications d'un concert
	if (isset($_POST['updateConcert']) && isset($_POST['day']) && $_POST['day'] != null
									   && isset($_POST['month']) && $_POST['month'] != null
									   && isset($_POST['year']) && $_POST['year'] != null
									   && isset($_POST['concertHour']) && $_POST['concertHour'] != null
									   && isset($_POST['concertMinute']) && $_POST['concertMinute'] != null
									   && isset($_POST['band']) && $_POST['band'] != null
									   && isset($_POST['place']) && $_POST['place'] != null) {	
		
		if (US_CLOCK) {
			$hour = htmlspecialchars($_POST['concertHour']) + (($_POST['daytime'] == 'pm') ? 12 : 0);
		}
		else {
			$hour = htmlspecialchars($_POST['concertHour']);
		}
		$time = $hour . ':' . htmlspecialchars($_POST['concertMinute']) . ':00';		
		
		$updatedConcert = new Concert(array('day' => htmlspecialchars($_POST['day']), 
							 	     		'month' => htmlspecialchars($_POST['month']), 
							 	     		'year' => htmlspecialchars($_POST['year']), 
							 	     		'time' => $time,
							 	     		'band' => htmlspecialchars($_POST['band']),
							 	     		'musicians' => htmlspecialchars($_POST['musicians']), 
							 	     		'place' => htmlspecialchars($_POST['place']), 
							 	     		'website' => htmlspecialchars($_POST['website']), 
							 	     		'price' => htmlspecialchars($_POST['price']), 
							 	     		'details' => htmlspecialchars($_POST['details']),
							 	     		'id' => htmlspecialchars($_GET['updateConcert']))); 

		$concertsManager->update($updatedConcert);
		$concertCom = 'updated';
	}
	
	// En cas de modifications d'une mais avec un des champs obligatoires manquant
	else if (isset($_POST['updateConcert']) && (!isset($_POST['day']) or $_POST['day'] == null
											or !isset($_POST['month']) or $_POST['month'] == null
											or !isset($_POST['year']) or $_POST['year'] == null
											or !isset($_POST['concertHour']) or $_POST['concertHour'] == null
											or !isset($_POST['concertMinute']) or $_POST['concertMinute'] == null
											or !isset($_POST['band']) or $_POST['band'] == null
											or !isset($_POST['place']) or $_POST['place'] == null)) {
	
		$concertUpdateFieldMissing = true;
		$concertCom = 'concertUpdateFieldMissing';
		
		$day = isset($_POST['day']) ? $_POST['day'] : null;
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$year = isset($_POST['year']) ? $_POST['year'] : null;
		$concertMinute = isset($_POST['concertMinute']) ? $_POST['concertMinute'] : null;
		$concertHour = isset($_POST['concertHour']) ? $_POST['concertHour'] : null;
		$band = isset($_POST['band']) ? $_POST['band'] : null;
		$musicians = isset($_POST['musicians']) ? $_POST['musicians'] : null;
		$place = isset($_POST['place']) ? $_POST['place'] : null;
		$website = isset($_POST['website']) ? $_POST['website'] : null;
		$price = isset($_POST['price']) ? $_POST['price'] : null;
		$details = isset($_POST['details']) ? $_POST['details'] : null;	
		
		if (US_CLOCK) {
			if (isset($_POST['concertHour'])) {
				$hour = htmlspecialchars($_POST['concertHour']) + (($_POST['daytime'] == 'pm') ? 12 : 0);
			}
			else $hour = null;
		}
		else {
			if (isset($_POST['concertHour'])) {
				$hour = htmlspecialchars($_POST['concertHour']);
			}
			else $hour = null;
		}
			
		$i = (int) htmlspecialchars($_GET['updateConcert']);
		// TEST : la ligne ci-dessous est vraiment utile ??!?
		$concertToBeEdited = $concertsManager->get($i);			
	}
?>
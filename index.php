<?php
	// Démarrage de la session
	//session_cache_limiter('private');
	session_cache_expire(1);
	session_start();
	
	// Protection des get url
	if (isset($_SESSION['rights'])){
		$droits = $_SESSION['rights'];
	} else {
		$droits = 'user';
	}
	
	include('modele.php');

	// Détection du type de terminal avec le user_agent
	$terminal = UA_Detect($_SERVER['HTTP_USER_AGENT']);
	
	// Gestion dates
	setlocale(LC_TIME, 'fr_FR.UTF8','fra');
	$Year = strftime('%Y');
	if(isset($_GET['sem'])){
		$WeekNum = $_GET['sem'];
	} else {
		$WeekNum = strftime('%W');
	}
	$Week_start = new DateTime();
	$Week_start->setISODate($Year,$WeekNum);
	$DateSearch = new DateTime();
	$DateSearch->setISODate($Year,$WeekNum);
	$DateCompare = new DateTime();
	$DateCompare->setISODate($Year,$WeekNum);
	
	// Favion aléatoire
	$numFavicon = rand(1,14);
	
	// Connexion SQL
	sqlConnection();
	
	//
	// ##########	Gestion des logs	##########
	//
	// Sélection fichier à lire
	if(isset($_POST['cbboxlogs'])){
		$currentFile = $_POST['cbboxlogs'];
	}
	// Supprimer le fichier en cours de lecture
	if(isset($_POST['purge_log_selected'])){
		if(!empty($currentFile)){
			unlink('./logs/'.$currentFile.'.txt');
			unset($currentFile);
			header('Location: index.php?frame=logs');
		}
	}
	// Lecture de l'état mémorisé de $LockLogs
	$LockLogs = sqlReadLockLogs();
	// Activer ou non l'écriture des logs
	if(isset($_POST['on_off_logs'])){
		if (isset($LockLogs)){
			$LockLogs = !$LockLogs;
			sqlWriteLockLogs($LockLogs);
		} else {
			$msg = 'La variable $LockLogs est vide!';
		}
	}
	//
	// #########################################################	CONNEXION UTILISATEURS		#########################################################
	//
	// ###### Gestion connexion utilisateur ######
	//
	if(isset($_POST['connection'])){
		if(!empty($_POST['login']) && !empty($_POST['pwd'])){
			$_SESSION['user'] = strtoupper($_POST['login']);
			$pwd = strtoupper($_POST['pwd']);
			$userData = sqlConnectUser($_SESSION['user'],$pwd);
			if(!empty($userData['pword']) && $userData['pword'] == $pwd){
				if($userData['droits'] == 'admin'){
					$_SESSION['rights'] = 'admin';
					header('Location: index.php?frame=logs');
				} else if( $userData['droits'] == 'user+'){
					$_SESSION['rights'] = 'user+';
					header('Location: index.php');
				} else if ( $userData['droits'] == 'user'){
					$_SESSION['rights'] = 'user+';
				} else {
					// defaut
				}
				$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
				$_SESSION['connected'] = True;
				if(!isset($_SESSION['count'])){
						$_SESSION['count']= 0;
				} else {
					$_SESSION['count']++;
				}
				
				logFile('logSession','Start Connection N° '.$_SESSION['count'].' ; User : '.$_SESSION['user'].' Rights : '.$_SESSION['rights'].' ; Ip : '.$_SESSION['ip']);
			} else {
				$msg = '<p>Vous n\'avez pas de mot de passe de définit! Contacter l\'administrateur.</p>';
			}
		} else {
			$msg = '<p>Echèc de connexion ! vérifiez votre nom d\'utilisateur ou votre mot de passe.</p>';
		}
	}
	//
	// ###### Gestion déconnexion utilisateur ######
	//
	if(isset($_GET['log']) && $_GET['log'] = 'out') {
		logFile('logSession','End Connection N° '.$_SESSION['count'].' ; User : '.$_SESSION['user'].' Rights : '.$_SESSION['rights'].' ; Ip : '.$_SESSION['ip']);
		unset($_SESSION['user'],$_SESSION['rights'],$_SESSION['ip'],$_SESSION['count']);
		session_destroy();
		header('Location: index.php?frame=planning_vehicules&sem='.$WeekNum);
	}
	
	// #########################################################	PLANNING VEHICULES COMMUNS		#########################################################
	//
	// ###### réservations des véhicules ######
	// GET
	if(isset($_GET['day'])){
		$Date = DateTime::createFromFormat('D d-M-Y', $_GET['day']);
		$Date = $Date->format('Y-m-d');
		$DatePlus1 = $Date;
	}
	//
	if(isset($_GET['car'])){
		$carmodele = substr($_GET['car'],0,strlen($_GET['car'])-9);
		$carimmat = substr($_GET['car'],strlen($_GET['car'])-9);
	}
	//
	// if(isset($_GET['halfdayCars'])){
		// $halfdayCars = $_GET['halfdayCars'];
	// }
	// POST
	if (isset($_POST['BP_CreateEventCars'])) {
		if (!empty($_POST['cbboxusers']) && !empty($_POST['cbboxvoitures']) && !empty($_POST['Client']) && !empty($_POST['Ville']) && !empty($_POST['DatepickerStart']) && !empty($_POST['DatepickerEnd']) && !empty($_POST['cbbox_am_pm_start']) && !empty($_POST['cbbox_am_pm_end'])) {
			if(($_POST['DatepickerStart'] > $_POST['DatepickerEnd']) || ($_POST['DatepickerStart'] == $_POST['DatepickerEnd'] && $_POST['cbbox_am_pm_start'] == 'pm' && $_POST['cbbox_am_pm_end'] == 'am')){
				$msg = '<p>Erreur, la date de début doit être inférieure à la datre de fin de votre réservation.</p>';
			} else {
				$ifExistEventCarBdd = sqlEventCarsExist(substr($_POST['cbboxvoitures'],strlen($_POST['cbboxvoitures'])-9),substr($_POST['cbboxvoitures'],0,strlen($_POST['cbboxvoitures'])-9),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end']);
				if($ifExistEventCarBdd == true){
					$msg = '<h2 style="color: red"><center>Erreur, le véhicule "'.$_POST['cbboxvoitures'].'" est déjà réservé aux dates indiquées.</center></h2>';
				}else{
					// Fonction requête sql pour ajout de l'évènement
					sqlInsertEventsCars($_POST['cbboxusers'],substr($_POST['cbboxvoitures'],0,strlen($_POST['cbboxvoitures'])-9),substr($_POST['cbboxvoitures'],strlen($_POST['cbboxvoitures'])-9),ucfirst(strtolower($_POST['Client'])),ucfirst(strtolower($_POST['Ville'])),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['comment']);
					$msg = '<p>Envoi des données dans la table mysql<br/>'.$_POST['cbboxusers'].'/'.$_POST['cbboxvoitures'].'/'.ucfirst(strtolower($_POST['Client'])).'/'.ucfirst(strtolower($_POST['Ville'])).'/'.$_POST['DatepickerStart'].'/'.$_POST['DatepickerEnd'].'/'.$_POST['cbbox_am_pm_start'].'/'.$_POST['cbbox_am_pm_end'].'/'.$_POST['comment'].'</p>';
					//notifyBySms('+33698936837','Test envoie sms par Email en PHP');
					notifyByEmail($_POST['cbboxusers'],'Réservation de votre véhicule','|'.$_POST['cbboxvoitures'].'|'.$_POST['Client'].'|'.$_POST['Ville'].'|'.$_POST['DatepickerStart'].'|'.$_POST['cbbox_am_pm_start'].'|'.$_POST['DatepickerEnd'].'|'.$_POST['cbbox_am_pm_end']);
					header('Location: index.php?frame=planning_vehicules&sem='.$WeekNum);
				}
			}
		} else {
			$msg = '<p>Erreur, au moins un champ est vide! Pour la création de la réservation du véhicule.</p>';
		}
	}
	//
	// ###### modifications réservations des véhicules ######
	//
	// GET
	if(!empty($_GET['idsqlcar'])){
		$idsqlCars = $_GET['idsqlcar'];
		// Fonction requête sql pour l'évènement
		$modifyCars = sqlSelectEventsCarsById($idsqlCars);
	}
	// POST
	if (isset($_POST['BP_ModifyEventCars'])) {
		if (!empty($_POST['idvueCars']) && !empty($_POST['cbboxusers']) && !empty($_POST['cbboxvoitures']) && !empty($_POST['Client']) && !empty($_POST['Ville']) && !empty($_POST['DatepickerStart']) && !empty($_POST['DatepickerEnd']) && !empty($_POST['cbbox_am_pm_start']) && !empty($_POST['cbbox_am_pm_end'])) {
			$ifExistEventCarBdd = sqlEventCarsExist(substr($_POST['cbboxvoitures'],strlen($_POST['cbboxvoitures'])-9),substr($_POST['cbboxvoitures'],0,strlen($_POST['cbboxvoitures'])-9),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['idvueCars']);
			if($ifExistEventCarBdd == true){
				$msg = '<h2 style="color: red"><center>Erreur, le véhicule "'.$_POST['cbboxvoitures'].'" est déjà réservé aux dates indiquées.</center></h2>';
			}else{
				// Fonction requête sql pour mise à jour de l'évènement
				sqlUpdateEventsCars($_POST['idvueCars'],$_POST['cbboxusers'],substr($_POST['cbboxvoitures'],0,strlen($_POST['cbboxvoitures'])-9),substr($_POST['cbboxvoitures'],strlen($_POST['cbboxvoitures'])-9),ucfirst(strtolower($_POST['Client'])),ucfirst(strtolower($_POST['Ville'])),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['comment']);
				$msg = '<p>Mise à jour des données dans la table mysql<br/>'.$_POST['cbboxusers'].'/'.$_POST['cbboxvoitures'].'/'.ucfirst(strtolower($_POST['Client'])).'/'.ucfirst(strtolower($_POST['Ville'])).'/'.$_POST['DatepickerStart'].'/'.$_POST['DatepickerEnd'].'/'.$_POST['cbbox_am_pm_start'].'/'.$_POST['cbbox_am_pm_end'].'/'.$_POST['comment'].'</p>';
				notifyByEmail($_POST['cbboxusers'],'Modification de réservation de votre véhicule',$_POST['cbboxvoitures'].'|'.$_POST['Client'].'|'.$_POST['Ville']);
				header('Location: index.php?frame=planning_vehicules&sem='.$WeekNum);
			}
		} else {
			$msg = '<p>Erreur, au moins un champ est vide! idvueCars : '.$_POST['idvueCars'].' cbboxusers : '.$_POST['cbboxusers'].' cbboxvoitures : '.$_POST['cbboxvoitures'].' Client : '.ucfirst(strtolower($_POST['Client'])).' Ville : '.ucfirst(strtolower($_POST['Ville'])).' DatepickerStart : '.$_POST['DatepickerStart'].' DatepickerEnd : '.$_POST['DatepickerEnd'].' cbbox_am_pm_start : '.$_POST['cbbox_am_pm_start'].' cbbox_am_pm_end : '.$_POST['cbbox_am_pm_end'].' commentaire : '.$_POST['Commentaire'].'</p>';
		}
	}
	if (isset($_POST['BP_SupEventCars'])){
		sqlDeleteEventsCars($_POST['idvueCars']);
		$msg = '<p>Supression de l\'évènement dans la table mysql</p>';
		header('Location: index.php?frame=planning_vehicules&sem='.$WeekNum);
	}
	//
	// #########################################################	PLANNING APPAREILS COMMUNS		#########################################################
	//
	// ##########	Gestion réservations des appareils	##########
	// GET
	if(isset($_GET['device'])){
		$deviceInfos = explode(' ',$_GET['device']);
		$devicemodele = $deviceInfos[0];
		$deviceid = $deviceInfos[1];
	}
	if(isset($_GET['halfdayDevices'])){
		$halfdayDevices = $_GET['halfdayDevices'];
	}
	// POST
	if (isset($_POST['BP_CreateEventDevices'])) {
		if (!empty($_POST['cbboxusers']) && !empty($_POST['cbboxdevices']) && !empty($_POST['Client']) && !empty($_POST['Ville']) && !empty($_POST['DatepickerStart']) && !empty($_POST['DatepickerEnd']) && !empty($_POST['cbbox_am_pm_start']) && !empty($_POST['cbbox_am_pm_end'])) {
			if(($_POST['DatepickerStart'] > $_POST['DatepickerEnd']) || ($_POST['DatepickerStart'] == $_POST['DatepickerEnd'] && $_POST['cbbox_am_pm_start'] == 'pm' && $_POST['cbbox_am_pm_end'] == 'am')){
				$msg = '<p>Erreur, la date de début doit être inférieure à la datre de fin de votre réservation.</p>';
			} else {
				$ifExistEventDeviceBdd = sqlEventDevicesExist(substr($_POST['cbboxdevices'],0,stripos($_POST['cbboxdevices'],' ')+1),substr($_POST['cbboxdevices'],strrpos($_POST['cbboxdevices'],' ')+1),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end']);
				if($ifExistEventDeviceBdd == true){
					$msg = '<h2 style="color: red"><center>Erreur, l\'appareil "'.$_POST['cbboxdevices'].'" est déjà réservé aux dates indiquées.</center></h2>';
				}else{
					// Fonction requête sql pour ajout de l'évènement
					sqlInsertEventsDevices($_POST['cbboxusers'],substr($_POST['cbboxdevices'],0,stripos($_POST['cbboxdevices'],' ')+1),substr($_POST['cbboxdevices'],strrpos($_POST['cbboxdevices'],' ')+1),ucfirst(strtolower($_POST['Client'])),ucfirst(strtolower($_POST['Ville'])),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['comment']);
					$msg = '<p>Envoi des données dans la table mysql<br/>'.$_POST['cbboxusers'].'/'.$_POST['cbboxdevices'].'/'.ucfirst(strtolower($_POST['Client'])).'/'.ucfirst(strtolower($_POST['Ville'])).'/'.$_POST['DatepickerStart'].'/'.$_POST['DatepickerEnd'].'/'.$_POST['cbbox_am_pm_start'].'/'.$_POST['cbbox_am_pm_end'].'/'.$_POST['comment'].'</p>';
					//notifyBySms('+33698936837','Test envoie sms par Email en PHP');
					//notifyByEmail('mickael.lehoux@gmail.com','Test Email sur ajout évènement','Ceci est un test d\'envoi d\'email sur création d\'évènement');
					header('Location: index.php?frame=planning_devices&sem='.$WeekNum);
				}
			}
		} else {
			$msg = '<p>Erreur, au moins un champ est vide! Pour la création de la réservation de l\'appareil</p>';
		}
	}
	//
	// ##########	Gestion modifications réservations des Appareils	##########
	//
	// GET
	if(!empty($_GET['idsqldevice'])){
		$idsqldevice = $_GET['idsqldevice'];
		// Fonction requête sql pour l'évènement
		$modifyDevices = sqlSelectEventDevicesById($idsqldevice);
	}
	// POST
	if (isset($_POST['BP_ModifyEventDevices'])) {
		if (!empty($_POST['idvueDevices']) && !empty($_POST['cbboxusers']) && !empty($_POST['cbboxdevices']) && !empty($_POST['Client']) && !empty($_POST['Ville']) && !empty($_POST['DatepickerStart']) && !empty($_POST['DatepickerEnd']) && !empty($_POST['cbbox_am_pm_start']) && !empty($_POST['cbbox_am_pm_end'])) {
			$ifExistEventDeviceBdd = sqlEventDevicesExist(substr($_POST['cbboxdevices'],0,stripos($_POST['cbboxdevices'],' ')+1),substr($_POST['cbboxdevices'],strrpos($_POST['cbboxdevices'],' ')+1),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['idvueDevices']);
			if($ifExistEventDeviceBdd == true){
				$msg = '<h2 style="color: red"><center>Erreur, l\'appareil "'.$_POST['cbboxdevices'].'" est déjà réservé aux dates indiquées.</center></h2>';
			}else{
				// Fonction requête sql pour mise à jour de l'évènement
				sqlUpdateEventsDevices($_POST['idvueDevices'],$_POST['cbboxusers'],substr($_POST['cbboxdevices'],0,stripos($_POST['cbboxdevices'],' ')+1),substr($_POST['cbboxdevices'],strrpos($_POST['cbboxdevices'],' ')+1),ucfirst(strtolower($_POST['Client'])),ucfirst(strtolower($_POST['Ville'])),$_POST['DatepickerStart'],$_POST['DatepickerEnd'],$_POST['cbbox_am_pm_start'],$_POST['cbbox_am_pm_end'],$_POST['comment']);
				$msg = '<p>Mise à jour des données dans la table mysql<br/>'.$_POST['cbboxusers'].'/'.$_POST['cbboxdevices'].'/'.ucfirst(strtolower($_POST['Client'])).'/'.ucfirst(strtolower($_POST['Ville'])).'/'.$_POST['DatepickerStart'].'/'.$_POST['DatepickerEnd'].'/'.$_POST['cbbox_am_pm_start'].'/'.$_POST['cbbox_am_pm_end'].'/'.$_POST['comment'].'</p>';
				//$sendSucces = notifyByEmail('mickael.lehoux@gmail.com','Test Email sur modification d\'évènement','Ceci est un test d\'envoi d\'email sur modification d\'évènement');
				//if($sendSucces = true){
				//	$msg = 'Message envoyé';
				//}
				header('Location: index.php?frame=planning_devices&sem='.$WeekNum);
			}
		} else {
			$msg = '<p>Erreur, au moins un champ est vide! idvueDevices : '.$_POST['idvueDevices'].' cbboxusers : '.$_POST['cbboxusers'].' cbboxdevices : '.$_POST['cbboxdevices'].' Client : '.ucfirst(strtolower($_POST['Client'])).' Ville : '.ucfirst(strtolower($_POST['Ville'])).' DatepickerStart : '.$_POST['DatepickerStart'].' DatepickerEnd : '.$_POST['DatepickerEnd'].' cbbox_am_pm_start : '.$_POST['cbbox_am_pm_start'].' cbbox_am_pm_end : '.$_POST['cbbox_am_pm_end'].'/'.$_POST['comment'].'</p>';
		}
	}
	if (isset($_POST['BP_SupEventDevices'])){
		sqlDeleteEventsDevices($_POST['idvueDevices']);
		$msg = '<p>Supression de l\'évènement dans la table mysql</p>';
		header('Location: index.php?frame=planning_devices&sem='.$WeekNum);
	}
	//
	// #########################################################	HISTORIQUES VEHICULES		#########################################################
	//
	//
	// ##########	Gestion recherches dans l'historique Véhicules ##########
	//
	if(isset($_POST['BP_SearchCars'])){
		if(!empty($_POST['StartSearchCars']) || !empty($_POST['EndSearchCars']) || !empty($_POST['cbboxcarsearch']) || !empty($_POST['cbboxusersearchCars'])){
			if ($_POST['StartSearchCars'] > $_POST['EndSearchCars']){
				$msg = '<p>La date de début ne peut être supérieure à la date de fin de la recherche.</p>';
			} else {
				$historyCars = sqlSearchCarsEvents(!empty($_POST['StartSearchCars'])? $_POST['StartSearchCars'] : '', !empty($_POST['EndSearchCars']) ? $_POST['EndSearchCars'] : '' , !empty($_POST['cbboxcarsearch']) ? substr($_POST['cbboxcarsearch'],strlen($_POST['cbboxcarsearch'])-9) : '' , !empty($_POST['cbboxusersearchCars']) ? $_POST['cbboxusersearchCars'] : '' );
				if($historyCars == 'false'){
					$msg = '<p>Résultat de Recherche : Aucun résultat trouvé.</p>';
				}else{
					$msg = '<p>Résultat de Recherche : '.count($historyCars).' résultats trouvés</p>';
					$csvEventsCars = './extractions_historiques/'.date('Y_m_d').'_extraction_historique_vehicules.csv';
					if (File_exists($csvEventsCars)){
						unlink($csvEventsCars);
					}
					$FichierCars = fopen($csvEventsCars, 'a+');
					foreach($historyCars as $lines => $line){
						fputcsv($FichierCars,$line);
					}
					fclose($FichierCars);
				}
			}
		} else {
			$msg = '<p>Pour faire une requête dans l\'historique, vous devez avoir indiqué au moins un critère de sélection.</p>';
		}
	}
	//
	// ##########	Gestion recherches dans l'historique Appareils ##########
	//
	if(isset($_POST['BP_SearchDevices'])){
		if(!empty($_POST['StartSearchDevices']) || !empty($_POST['EndSearchDevices']) || !empty($_POST['cbboxdevicesearch']) || !empty($_POST['cbboxusersearchDevices'])){
			if ($_POST['StartSearchDevices'] > $_POST['EndSearchDevices']){
				$msg = '<p>La date de début ne peut être supérieure à la date de fin de la recherche.</p>';
			} else {
				$historyDevices = sqlSearchDevicesEvents(!empty($_POST['StartSearchDevices'])? $_POST['StartSearchDevices'] : '', !empty($_POST['EndSearchDevices']) ? $_POST['EndSearchDevices'] : '' , !empty($_POST['cbboxdevicesearch']) ? substr($_POST['cbboxdevicesearch'],strlen($_POST['cbboxdevicesearch'])-1) : '' , !empty($_POST['cbboxusersearchDevices']) ? $_POST['cbboxusersearchDevices'] : '' );
				if($historyDevices == 'false'){
					$msg = '<p>Résultat de Recherche : Aucun résultat trouvé.</p>';
				}else{
					$msg = '<p>Résultat de Recherche : '.count($historyDevices).' résultats trouvés</p>';
					$csvEventsDevices = './extractions_historiques/'.date('Y_m_d').'_extraction_historique_appareils.csv';
					if (File_exists($csvEventsDevices)){
						unlink($csvEventsDevices);
					} 
					$FichierDevices = fopen($csvEventsDevices, 'a+');
					foreach($historyDevices as $lines => $line){
						fputcsv($FichierDevices,$line);
					}
					fclose($FichierDevices);
				}
			}
		} else {
			$msg = '<p>Pour faire une requête dans l\'historique, vous devez avoir indiqué au moins un critère de sélection.</p>';
		}
	}
	//
	// #########################################################	ADMINISTRATION		#########################################################
	//
	//
	// ##########	Modifications / Création / Supression des Utilisateurs	##########
	//
	if(!empty($_POST['cbboxuser'])){
		$iduser = $_POST['cbboxuser'];
		$UserToModify = sqlSelectUsersByInitials($iduser);
	}
	// Modifier user
	if(isset($_POST['BP_ModifyUser'])){
		if(!empty($_POST['cbboxuser'])){
			if(!empty($_POST['name']) && !empty($_POST['firstname']) && !empty($_POST['initials']) && !empty($_POST['email']) && !empty($_POST['entity']) && !empty($_POST['rights'])){
				sqlUpdateUsers(ucfirst(strtolower($_POST['name'])),ucfirst(strtolower($_POST['firstname'])),strtoupper($_POST['initials']),$_POST['email'],$_POST['entity'],str_replace(' ','',$_POST['phone']),$_POST['rights'],strtoupper($_POST['login']),strtoupper($_POST['pwd']));
				$msg = '<p>Modification de l\'utilisateur "<a style="color: green">'.strtoupper($_POST['initials']).'</a>" effectuée</p>';
			} else {
				$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires.</p>';
			}
		} else {
			$msg = '<p>Impossible de modifier un utilisateur non sélectionné.</p>';
		}
	}
	// Suprimer user
	if(isset($_POST['BP_DeleteUser'])){
		if(!empty($_POST['initials'])){
			sqlDeleteUsers($_POST['initials']);
			$msg = '<p>Supression de l\'utilisateur "<a style="color: green">'.$_POST['initials'].'</a>" effectuée.</p>';
			header('Location: index.php?frame=manage_users&sem='.$WeekNum);
		} else {
			$msg = '<p>Sélectionnez un utilisateur avant de le suprimer.</p>';
		}
	}
	// Ajout user
	if(isset($_POST['BP_NewUser'])){
		if(empty($_POST['cbboxuser'])){
			if(!empty($_POST['name']) && !empty($_POST['firstname']) && !empty($_POST['initials']) && !empty($_POST['entity']) && !empty($_POST['rights'])){
				if(check_Alpha(strtoupper($_POST['initials']))){
					if ( strlen($_POST['initials']) > 2 && strlen($_POST['initials']) <5 ){
						sqlInsertUsers(ucfirst($_POST['name']),ucfirst($_POST['firstname']),strtoupper($_POST['initials']),strtoupper($_POST['initials']).'@'.$_POST['entity'].'fr',$_POST['entity'],str_replace(' ','',$_POST['phone']),strtolower($_POST['rights']),!empty($_POST['login'])? strtoupper($_POST['login']) : '',!empty($_POST['pword'])? strtoupper($_POST['pword']) : '');
						$msg = '<p>Création de l\'utilisateur "<a style="color: green">'.strtoupper($_POST['initials']).'</a>" effectuée."</p>';
					} else {
						$msg = '<p><a style="color: red">Les initiales d\'un utilisateur comporte au minimum 3 lettres et au maximum 4 lettres.</a></p>';
					}
				} else {
					$msg = '<p><a style="color: red">Les initiales d\'un utilisateur ne peuvent contenir de chiffre.</a></p>';
				}
			} else {
				$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires.</p>';
			}
		} else {
			$msg = '<p>Impossible d\'ajouter un utilisateur déjà existant.</p>';
		}
	}
	//
	// ##########	Modifications / Création / Supression des Véhicules	##########
	//
	if(!empty($_POST['cbboxcar'])){
		$idcar = $_POST['cbboxcar'];
		$immat = substr($idcar,strlen($idcar)-9);
		$CarToModify = sqlSelectCarsByImmat($immat);
	}

	// Modifier car
	if(isset($_POST['BP_ModifyCar'])){
		if(!empty($_POST['cbboxcar'])){
			if(!empty($_POST['marque']) && !empty($_POST['modele']) && !empty($_POST['immatriculation'])){
				sqlUpdateCars(ucfirst($_POST['marque']),ucfirst($_POST['modele']),strtoupper($_POST['immatriculation']));
				$msg = '<p>Modification du véhicule "<a style="color: green">'.ucfirst($_POST['marque']).' '.ucfirst($_POST['modele']).' '.strtoupper($_POST['immatriculation']).'</a>" effectuée.</p>';
			} else {
				$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires.</p>';
			}
		} else {
			$msg = '<p>Impossible de modifier un véhicule non sélectionné.</p>';
		}
	}
	// Suprimer car
	if(isset($_POST['BP_DeleteCar'])){
		if(!empty($_POST['immatriculation'])){
			sqlDeleteCars($_POST['immatriculation']);
			$msg = '<p>Supression du véhicule "<a style="color: green">'.$_POST['immatriculation'].'</a>" effectuée.</p>';
			header('Location: index.php?frame=manage_cars&sem='.$WeekNum);
		}
	}
	// Ajouter car
	//echo str_replace('-',' ',strtoupper(substr($_POST['immatriculation'],2,-2)));
	if(isset($_POST['BP_NewCar'])){
		if(!empty($_POST['marque']) && !empty($_POST['modele']) && !empty($_POST['immatriculation'])){
			if(strlen($_POST['immatriculation']) == 9){
				if(substr_count($_POST['immatriculation'],'-') == 2){
					if(check_Alpha(strtoupper(substr($_POST['immatriculation'],0,2))) && check_Num(str_replace('-','',substr($_POST['immatriculation'],2,-2))) && check_Alpha(strtoupper(substr($_POST['immatriculation'],-2)))){
						$returnInsertCar = sqlInsertCars(ucfirst($_POST['marque']),ucfirst($_POST['modele']),strtoupper($_POST['immatriculation']));
						if($returnInsertCar == true){
							$msg = '<p>Ajout du véhicule "<a style="color: green">'.ucfirst($_POST['marque']).' '.ucfirst($_POST['modele']).' '.strtoupper($_POST['immatriculation']).'</a>" effectuée.</p>';
						} else {
							$msg = '<p><a style="color: red">Erreur création véhicule</a></p>';
						}
					} else {
						$msg = '<p><a style="color: red">Vous avez inversé les lettres et les chiffres dans l\'immatriculation du véhicule.<br/>
						Le format attendu est AA-111-AA et non : '.strtoupper($_POST['immatriculation']).'
						</a></p>';
					}
				} else {
				$msg = '<p><a style="color: red">L\'immatriculation '.$_POST['immatriculation'].' du véhicule '.$_POST['marque'].' '.$_POST['modele'].' est mal renseignée. Il manque les "-".</a></p>';
				}
			} else {
				$msg = '<p><a style="color: red">L\'immatriculation du véhicule est mal renseignée, elle comporte '.strlen($_POST['immatriculation']).' caractères : '.$_POST['immatriculation'].'.<br/>
				Elle doit comporter 9 caractères du type AA-123-XX.</a></p>';
			}
		} else {
			$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires pour l\'ajout d\'un véhicule dans la base.</p>';
		}
	}
	//
	// ##########	Modifications / Création / Supression des Appareils	##########
	//
	if(!empty($_POST['cbboxdevice'])){
		$iddevice = $_POST['cbboxdevice'];
		$device = explode(' ',$iddevice);
		$DeviceToModify = sqlSelectDeviceById($device[2]);
	}
	// Modifier appareils
	if(isset($_POST['BP_ModifyDevice'])){
		if(!empty($_POST['cbboxdevice'])){
			if(!empty($_POST['marque']) && !empty($_POST['modele']) && !empty($_POST['identifiant'])){
				sqlUpdateDevices(ucfirst($_POST['marque']),ucfirst($_POST['modele']),strtoupper($_POST['identifiant']));
				$msg = '<p>Modification de l\'appareil "<a style="color: green">'.ucfirst($_POST['marque']).' '.ucfirst($_POST['modele']).' '.strtoupper($_POST['identifiant']).'</a>" effectuée.</p>';
			} else {
				$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires.</p>';
			}
		} else {
			$msg = '<p>Impossible de modifier un appareil non sélectionné.</p>';
		}
	}
	// Suprimer appareils
	if(isset($_POST['BP_DeleteDevice'])){
		if(!empty($_POST['identifiant'])){
			sqlDeleteDevices($_POST['identifiant']);
			$msg = '<p>Supression de l\'appareil "<a style="color: green">'.$_POST['identifiant'].'</a>" effectuée.</p>';
			header('Location: index.php?frame=manage_devices&sem='.$WeekNum);
		}
	}
	// Ajouter appareils
	if(isset($_POST['BP_NewDevice'])){
		if(empty($_POST['cbboxdevice'])){
			if(!empty($_POST['marque']) && !empty($_POST['modele']) && !empty($_POST['identifiant'])){
				if(strlen($_POST['identifiant']) < 7 || strlen($_POST['identifiant']) > 8 ) {
					$msg = '<p><a style="color: red">L\'identifiant d\'un appareil doit comporter 7 à 8 caractères.<br/>
						vous avez entré : <br/>
						'.strtoupper($_POST['identifiant']).'<br/>
						Le format attendu est : "CORAPP01"<br/>
						COR : pour Clemessy Orléans.<br/>
						APP : pour la désignation de l\'appareil en 2 ou 3 lettres.<br/>
						01 : pour la numérotation de l\'appareil. Sur deux chiffres uniquement.
					</a></p>';
				} else if(substr(strtoupper($_POST['identifiant']),0,3) == 'COR'){
					if(check_Alpha(substr($_POST['identifiant'],3,-2)) && check_Num(substr($_POST['identifiant'],-2))) {
							sqlInsertDevices(ucfirst($_POST['marque']),ucfirst($_POST['modele']),strtoupper($_POST['identifiant']));
							$msg = '<p>Ajout de l\'appareil "<a style="color: green">'.ucfirst($_POST['marque']).' '.ucfirst($_POST['modele']).' '.strtoupper($_POST['identifiant']).'</a>" effectuée.</p>';
					} else {
						$msg = '<p><a style="color: red">L\'identifiant de l\'appareil être de la forme suivante : COR + "Designation 2 ou 3 lettres" + "2 chiffres" </a></p>';
					}
				} else {
					$msg = '<p><a style="color: red">L\'identifiant de l\'appareil doit commencer par "COR"</a></p>';
				}
			} else {
				$msg = '<p>Les champs comportant un <a style="color: red">*</a> sont obligatoires.</p>';
			}
		}
	}
	//
	// #########################################################	Drag & Drop		#########################################################
	//
	if(isset($_GET['triggerDragDropCar'])){
		if (!empty($_GET['dateDropEvent']) && !empty($_GET['idDragEvent'])  && isset($_GET['ligneDropEvent'])) {
			$DragEvent=sqlSelectEventsCarsById($_GET['idDragEvent']);
			$cars=sqlSelectCars();
			$DateDrop = DateTime::createFromFormat('D d-M-Y', $_GET['dateDropEvent']);
			$DateEndDrop = date_add(date_create($DateDrop->format('Y-m-d')),date_diff(date_create($DragEvent['date_start']),date_create($DragEvent['date_end'])));
			$ifExistEventCarBdd = sqlEventCarsExist($cars[$_GET['ligneDropEvent']]['immatriculation'],$cars[$_GET['ligneDropEvent']]['modele'],$DateDrop->format('Y-m-d'),$DateEndDrop->format('Y-m-d'),$DragEvent['am_pm_start'],$DragEvent['am_pm_end'],$DragEvent['id_events']);
			if($ifExistEventCarBdd == true){
				$msg = '<h2 style="color: red"><center>Erreur, le véhicule '.$cars[$_GET['ligneDropEvent']]['immatriculation'].' est déjà réservé aux dates indiquées.</center></h2>';
			}else{
				// Fonction requête sql pour mise à jour de l'évènement
				sqlUpdateEventsCars($DragEvent['id_events'],$DragEvent['utilisateur'],$cars[$_GET['ligneDropEvent']]['modele'],$cars[$_GET['ligneDropEvent']]['immatriculation'],$DragEvent['client'],$DragEvent['ville'],$DateDrop->format('Y-m-d'),$DateEndDrop->format('Y-m-d'),$DragEvent['am_pm_start'],$DragEvent['am_pm_end'],$DragEvent['commentaire']);
				header('Location: index.php?frame=planning_vehicules&sem='.$WeekNum);
			}
		}
	}
	if(isset($_GET['triggerDragDropDevices'])){
		if (!empty($_GET['dateDropEvent']) && !empty($_GET['idDragEvent'])  && isset($_GET['ligneDropEvent'])) {
			$DragEvent=sqlSelectEventsDevicesById($_GET['idDragEvent']);
			$Devices=sqlSelectDevices();
			$DateDrop = DateTime::createFromFormat('D d-M-Y', $_GET['dateDropEvent']);
			$DateEndDrop = date_add(date_create($DateDrop->format('Y-m-d')),date_diff(date_create($DragEvent['date_start']),date_create($DragEvent['date_end'])));
			$ifExistEventDeviceBdd = sqlEventDevicesExist($Devices[$_GET['ligneDropEvent']]['identifiant'],$Devices[$_GET['ligneDropEvent']]['modele'],$DateDrop->format('Y-m-d'),$DateEndDrop->format('Y-m-d'),$DragEvent['am_pm_start'],$DragEvent['am_pm_end'],$DragEvent['id_events']);
			if($ifExistEventDeviceBdd == true){
				$msg = '<h2 style="color: red"><center>Erreur, l\'appareil '.$Devices[$_GET['ligneDropEvent']]['identifiant'].' est déjà réservé aux dates indiquées.</center></h2>';
			}else{
				// Fonction requête sql pour mise à jour de l'évènement
				sqlUpdateEventsDevices($DragEvent['id_events'],$DragEvent['utilisateur'],$Devices[$_GET['ligneDropEvent']]['modele'],$Devices[$_GET['ligneDropEvent']]['identifiant'],$DragEvent['client'],$DragEvent['ville'],$DateDrop->format('Y-m-d'),$DateEndDrop->format('Y-m-d'),$DragEvent['am_pm_start'],$DragEvent['am_pm_end'],$DragEvent['commentaire']);
				header('Location: index.php?frame=planning_devices&sem='.$WeekNum);
			}
		}
	}
	
	//
	// #########################################################	CARNETS DE BORD		#########################################################
	//
	if (!empty($_GET['v'])){
		$v = $_GET['v'];
		$vcall = sp_CHECK_DATAS_CB($v);
	}
	
	if(isset($_POST['valider'])){
		if(isset($_POST['newIndexKM'],$_POST['projectNum'])){
			$newIndexKM = $_POST['newIndexKM'];
			$projectNum = $_POST['projectNum'];
			$saveLastIndexKM = $_POST['saveLastIndexKM'];
			$saveIdEvent = $_POST['saveIdEvent'];
			$saveImmat = $_POST['saveImmat'];
			echo $newIndexKM;
			echo $projectNum;
			echo $saveLastIndexKM;
			sp_INSERT_DATAS_CB($newIndexKM,$projectNum,$saveIdEvent,$saveLastIndexKM,$saveImmat);
		}
		
	}
	
	//
	// #########################################################	NAVIGATION		#########################################################
	//
	
	//if ($terminal =='pc'){
	if (true){	
		// Version PC
		//
		// Vue réservation véhicule
		if(isset($_GET['frame']) && $_GET['frame'] == 'create_event_car' ){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			// Fonction requête sql pour la liste des clients
			$list_customers = sqlSelectCustomers();
			// Fonction requête sql pour la liste des villes
			$list_cities = sqlSelectCities();
			$cbboxcarselected = $carmodele.' '.$carimmat;
			$vue = 'manage_events.php';
			$headerTitle = 'Réservation d\'un véhicule';
			$Boutons = '<button hidden name="startvalue" value="'.$Date.'"></button><button hidden name="endvalue" value="'.$DatePlus1.'"></button><button class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" id="BP_CreateEventCars" name="BP_CreateEventCars" disabled>Valider</button>';
		} else if 
		// Vue modification réservation véhicule
			(isset($_GET['frame']) && $_GET['frame'] == 'modify_event_car' ){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			// Fonction requête sql pour la liste des clients
			$list_customers = sqlSelectCustomers();
			// Fonction requête sql pour la liste des villes
			$list_cities = sqlSelectCities();
			$cbboxcarselected = $modifyCars['vehicule_modele'].' '.$modifyCars['vehicule_immat'];
			$Date = $modifyCars['date_start'];
			$DatePlus1 = $modifyCars['date_end'];
			$Client = $modifyCars['client'];
			$Ville = $modifyCars['ville'];
			$vue = 'manage_events.php';
			$headerTitle = 'Modification d\'un évènement';					
			$Boutons= '<button class="ui-widget ui-state-default ui-corner-all" id="BP_ModifyEventCars" name="BP_ModifyEventCars">Modifier</button><button class="ui-widget ui-state-default ui-corner-all" id="BP_SupEventCars" name="BP_SupEventCars">Suprimer</button>';
		} else if 
		// Vue réservation appareil
			(isset($_GET['frame']) && $_GET['frame'] == 'create_event_device' ){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des clients
			$list_customers = sqlSelectCustomers();
			// Fonction requête sql pour la liste des villes
			$list_cities = sqlSelectCities();
			// Fonction requête sql pour la liste des appareils
			$list_devices = sqlSelectDevices();
			$cbboxdeviceselected = $devicemodele.' '.$deviceid;
			$vue = 'manage_events_devices.php';
			$headerTitle = 'Création d\'un évènement';
			$Boutons = '<button hidden name="startvalue" value="'.$Date.'"></button><button hidden name="endvalue" value="'.$DatePlus1.'"></button><button class="ui-widget ui-state-default ui-corner-all ui-state-disabled" id="BP_CreateEventDevices" name="BP_CreateEventDevices" disabled>Valider</button>';
			} else if
		// Vue modification réservation appareil
			(isset($_GET['frame']) && $_GET['frame'] == 'modify_event_device' ){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des clients
			$list_customers = sqlSelectCustomers();
			// Fonction requête sql pour la liste des villes
			$list_cities = sqlSelectCities();
			// Fonction requête sql pour la liste des appareils
			$list_devices = sqlSelectDevices();
			$cbboxdeviceselected  = $modifyDevices['appareil_modele'].' '.$modifyDevices['appareil_id'];
			$Date = $modifyDevices['date_start'];
			$DatePlus1 = $modifyDevices['date_end'];
			$Client = $modifyDevices['client'];
			$Ville = $modifyDevices['ville'];
			$vue = 'manage_events_devices.php';
			$headerTitle = 'Modification d\'un évènement';
			$Boutons= '<button class="ui-widget ui-state-default ui-corner-all" id="BP_ModifyEventDevices" name="BP_ModifyEventDevices">Modifier</button><button class="ui-widget ui-state-default ui-corner-all" id="BP_SupEventDevices" name="BP_SupEventDevices">Suprimer</button>';
		} else if 
		// Vue Gestion utilisateurs
			(isset($_GET['frame']) && $_GET['frame'] == 'manage_users' && $droits != 'user' && $droits != 'empty'){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			$vue = 'manage_users.php';
			$headerTitle = 'Création ou Modification d\'un utilisateur';			
		} else if 
		// Vue Gestion vehicules
			(isset($_GET['frame']) && $_GET['frame'] == 'manage_cars' && $droits != 'user'  && $droits != 'empty'){
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			$vue = 'manage_cars.php';
			$headerTitle = 'Création ou Modification d\'un véhicule';
		}else if 
		// Vue Gestion vehicules
			(isset($_GET['frame']) && $_GET['frame'] == 'manage_devices' && $droits != 'user'  && $droits != 'empty'){
			// Fonction requête sql pour la liste des vehicules
			$list_devices = sqlSelectDevices();
			$vue = 'manage_devices.php';
			$headerTitle = 'Création ou Modification d\'un appareil';
		} else if
		// Vue Gestion historique véhicules communs
			(isset($_GET['frame']) && $_GET['frame'] == 'historique_cars' && $droits != 'user'  && $droits != 'empty'){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			$vue = 'historique_cars.php';
			$headerTitle = 'Historiques des évènements<br/>des véhicules communs';
		} else if 
		// Vue Gestion historique appareils communs
			(isset($_GET['frame']) && $_GET['frame'] == 'historique_devices' && $droits != 'user'  && $droits != 'empty'){
			// Fonction requête sql pour la liste des utilisateurs
			$list_users = sqlSelectUsers();
			// Fonction requête sql pour la liste des vehicules
			$list_devices = sqlSelectDevices();
			$vue = 'historique_devices.php';
			$headerTitle = 'Historiques des évènements<br/>des appareils communs';
		} else if 
		// Vue planning véhicules communs
			(isset($_GET['frame']) && $_GET['frame'] == 'connection' ){
			$vue = 'connection.php';
			$headerTitle = 'Connectez-vous pour accéder à<br/> l\'interface d\'administration.';
		} else if 
		// Vue planning véhicules communs
			(isset($_GET['frame']) && $_GET['frame'] == 'planning_vehicules' ){
			// Fonction requête sql pour la liste des évènements
			$list_eventsday = sqlSelectEventsCars($Week_start->format('Y-m-d'),date('Y-m-d', strtotime($Week_start->format('Y-m-d').'+8 days')));
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			$vue = 'planning_vehicules.php';
			$headerTitle = 'Planning véhicules communs';
		} else if 
		// Vue planning véhicules communs 2 TEST PAUL
			(isset($_GET['frame']) && $_GET['frame'] == 'planning_vehicules2' ){
			// Fonction requête sql pour la liste des évènements
			$list_eventsday = sqlSelectEventsCars($Week_start->format('Y-m-d'),date('Y-m-d', strtotime($Week_start->format('Y-m-d').'+8 days')));
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			$vue = 'planning_vehicules2.php';
			$headerTitle = 'Planning véhicules communs';
		} else if 
		// Vue planning appareils communs
			(isset($_GET['frame']) && $_GET['frame'] == 'planning_devices' ){
			// Fonction requête sql pour la liste des utilisateurs
			$list_eventsDevices = sqlSelectEventsDevices($Week_start->format('Y-m-d'),date('Y-m-d', strtotime($Week_start->format('Y-m-d').'+8 days')));
			// Fonction requête sql pour la liste des vehicules
			$list_devices = sqlSelectDevices();
			$vue = 'planning_devices.php';
			$headerTitle = 'Planning appareils communs';
		} else if 
		// Vue logs
			(isset($_GET['frame']) && $_GET['frame'] == 'logs' && $_SESSION['connected'] ){
			// Liste des fichiers de logs
			$list_logs = scandir('./logs');
			$vue = 'logs.php';
			$headerTitle = 'Fichiers Logs';
		} else if 
		// Vue carnet de bord
			(isset($_GET['frame']) && $_GET['frame'] == 'cb'){
			$vue = 'cb.php';
			$headerTitle = 'Carnet de bord';
		} else {
		// Par défaut Vue planning
			// Fonction requête sql pour la liste des évènements
			$list_eventsday = sqlSelectEventsCars($Week_start->format('Y-m-d'),date('Y-m-d', strtotime($Week_start->format('Y-m-d').'+8 days')));
			// Fonction requête sql pour la liste des vehicules
			$list_cars = sqlSelectCars();
			$vue = 'planning_vehicules.php';
			$headerTitle = 'Planning véhicules communs';
		}
	} elseif ($terminal == 'mobile' ){
		$vue ='vuemobile.php';
		$headerTitle = 'Interface mobile';
	} else {
		echo 'Terminal '.$terminal.' non pris en charge, page non chargée';
	}
	include('content.php');
	// Fermeture connexion mysql 
	mysql_close();
?>


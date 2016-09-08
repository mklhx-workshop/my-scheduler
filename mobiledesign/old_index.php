<?php
	include('../modele.php');
	// Connexion SQL
	sqlConnection();
	// Gestion dates
	setlocale(LC_TIME, 'fr_FR.UTF8');
	$Year = date('Y');
	if(isset($_GET['sem'])){
		$WeekNum = $_GET['sem'];
	} else {
		$WeekNum = date('W');
	}
	$Week_start = new DateTime();
	$Week_start->setISODate($Year,$WeekNum);
	$DateSearch = new DateTime();
	$DateSearch->setISODate($Year,$WeekNum);
	$DateCompare = new DateTime();
	$DateCompare->setISODate($Year,$WeekNum);
	$list_cars = sqlSelectCars();
	$list_users = sqlSelectUsers();
	$list_devices = sqlSelectDevices();
	$list_eventsCars = sqlSelectEventsCars('10-02-2016','14-02-2016');
	$list_eventsDevices = sqlSelectEventsDevices('10-02-2016','14-02-2016');
	include('vuemobile.php');
	// Fermeture connexion mysql 
	mysql_close();
?>
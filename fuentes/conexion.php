<?php
	//CONEXIÓN A LA BASE DE DATOS
	
	$local = true;
	
	$host = "localhost";
	$database = "programas";
	
	$user = "programas";
	$password = "TMtrj9rS5di";
	if ($local) {
		$user = "root";
		$password = "";
	}
	
	$mysqli = new mysqli($host, $user, $password, $database);
	$mysqli->set_charset("utf8");
	
	if ($mysqli->errno) {
		printf("Unable to connect to the database:<br /> %s", $mysqli->error);
		exit();
	}
?>

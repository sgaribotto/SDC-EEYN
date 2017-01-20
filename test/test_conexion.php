
<?php
	
	include '../fuentes/conexion.php';
	
	echo "<u>TEST CONEXION</u> <br />";
	//Mostrame las tablas para saber que está conectado y que tablas hay
	$query = "SHOW TABLES";
	
	//$query = "SELECT * FROM actas LIMIT 5;";
	
	$result = $mysqli->query($query);
	
	echo "CONEXION A MYSQL .... ";
	if ($mysqli->connect_errno) {
		printf("Unable to connect to the database:<br /> %s", $mysqli->connect_error);
		exit();
	} else {
		echo "OK <br />";
	}
	
	echo "PING MYSQL .... ";
	if ($mysqli->ping) {
		printf("Unable to connect to the database:<br /> %s", $mysqli->connect_error);
		exit();
	} else {
		echo "OK <br />";
	}
	
	echo "ESTADO MYSQL .... ";
	if ($mysqli->errno) {
		print_r($mysqli->error);
		echo "<br />";
	} else {
		echo "OK <br />";
	}
	
	
	
	
	$result->free();
	$mysqli->close();
	
	$error = $error2;
	
?>

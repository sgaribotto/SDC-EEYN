<?php
	$file = "contenidos_minimos.txt";
	
	$file = fopen($file, 'r');
	require './fuentes/conexion.php';
	
	while ($line = fgets($file)) {
		$line = explode("\t" , $line);
		
		$query = "UPDATE materia SET contenidosminimos = '$line[1]' WHERE cod = '$line[0]' ";
		
		$mysqli->query($query);
		echo $query;
		
	}
	
	fclose($file);
?>
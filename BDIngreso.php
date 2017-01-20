<?php
	//VALIDACIÓN DEL USUARIO

	//Autoload de la clase.
	require 'programas.autoloader.php';
	
	include './fuentes/dbbackuper.php';
	
	//Inicio la validación
	$validar = new clases\Validar_log_in_mysql();
	
	//Tomo el usuario y la password del POST (SANEADO EN LA CLASE)
	$validar->validarIngreso($_POST['usuario'], $_POST['password']);

	//Cerrar la conexión con la base de datos
	$validar->conexion->close();
?>

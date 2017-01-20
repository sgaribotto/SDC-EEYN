<?php
//VALIDACIONES DE USUARIO Y MATERIAS ETC.


//Validacines en el cuerpo
function validacionesHabitualesParaMeta() {
	if (! strpos(strtolower($_SERVER['PHP_SELF']), '/portada.php')) {
		
		validarExisteUsuario(); //Siempre debe ir primero porque inicia la sesión
		if (! strpos(strtolower($_SERVER['PHP_SELF']), '/cambiarclave.php')) {
			validarSeleccionMateria();
		}
		
	}
}

//Selección de materias
function validarSeleccionMateria() {
	if (! strpos(strtolower($_SERVER['PHP_SELF']), '/seleccionarmateria.php') and !$_SESSION['permiso']) {
		
		if (! isset($_SESSION['materia'])) {
			print_r($_SESSION);
			echo '<meta http-equiv="refresh" content="0;url=seleccionarmateria.php">';
		}
	}
}

//Sesión iniciada
function validarExisteUsuario() { //Deja la sesión iniciada
	session_start(); 
	
	if (! isset($_SESSION['usuario'])) {
		echo '<meta http-equiv="refresh" content="0;url=portada.php?Error=Debe iniciar sesión.">';
	} 
}



?>

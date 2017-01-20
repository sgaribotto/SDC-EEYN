<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Características Generales</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="./css/general.css">
		<link rel="stylesheet" type="text/css" href="./css/icons.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		
	</head>
	
	<body>
		
		<?php
			require_once('./fuentes/botonera.php');
			//require_once("./fuentes/panelNav.php");
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Características generales</h2>
			<p class="normal">Con el fin de unificar, centralizar y mantener constantemente actualizados los planes de estudio de las materias, la Secretaría Académica 
			 pone a disposición de los docentes el siguiente software web para la carga de programas con las siguientes características:</p>
			<div id="formulario">
				<ul class="">
					<li class="listHeaders">Propósito</li>
						<ul class="">
						<li class="listItems">Carga de la siguiente información: </li>
							<ul class="">
								<li class="listItems">Equipo docente</li>
								<li class="listItems">Objetivos</li>
								<li class="listItems">Enfoque metodológico</li>
								<li class="listItems">Unidades temáticas</li>
								<li class="listItems">Métodos de evaluación y criterios de aprobación</li>
								<li class="listItems">Bibliografía</li>
								<li class="listItems">Plan de clases</li>
							</ol>
						<li class="listItems">Vista previa de programas</li>
						<li class="listItems">Versión imprimible en pdf del programa</li>
						</ul>
					<li class="listHeaders">Ventajas</li>
						<ul class="">
							<li class="listItems">Centralización de la información</li>
							<li class="listItems">Unificación de formatos</li>
							<li class="listItems">Almacenamiento de información histórica y comparación inmediata</li>
							<li class="listItems">Actualización constante</li>
						</ul>
					<li class="listHeaders">Otras ventajas</li>
						<ul class="">
							<li class="listItems">Fácil y agradable interfaz para el usuario</li>
							<li class="listItems">Acceso externo seguro</li>
							<li class="listItems">Resguardo seguro de la información</li>
						</ul>
						
				</ul>
			</div>
			
		</div>
		
		<div class="navLateral">
			<h2 class="navLateral">Regresar al inicio</h2>
			<p class="navLateral">
				<a href="portada.php">Regresar</a>
			</p>
		</div>
	</body>
</html>

<!DOCTYPE html>
<html>
	<head>
		
		<title></title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once('./fuentes/botonera.php');
		?>
		<style>
			#videoAyuda {
				margin:auto;
				display:inline-block;
			}
		</style>
	</head>
	
	<body>
		
		
		<!--<div class="ayudaLateral">
			<h2 class="ayudaLateral">Ayuda</h2>
			<p class="ayudaLateral">En esta sección te daremos ayuda y consejos acerca de cómo completar el programa de la asignatura. 
				En esta primera pantalla te ofrecemos un tutorial en video que te mostrará rápidamente todos los pasos a seguir hasta completar y enviar el programa.
				<br />
				A cada paso se irá guardando el progreso. Si necesitaras interrumpir la carga del programa, podrás continuar la próxima vez desde donde dejaste.
			</p>
		</div>-->
		
		<?php
			require("./fuentes/panelNav.php");
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Video Tutorial</h2>
			<p class="subtitulo formularioLateral">El siguiente tutorial en video te orientará para completar el programa de la asignatura</p>
			<div clas="formularioLateral" id="videoAyuda">
				<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/KnCHQBsTKic" frameborder="0" allowfullscreen></iframe>-->
				<!--<video width="400" controls>
				  <source src="./fuentes/pruebaProgramas.mpg" type="video/mp4">
				  <source src="./fuentes/pruebaProgramas.asf" type="video/asf">
				  Your browser does not support HTML5 video.
				</video>-->
				<iframe width="560" height="315" src="https://www.youtube.com/embed/Qvo89_SCmLE" frameborder="0" allowfullscreen></iframe>
			</div>
			
			<a href="datosgenerales.php" class="btnNav">
				<div class="btnNav">
					<p class="btnNav">Empezar a cargar el programa</p>
				</div>
			</a>
			
		</div>
		
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="./fuentes/funciones.js"></script>
	<script>
		ajustarAlturaLaterales($("div.formularioLateral"), $("div.navLateral"));
	</script>
</html>
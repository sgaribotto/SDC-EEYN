<!-- TAREAS 
	- Doble verificación del ingreso.
	- Subir el video definitivo.
	- Revisar la secuencia de guardado.
	
	
-->
<!DOCTYPE html>
<html>
	<head>
		
		<title> Programa de la asignatura </title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.js"></script>
		<script src="./fuentes/funciones.js"></script>
		<?php 
			require_once('./fuentes/meta.html');
			
		?>
	</head>
	
	<body>
		<?php require_once('./fuentes/botonera.php'); ?>
		
		<div class="ingreso">
			<fieldset class="ingreso">
				<legend class="ingreso">Ingrese Usuario y Contraseña</legend>
				
				<form method="post" action="BDIngreso.php">
					<!--<label class="ingreso" for="usuario">Usuario</label>-->
					<input class="ingreso iconUser" name="usuario" type="text" placeholder="Usuario" value="" >
					
					<!--<label class="ingreso" for="password">Contraseña</label>-->
					<input class="ingreso iconPassword" name="password" type="password" placeholder="Contraseña" value="">
					
					<button type="submit" class="ingreso iconIngreso">Ingreso</button>
					
					<p class="error" id="errorIngreso"><?php if (isset($_GET['Error'])) { echo $_GET['Error']; } ?></p>
					
				</form>
				
             </fieldset>
		</div>
		
		<div class="textoLateral" style="text-align:center;">
		<h2 class="textoLateral">Video Tutorial</h2>
			<p class="subtitulo textoLateral">El siguiente tutorial en video te orientará para completar el programa de la asignatura</p>
			<div class="" id="videoAyuda">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/mIuewjBzy7k" frameborder="0" allowfullscreen></iframe>
			</div>
			<p class="textoLateral">Si tiene problemas para loguearse, por favor escribanos a <a href="mailto:weeyn@unsam.edu.ar?Subject=Carga%20de%20programas" class="mail" target="_top">webmaster.eeyn@unsam.edu.ar</a></p>
		</div class="textoLateral">
		
	</body>
</html>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Datos Generales</title>
		<?php 
			require_once('./fuentes/meta.html');
			
		?>
		
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
			<h2 class="formularioLateral">Cambiar contraseña</h2>
			<div id="formulario">
				<fieldset class="formularioLateral">
				
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=cambiarClave">
					
						<label for="nombre" class="formularioLateral">Contraseña actual: </label>
						<input class="formularioLateral iconPassword" type="password" name="claveactual" pattern="[A-Za-z0-9]{4,}" placeholder="Contraseña actual" required title="4 o más caracteres alfanuméricos. Puede incluir mayúsculas o minúsculas.">
						<br />
						<label for="nombre" class="formularioLateral">Contraseña nueva: </label>
						<input class="formularioLateral iconPassword" type="password" name="clavenueva" pattern="[A-Za-z0-9]{4,}" placeholder="Contraseña nueva" required title="4 o más caracteres alfanuméricos. Puede incluir mayúsculas o minúsculas.">
						<br />
						<label for="nombre" class="formularioLateral">Repetir contraseña nueva: </label>
						<input class="formularioLateral iconPassword" type="password" name="clavenueva2" pattern="[A-Za-z0-9]{4,}" placeholder="Contraseña nueva" required title="4 o más caracteres alfanuméricos. Puede incluir mayúsculas o minúsculas.">
						<br />
						
						
						
						<button tpye="submit" class="formularioLateral iconContinuar">Continuar</button>
					</form>
				</fieldset>
			</div>
			
		</div>
		
		<div class="navLateral">
			<h2 class="navLateral">Seleccione la materia</h2>
			<p class="navLateral">
				Elija una contraseña segura con 4 o más caracteres alfanuméricos. Puede incluir mayúsculas o minúsculas.
			</p>
		</div>
	</body>
</html>
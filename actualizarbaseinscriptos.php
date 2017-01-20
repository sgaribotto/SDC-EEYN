<!DOCTYPE html>
<html>
	<head>
		
		<title>Actualizar la base de inscriptos</title>
		<?php 
			require_once('./fuentes/meta.html');
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		
	</head>
	
	<body>

		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
			require "./fuentes/constantes.php";
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Actualizar la base de inscriptos</h2>
			<!--<div id="mostrarFormulario">Mostrar Formulario</div>-->
			<div id="formulari">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="actualizarbaseinscriptos.php" id="formularioCarga" enctype="multipart/form-data">
						
						<label class="formularioLateral" for="materia">Seleccionar la base: </label>
						<input type="file" class="formularioLateral" name="base" id="base" accept=".txt" required="required" />
						<br />
						
						
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Actualizar</button>
					</form>
				</fieldset>
			</div>
			<hr>
			<div class="estado">
			<?php
				
				if (isset($_FILES['base'])) {
					$targetDir = "/var/www/soporte/inscriptos/";
					$targetFile = $targetDir . basename($_FILES['base']['name']);
					
					//echo $_FILES['base']['error'];
					//echo $targetFile;
					
					/*if (is_dir($targetDir)) {
						echo "dir";
					} else {
						echo "no dir";
					}
					
					if (is_writable($targetDir)) {
						echo "escribo";
					} else {
						echo "Nos se puede escribir";
					}*/
					
					
					 if (move_uploaded_file($_FILES['base']['tmp_name'], $targetFile)) {
						echo "Se ha cargado ". basename( $_FILES["base"]["name"]);
					
					
						require "conexion.php";
						
						$query = "LOAD DATA INFILE '$targetFile'
									REPLACE
									INTO TABLE inscriptos
									IGNORE 1 LINES";
						$mysqli->query($query);
						echo $mysqli->error;
						
						$mysqli->close();
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				}
				
			?>
			</div>
			<div class="instrucciones">
				<h3 class="formularioLateral">Instrucciones</h3>
				
				<ol class="formularioLateral instrucciones">
					<li class="formularioLateral instrucciones">Descargue del SIU guaraní el reporte de inscriptos por materia del cuatrimestre que quiere actualizar</li>
					<li class="formularioLateral instrucciones">Guardelo como TXT y nombrelo año-cuatrimestre (por ejemplo 2015-1.TXT)</li>
					<li class="formularioLateral instrucciones">Pulse el botón examinar y elija el archivo descargado del SIU</li>
					<li class="formularioLateral instrucciones">Pulse el botón actualizar y espere (podría tardar unos minutos)</li>
					<li class="formularioLateral instrucciones">Recibirá un mensaje indicando el éxito de la operación, si recibe un mensaje de error, comunicarse con el webmaster</li>
				</ol>
			</div>
		</div>
		
		
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			
			
			/*$('#formularioCarga').submit( function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values += "&act=agregarAula";
				
				$.get("./fuentes/AJAX.php", values, function(data) {
					location.reload();
				});
			});*/
			
			
			/*$('.botonEliminar').click(function() {
				id = $(this).data('id');
				$.post("./fuentes/AJAX.php?act=eliminarAula", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});*/
			
		});
	</script>
</html>

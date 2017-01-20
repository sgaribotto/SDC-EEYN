<!DOCTYPE html>
<html>
	<head>
		
		<title>Evaluación y criterios de aprobación</title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once 'programas.autoloader.php';
			include './fuentes/constantes.php';
			$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
			$campos = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);
		?>
	</head>
	
	<body>
		
		<?php
			require("./fuentes/panelNav.php");
			require_once('./fuentes/botonera.php');
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Evaluación y criterios de aprobación</h2>
			<a href="http://guarani.unsam.edu.ar/eyn_alumnos/acceso/descargar_archivo?archivo=PROM.pdf">Normativa de promoción</a>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=evaluacion">
						
						<textarea class="formularioLateral" name="evaluacion" placeholder="Evaluación"><?php $detalle = (isset($campos['evaluacion'])) ? $campos['evaluacion'] : ""; echo $detalle; ?></textarea>
						
						<button type="submit" class="formularioLateral iconGuardar">Guardar y continuar</button>
					</form>
					
				</fieldset>
			</div>
		</div>
		
	</body>
</html>

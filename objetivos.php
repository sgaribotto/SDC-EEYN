<!DOCTYPE html>
<html>
	<head>
		
		<title>Objetivos</title>
		<?php 
			require_once('./fuentes/meta.html');
			
			require 'programas.autoloader.php';
			
			include './fuentes/constantes.php';
			$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
			$campos = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);
		?>
	</head>
	
	<body>
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Objetivos</h2>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=objetivos">
						<textarea class="formularioLateral" name="objetivos" placeholder="Objetivos"><?php $detalle = (isset($campos['objetivos'])) ? $campos['objetivos'] : ""; echo $detalle; ?></textarea>
						<button type="submit" class="formularioLateral iconGuardar">Guardar y continuar</button>
					</form>
					
				</fieldset>
			</div>
		</div>
		
	</body>
</html>

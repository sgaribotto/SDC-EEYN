<!DOCTYPE html>
<html>
	<head>
		
		<title>Enfoque metodológico</title>
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
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Enfoque metodológico</h2>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=fundamentacion">
						<textarea class="formularioLateral" name="fundamentacion" placeholder="Enfoque metodológico"><?php $detalle = (isset($campos['fundamentacion'])) ? $campos['fundamentacion'] : ""; echo $detalle; ?></textarea>
						<button type="submit" class="formularioLateral iconGuardar">Guardar y continuar</button>
					</form>
					
				</fieldset>
			</div>
		</div>
		
	</body>
</html>

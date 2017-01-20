<html>
	<head>
		
		<title>Contenidos mínimos</title>
		<?php 
			require_once './fuentes/meta.html';
			
		?>
		
	</head>
	
	<body>
		<?php 
			require_once './fuentes/botonera.php';
			require "./fuentes/panelNav.php";
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Contenidos mínimos</h2>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=contenidos">
					
						<h3 class="formularioLateral">Contenidos mínimos <img src="./images/icons/info.png" alt="Info" title="Si encuentra errores en está información, por favor informe a weeyn@unsam.edu.ar" height="20px" style="cursor:help;margin-left:10px;"></h3>
						<?php
							
							require('./conexion.php');
							
							$query = "SELECT contenidosminimos FROM materia WHERE cod = '$_SESSION[materia]' ";
							
							$result = $mysqli->query($query);
							
							$row = $result->fetch_array(MYSQLI_ASSOC);
							
							echo "<p class='formularioLateral contenidosMinimos'>$row[contenidosminimos]</p>";
							
							$result->free();
							$mysqli->close();
						?>
						<button type="submit" class="formularioLateral iconGuardar">Continuar</button>
					</form>
					
				</fieldset>
			</div>
		</div>
		
	</body>
	
</html>

<!DOCTYPE html>
<html>
	<head>
		
		<title>Datos Generales</title>
		<?php 
			require_once('./fuentes/meta.html');
			
			
			include 'programas.autoloader.php';
			
			if ($_SESSION['permiso'] == [7]) {
				echo "<script>location.assign('verprogramas.php')</script>";
			}
			
			if ($_SESSION['permiso'] == [8]) {
				echo "<script>location.assign('consultacomisiones.php')</script>";
			}
			
		?>
		
	</head>
	
	<body>
		
		<?php
			require_once('./fuentes/botonera.php');
			require_once('./fuentes/panelNav.php');
			
			
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Seleccionar Materia</h2>
			<div>
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=seleccionarMateria" id="formSeleccionarMateria">
						<div class="radioSet">
							<?php
								
								
								require_once('./fuentes/conexion.php');
								
								
								$query = "SELECT r.materia, m.nombre, m.cod, m.id FROM responsable as r 
												INNER JOIN materia AS m
												ON r.materia = m.cod							
												WHERE r.usuario = '$_SESSION[id]' AND r.activo = 1 ";
								
								$result = $mysqli->query($query);
								if ($result->num_rows == 0) {
									echo "<p class='sinResultados'>El docente no está asignado como responsable de ninguna materia</p>";
									
								} else {
									while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
										echo "<input type='radio' name='materia' value='$row[cod] - $row[nombre]' required='required' id='$row[cod]'><label for='$row[cod]' class='radioSet'>$row[cod] <br />$row[nombre]</label>";
									}
								}
								
								$result->free();
								$mysqli->close();
								
							?>
						</div>
						
						<!--<button type="submit" class="formularioLateral iconContinuar">Continuar</button>-->
					</form>
				</fieldset>
			</div>
		</div>
			
		
	</body>
	<script>
		$(document).ready(function() {
			
			$('div.radioSet').buttonset();
			
			$('input[type="radio"]').click(function() {
				$('#formSeleccionarMateria').submit();
			});
			
			$('h2.navLateral').eq(0).click();
			
		});
	</script>
	
</html>

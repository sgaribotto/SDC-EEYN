<!DOCTYPE html>
<html>
	<head>
		
		<title>Inscriptos</title>
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
			<h2 class="formularioLateral">Inscriptos</h2>
			<!--<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="#" id="formularioCarga">
						
						<label class="formularioLateral" for="materia">Materia: </label>
						<select name="materia" class="formularioLateral iconMateria"  required="required" id="materia">
							<?php
								/*require './conexion.php';
								
								$query = "SELECT MAX(cod) AS codigo, GROUP_CONCAT(DISTINCT nombre SEPARATOR ' / ') AS nombre, conjunto 
											FROM materia
											WHERE activo = 1
											GROUP BY conjunto
											ORDER BY cuatrimestre, cod";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQL_ASSOC)) {
									echo "<option class='formularioLateral' value='$row[codigo]'>$row[conjunto] - $row[nombre]</option>";
								}
								
								$result->free();
								$mysqli->close();*/
							?>
						</select>
						<br />
						<label class="formularioLateral" for="dia">Día:</label>
						<select class="formularioLateral iconTurno" name="dia" required="required" id="dia">
							<option class="formularioLateral" value="lunes">Lunes</option>
							<option class="formularioLateral" value="martes">Martes</option>
							<option class="formularioLateral" value="miercoles">Miércoles</option>
							<option class="formularioLateral" value="jueves">Jueves</option>
							<option class="formularioLateral" value="viernes">Viernes</option>
							<option class="formularioLateral" value="sabado">Sábado</option>
						</select>
						<br />
						<label class="formularioLateral" for="turno">Turno:</label>
						<select class="formularioLateral iconTurno" name="turno" required="required" id="turno">
							<option class="formularioLateral" value="M">M - 8:30 a 12:30</option>
							<option class="formularioLateral" value="M1">M1 - 8:30 a 10:30</option>
							<option class="formularioLateral" value="M2">M2 - 10:30 a 12:30</option>
							<option class="formularioLateral" value="N">N - 18:30 a 22:30</option>
							<option class="formularioLateral" value="N1">N1 - 18:30 a 20:30</option>
							<option class="formularioLateral" value="N2">N2 - 20:30 a 22:30</option>
							<option class="formularioLateral" value="T">T - 14 a 18</option>
							<option class="formularioLateral" value="T1">T1 - 14 a 16</option>
							<option class="formularioLateral" value="T2">T2 - 16 a 18</option>
						</select>
						<br />
						
						<label class="formularioLateral" for="observaciones">Observaciones: </label>
						<textarea name="observaciones" class="formularioLateral"   id="observaciones" style="height:40px;"></textarea>
						
						
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
					</form>
				</fieldset>
			</div>-->
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:15%;">Cod</th>
						<th class="plantelActual" style="width:50%;">Materia</th>
						<th class="plantelActual" style="width:10%;">Turno</th>
						<th class="plantelActual" style="width:10%;">Inscriptos</th>
						<!--<th class="plantelActual" style="width:5%;">Eliminar</th>-->
					</tr>
					<?php
						require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 20;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(cantidad) FROM vista_inscriptos_por_conjunto");
													
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT conjunto, nombre_materia, turno, cantidad
									FROM vista_inscriptos_por_conjunto
									WHERE anio_academico = $ANIO AND periodo_lectivo = $CUATRIMESTRE
									ORDER BY turno, materia
									LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						echo $mysqli->error;
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
							echo "<tr class='formularioLateral plantelActual'>";
							
							foreach ($row as $key => $value) {
								if ($key != 'id') {
									echo "<td class='formularioLateral plantelActual'>$value</td>";
								}
							}
							
							//echo "<td class='formularioLaterial eliminarEnTabla'><button type='button' class='formularioLateral botonEliminar' id='eliminarDocente' data-id='$row[id]'>X</button>";
							echo "</tr>";
						}
						
						$result->free();
						$mysqli->close();

					?>
				</table>
				<ul class="linkPagina">
				<?php
					if ($totalPaginas > 1) {
						for ($i = 0; $i < $totalPaginas; $i++) {
							$url = $_SERVER['PHP_SELF'] . "?pagina=" . ($i + 1);
							echo "<li class='linkPagina'>
										<a href = $url>" . ($i + 1) . "</a>
									</li>";
							
						}
					}
				?>
				</ul>
			</div>
			
		</div>
		
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			
			$('#formularioCarga').submit( function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values += "&act=agregarTurno";
				
				$.get("./fuentes/AJAX.php", values, function(data) {
					location.reload();
				});
			});
			
			
			$('.botonEliminar').click(function() {
				id = $(this).data('id');
				$.post("./fuentes/AJAX.php?act=eliminarTurno", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>

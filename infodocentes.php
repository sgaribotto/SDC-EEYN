<!DOCTYPE html>
<html>
	<head>
		
		<title>Información Docentes</title>
		<?php 
			require_once('./fuentes/meta.html');
			include './fuentes/constantes.php';
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
			if (!isset($_SESSION['cuatrimestreTemporal'])) {
				$_SESSION['cuatrimestreTemporal'] = $ANIO . ' - ' . $CUATRIMESTRE;
			}
			
			$periodo = explode(' - ', $_SESSION['cuatrimestreTemporal']);
			$ANIO = $periodo[0];
			$CUATRIMESTRE = $periodo[1];
		?>
	</head>
	<body>
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="programaCompleto">
			<h1 class="programaCompleto">Información del plantel docente</h1>
					
				<label for="periodo" class="formularioLateral">Periodo lectivo</label>
				<select name="periodo" class="formularioLateral iconCalendario"  id="periodo" style="width:150px;">
					<?php 
						require "./conexion.php";
						$query = "SELECT DISTINCT anio, cuatrimestre FROM afectacion ORDER by anio DESC, cuatrimestre DESC" ;
						$result = $mysqli->query($query);
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							echo "<option value='$row[anio] - $row[cuatrimestre]' selected>$row[anio] - $row[cuatrimestre]</option>";
						}
						
						$result->free();
						$mysqli->close();
					?>
						
				</select>
				
				
					
				<?php 
					require './fuentes/conexion.php';
					$query = "SELECT c.nombre, c.cod,
								SUM(IF(tipoafectacion = 'titular', 1, 0)) AS Titular,
								SUM(IF(tipoafectacion = 'Asociado', 1, 0)) AS Asociado,
								SUM(IF(tipoafectacion = 'Adjunto', 1, 0)) AS Adjunto,
								SUM(IF(tipoafectacion = 'JTP', 1, 0)) AS JTP,
								SUM(IF(tipoafectacion = 'Ayudante Graduado', 1, 0)) AS 'Ayudante Graduado',
								SUM(IF(tipoafectacion = 'Ayudante Alumno', 1, 0)) AS 'Ayudante Alumno',
								SUM(IF(tipoafectacion = 'otro', 1, 0)) AS Otro
							FROM afectacion AS a
							JOIN materia AS m ON a.materia = m.cod
							JOIN carrera AS c ON m.carrera = c.id
							WHERE a.anio = " . $ANIO . " AND a.cuatrimestre = " . $CUATRIMESTRE . " AND a.activo = 1
							GROUP BY m.carrera";
							
					$result = $mysqli->query($query);
					$datosTabla = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$datosTabla[$row['cod']] = $row;
					}
					
					$encabezadoTabla = array_keys($datosTabla);
					
				?>
				
				<table class="formularioLateral tablaInfo">
					<thead class='formularioLateral tablaInfo'>
						<tr class="formularioLateral tablaInfo blackie">
							<th class='formularioLateral tablaInfo blackie'>Cargo | Carrera</th>
							<?php 
								$TotalColumna = array();
								foreach($encabezadoTabla as $codCarrera) {
									echo "<th class='formularioLateral tablaInfo blackie'>$codCarrera</th>";
									$totalColumna[$codCarrera] = 0;
								}
							?>
							<th class='formularioLateral tablaInfo blackie'>TOTAL</th>
						</tr>
					</thead>
					<tbody class='formularioLateral tablaInfo'>
						<?php
							
							foreach($datosTabla['EYN-3'] as $campo => $valor) {
								$totalLinea = 0;
								echo "<tr class='formularioLateral tablaInfo'>";
									if (!in_array($campo, ['cod', 'nombre'])) {
										echo "<th class='formularioLateral tablaInfo'>$campo</th>";
										foreach ($encabezadoTabla as $codCarrera) {
											
											$cantidad = 0;
											if (isset($datosTabla[$codCarrera][$campo])) {
												$cantidad = $datosTabla[$codCarrera][$campo];
												$totalLinea += $cantidad;
												$totalColumna[$codCarrera] += $cantidad;
											}
											
											echo "<td class='formularioLateral tablaInfo'>$cantidad</td>";
										}
										echo "<td class='formularioLateral tablaInfo'>$totalLinea</td>";
									}
									
								
								echo "</tr>";
							}
						?>	
						
							<tr class="formularioLateral tablaInfo blackie">
								<th class="formularioLateral tablaInfo blackie" style="background-color:#222;">TOTAL</th>
								<?php
									foreach($encabezadoTabla as $codCarrera) {
										echo "<td class='formularioLateral tablaInfo blackie'>$totalColumna[$codCarrera]</td>";
									}
									echo "<td class='formularioLateral tablaInfo blackie'>" . array_sum($totalColumna) . "</td>";
										
								?>
							</tr>
						
					</tbody>
					
				</table>

				
			</div>
		</body>
			
			<script src="./fuentes/funciones.js"></script>
			
			<script>
				$(document).ready(function() {
					
					$("#accordion").accordion({
						collapsible:true,
						heightStyle:'content'
						
					});
					
					$('#materia').change(function() {
						materia = $('#materia').val();
						periodo = $('#periodo').val();
						$.post("./fuentes/AJAX.php?act=traerProgramaMateria", {"materia":materia, "periodo": periodo, }, function(data) {
							location.reload();
						});
					});
				
				});
			</script>
	
	
</html>

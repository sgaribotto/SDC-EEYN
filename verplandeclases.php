<!DOCTYPE html>
<html>
	<head>
		
		<title>Programa</title>
		<?php 
			require_once('./fuentes/meta.html');
			include './fuentes/constantes.php';
			require_once 'programas.autoloader.php';
			if (!isset($_SESSION['materiaTemporal'])) {
				$_SESSION['materiaTemporal'] = '1005';
			}
			if (!isset($_SESSION['cuatrimestreTemporal'])) {
				$_SESSION['cuatrimestreTemporal'] = $ANIO . ' - ' . $CUATRIMESTRE;
			}
			
			$periodo = explode(' - ', $_SESSION['cuatrimestreTemporal']);
			$ANIO = $periodo[0];
			$CUATRIMESTRE = $periodo[1];
			
			$materia = new clases\Materia($_SESSION['materiaTemporal']);
			$cronograma = $materia->mostrarCronograma($ANIO, $CUATRIMESTRE);
			/*$programa = new Programa($_SESSION['materiaTemporal'], $_SESSION['id']);
			$camposPrograma = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);*/
			
		?>
		
	</head>
	
	<body>
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="programaCompleto">
			<h1 class="programaCompleto">Plan de estudios <?php echo $materia->datosMateria['nombre']; ?></h1>
				<label for="materia" class="formularioLateral">Materia: </label>
				<select name="materia" class="formularioLateral iconMateria" id="materia">
					<option value="">Seleccione la materia</option>
					<?php 
						require "./conexion.php";
						$query = "SELECT id, cod, nombre FROM materia WHERE activo = 1
							ORDER BY cod + 0";
						$result = $mysqli->query($query);
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							echo "<option value='$row[cod]'>$row[cod] - $row[nombre]</option>";
						}
						
						$result->free();
						$mysqli->close();
					?>
						
				</select>
				<br />				
				<label for="periodo" class="formularioLateral">Periodo lectivo: </label>
				<select name="periodo" class="formularioLateral iconCalendario"  id="periodo" style="width:150px;">
					<?php 
						require "./conexion.php";
						$query = "SELECT DISTINCT anio, cuatrimestre FROM programa ORDER by anio, cuatrimestre" ;
						$result = $mysqli->query($query);
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							echo "<option value='$row[anio] - $row[cuatrimestre]' selected>$row[anio] - $row[cuatrimestre]</option>";
						}
						
						$result->free();
						$mysqli->close();
					?>
						
				</select>
				
				<div id="plantelActual">
				
					<?php
						require "./conexion.php";
						if (empty($cronograma)) {
							echo "<tr><td colspan='5'>No hay clases cargadas</td></tr>";
						} else {
						
							foreach ($cronograma as $key => $value ) {
								$query = "SELECT id, valor, tipo 
											FROM agregados_cronograma
											WHERE materia = $_SESSION[materia] AND clase = $value[clase] AND anio = $ANIO AND cuatrimestre = $CUATRIMESTRE AND activo = 1";
								$result = $mysqli->query($query);
								
								$agregadosClase = array();
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
									$agregadosClase[$row['tipo']][] = json_decode($row['valor']);
								}
							
								$fecha = new DateTime($value['fecha']);
								$pasivo = 100 - $value['activo'];
								echo "<h3 class='formularioLateral'>Clase $value[clase] - Fecha: {$fecha->format('d-m')} </h3>";
								echo "<div class='intraTab'>
											<ul>
											<li><a href='#tabs-1'>Unidad temática</a></li>
											<li><a href='#tabs-2'>Descripción</a></li>
											<li><a href='#tabs-3'>Método</a></li>
											<li><a href='#tabs-4'>Bibliografía</a></li>
											<li><a href='#tabs-5'>Docentes</a></li>
										  </ul>";
											
								echo "		<div class='' id='tabs-1'>
												<p class='contenido'>Unidad $value[unidadtematica]</p>
												<p class='contenido'>$value[detalleunidadtematica]</p>
											</div>";
											
								echo "	<div class='' id='tabs-2'>
												<p class='contenido'>$value[descripcion]</p>
											</div>";
								
								//MÉTODOS DE DICTADO			
								echo "	<div class=''id='tabs-3'>";
								if (isset($agregadosClase['metodo'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:55%;'>Método</th>
													<th class='agregadosCronograma' style='width:15%;'>Activo</th>
													<th class='agregadosCronograma' style='width:15%;'>Pasivo</th>
													<th class='agregadosCronograma' style='width:15%;'>% Clase</th>
												</tr>
											</thead>
											<tbody>";
										$totalClase = 0;
										$totalActivo = 0;
										foreach ($agregadosClase['metodo'] as $object) {
											echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'>$object->metodo</td>
													<td class='agregadosCronograma'>$object->activo %</td>
													<td class='agregadosCronograma'>" . (100 - $object->activo) . " %</td>
													<td class='agregadosCronograma'>$object->porcentajeClase %</td>
												</tr>";
											$totalClase += $object->porcentajeClase;
											$totalActivo += ($object->activo * $object->porcentajeClase * .01);
										}										
									echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'><b>TOTALES</b></td>
													<td class='agregadosCronograma'><b>$totalActivo %</b></td>
													<td class='agregadosCronograma'><b>" . (100 - $totalActivo) . " %</b></td>
													<td class='agregadosCronograma'><b>$totalClase %</b></td>
												</tr>";
									echo "</tbody></table>";
								}
								echo "</div>";
								
								//BIBLIOGRAFÍA
								echo "<div class='' data-clase='$value[clase]' id='tabs-4'>";
								if (isset($agregadosClase['bibliografia'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:80%;'>Título</th>
													<th class='agregadosCronograma' style='width:20%;'>Páginas</th>
												</tr>
											</thead>
											<tbody>";
										$totalPaginas = 0;	
										
										foreach ($agregadosClase['bibliografia'] as $object) {
											echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'>" . str_replace("/ ", "<br />", $object->titulo) . " </td> 
													<td class='agregadosCronograma'>" . $object->paginas . "</td>
												</tr>";
											$totalPaginas += $object->paginas;
										}
										
										echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'><b>TOTAL PÁGINAS</b></td> 
													<td class='agregadosCronograma'><b>" . $totalPaginas . "</b></td>
												</tr>";
										
									echo "</tbody></table>";
								}
								echo"</div>";
								
								//EQUIPO DOCENTE
								echo "<div class='' data-clase='$value[clase]' id='tabs-5'>";
								if (isset($agregadosClase['docente'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:80%;'>Docente</th>
													<th class='agregadosCronograma' style='width:20%;'>Horas de clase</th>
												</tr>
											</thead>
											<tbody>";
									foreach ($agregadosClase['docente'] as $object) {
										echo "<tr class='agregadosCronograma'>
												<td class='agregadosCronograma'>" . str_replace("/ ", "<br />", $object->docente) . "</td>
												<td class='agregadosCronograma'>" . $object->horasClase . "</td>
											</tr>";
									}
									echo "</tbody></table>";
								}
								echo "</div>";
									
								echo	"</div>";
							}
						}
							

					?>
				
			</div>
			
			<script src="./fuentes/funciones.js"></script>
			
			<script>
				$(document).ready(function() {
					
					$('#plantelActual').accordion({
						collapsible:true,
						heightStyle:'content',
					});
					
					$('.intraTab').tabs();
					
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

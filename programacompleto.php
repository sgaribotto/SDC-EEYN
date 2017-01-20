<!DOCTYPE html>
<html>
	<head>
		
		<title>Programa</title>
		<?php 
			require_once('./fuentes/meta.html');
			require './fuentes/constantes.php';
			
			require 'programas.autoloader.php';
			
			$materia = new clases\Materia($_SESSION['materia']);
			
			$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
			$camposPrograma = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);
			
		?>
		
	</head>
	
	<body>
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="programaCompleto">
			<h1 class="programaCompleto">Plan de estudios <?php echo $materia->datosMateria['nombre']; ?></h1>
				<div id="accordion">
					<h2 class="programaCompleto">Datos Generales</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto">Nombre: <?php echo $materia->datosMateria['nombre']; ?></p>
						<p class="programaCompleto">Carrera: <?php echo $materia->datosMateria['carrera']; ?></p>
						<p class="programaCompleto">Plan: <?php echo $materia->datosMateria['plan']; ?></p>
						<p class="programaCompleto">Año: <?php echo round($materia->datosMateria['cuatrimestre'] / 2); ?></p>
						<p class="programaCompleto">Cuatrimestre: <?php echo (($materia->datosMateria['cuatrimestre'] % 2) == 1) ? 1 : 2; ?></p>
					</div>
					
					<h2 class="programaCompleto">Correlatividades</h2>
					<div class="detalleProgramaCompleto">
						<table id="correlatividades" class="formularioLateral correlatividadesTable" style="width:100%;">
									<tr class="formularioLateral correlatividadesTable" style="border-bottom:1px solid black;">
										<th class="formularioLateral correlatividadesTable" style="width:170px;border-bottom:1px solid #CCC;">Código</th>
										<th class="formularioLateral correlatividadesTable" style="border-bottom:1px solid #CCC;">Materia</th>
									</tr>
									<?php
										require('./conexion.php');
										
										
										$query = "SELECT m.cod, m.nombre FROM correlatividad AS c
														INNER JOIN materia AS m
														ON m.cod = c.requisito
														WHERE c.materia = '{$_SESSION['materia']}' ";
																		
										$result = $mysqli->query($query);
										
										if ($result->num_rows == 0) {
											echo "<tr><td colspan='2'>No hay correlatividades cargadas</td></tr>";
										} else {
										
											while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
												echo "<tr class='formularioLateral correlatividadesTable'>
															<td class='formularioLateral correlatividadesTable'>$row[cod]</td>
															<td class='formularioLateral correlatividadesTable'>$row[nombre]</td>
															</tr>";
											}
										}
										
										$result->free();
										$mysqli->close();
									?>
								</table>
					</div>
					
					<h2 class="programaCompleto">Contenidos mínimos</h2>
					<div class="detalleProgramaCompleto">
						<?php
								
								require('./conexion.php');
								
								$query = "SELECT contenidosminimos FROM materia WHERE cod = '$_SESSION[materia]' ";
								$result = $mysqli->query($query);
								
								$row = $result->fetch_array(MYSQLI_ASSOC);
								
								echo "<p class='formularioLateral contenidosMinimos'>$row[contenidosminimos]</p>";
								
								$result->free();
								$mysqli->close();
							?>
					</div>
					
					<h2 class="programaCompleto">Equipo docente</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:40%;">Docente</th>
							<th class="plantelActual" style="width:20%;">Cargo</th>
							<!--<th class="plantelActual" style="width:20%;">Carácter</th>-->
							<th class="plantelActual" style="width:20%;">Fecha de Ingreso</th>
							<th class="plantelActual" style="width:20%;">Estado</th>
						</tr>
						<?php
							
							
							//session_start();
							/*$query = "SELECT CONCAT_WS(', ', doc.apellido, doc.nombres) AS docente, a.tipoafectacion, doc.fechaingreso, a.estado 
												FROM docente AS doc
												INNER JOIN afectacion AS a
												ON doc.id = a.docente
												WHERE a.anio = 2015 AND a.cuatrimestre = 1 AND doc.activo = 1 AND a.materia = '$_SESSION[materia]' ";
							
							
							$result = $mysqli->query($query);*/
							//$materia = new Materia($_SESSION['materia']);
							$equipoDocente = $materia->mostrarEquipoDocente('*', $ANIO, $CUATRIMESTRE);
							
							if (empty($equipoDocente)) {
								echo "<tr><td colspan='2'>No hay docentes cargados</td></tr>";
							} else {
							
								foreach ($equipoDocente as $row) {
									echo "<tr class='formularioLateral correlatividadesTable'>
												<td class='formularioLateral correlatividadesTable'>$row[docente]</td>
												<td class='formularioLateral correlatividadesTable'>$row[tipoafectacion]</td>
												<td class='formularioLateral correlatividadesTable'>$row[fechaingreso]</td>
												</tr>";
												/*<td class='formularioLateral correlatividadesTable'>$row[estado]</td>
												</tr>";*/
								}
								
								
							}
							
							
							
						?>
					</table>
					</div>
					
					<h2 class="programaCompleto">Objetivos</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto"><?php $detalle = (isset($camposPrograma['objetivos'])) ? $camposPrograma['objetivos'] : ""; echo $detalle; ?></p>
					</div>
												
					<h2 class="programaCompleto">Enfoque metodológico</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto"><?php $detalle = (isset($camposPrograma['fundamentacion'])) ? $camposPrograma['fundamentacion'] : ""; echo $detalle; ?></p>
					</div>
					
					<h2 class="programaCompleto">Unidades temáticas</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:15%;">Unidad</th>
							<th class="plantelActual" style="width:80%;">Descripción</th>
							<!--<th class="plantelActual" style="width:20%;">Carácter</th>
							<th class="plantelActual" style="width:20%;">Fecha de Ingreso</th>
							<th class="plantelActual" style="width:20%;">Estado</th>-->
						</tr>
						<?php
							
							$unidadesTematicas = $materia->mostrarUnidadesTematicas("*", $ANIO, $CUATRIMESTRE);
							
							if (empty($unidadesTematicas)) {
								echo "<tr><td colspan='2'>No hay unidades cargadas</td></tr>";
							} else {
							
								foreach ($unidadesTematicas as $key => $value ) {
									echo "<tr class='formularioLateral plantelActual'>
												<td class='formularioLateral plantelActual'>$key</td>
												<td class='formularioLateral plantelActual'>$value</td>
												
												</tr>";
								
								}
							}
								

						?>
					</table>
					</div>
					
					<h2 class="programaCompleto">Evaluación y criterios de aprobación</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto"><?php $detalle = (isset($camposPrograma['evaluacion'])) ? $camposPrograma['evaluacion'] : ""; echo $detalle; ?></p>
					</div>
					
					<h2 class="programaCompleto">Bibliografía</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:40%;">Título</th>
							<th class="plantelActual" style="width:30%;">Autor</th>
							<th class="plantelActual" style="width:20%;">Editorial</th>
							<th class="plantelActual" style="width:10%;">Páginas</th>
							<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
						</tr>
						<?php
							
							
							
							
							$bibliografia = $materia->mostrarBibliografia($ANIO, $CUATRIMESTRE);
							
							if (empty($bibliografia)) {
								echo "<tr><td colspan='2'>No hay bibliografía cargadas</td></tr>";
							} else {
							
								foreach ($bibliografia as $key => $value ) {
									echo "<tr class='formularioLateral plantelActual'>
												<td class='formularioLateral plantelActual'>$value[titulo]</td>
												<td class='formularioLateral plantelActual'>$value[autor]</td>
												<td class='formularioLateral plantelActual'>$value[editorial]</td>
												<td class='formularioLateral plantelActual'>$value[paginas]</td>
												</tr>";
								
								}
							}
								

						?>
					</table>
					</div>
				</div>
			</div>
		</body>
			
			<script src="./fuentes/funciones.js"></script>
			
			<script>
				$(document).ready(function() {
					
					$("#accordion").accordion({
						collapsible:true,
						heightStyle:'content'
						
					});
					
				
				});
			</script>
	
	
</html>

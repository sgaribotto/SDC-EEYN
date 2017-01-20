<!DOCTYPE html>
<html>
	<head>
		
		<title>Docentes</title>
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
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Asignación de aulas</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario1">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente" id="formulario_asignacion">
						<label class="formularioLateral" for="aula">Aula:</label>
						<select class="formularioLateral iconCod" name="aula" required="required" id="aula"/>
							<?php
								require './fuentes/conexion.php';
								
								$query = "SELECT id, cod, capacidad FROM aulas WHERE activo = 1";
								$result = $mysqli->query($query);
								
								while($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option value='$row[id]'>$row[cod] ($row[capacidad] Alumnos)</option>";
								}
								
								$result->free();
							?>
						</select>
						<br />
						<label class="formularioLateral" for="conjunto">Materia:</label>
						<select class="formularioLateral iconCod" name="conjunto" required="required" id="conjunto"/>
							<?php
								require './fuentes/conexion.php';
								
								$query = "SELECT DISTINCT conjunto, nombre FROM materia 
											ORDER BY carrera, plan, conjunto ";
								$result = $mysqli->query($query);
								
								while($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option value='$row[conjunto]'>$row[conjunto] $row[nombre]</option>";
								}
								
								$result->free();
							?>
						</select>
						<br />
						<label class="formularioLateral" for="cantidad">Cantidad: </label>
						<input name="cantidad" class="formularioLateral iconNombre"  required="required" id="cantidad" type="number" min="0" max="100" >
						<br />
						<label for='dia' class='formularioLateral'>Día:</label>
						<select id="dia" class='formularioLateral' name='dia'>
							<option class='formularioLateral' value='lunes'>Lunes</option>
							<option class='formularioLateral' value='martes'>Martes</option>
							<option class='formularioLateral' value='miércoles'>Miércoles</option>
							<option class='formularioLateral' value='jueves'>Jueves</option>
							<option class='formularioLateral' value='viernes'>Viernes</option>
							<option class='formularioLateral' value='sábado'>Sábado</option>
						</select>
						<br />
						<label for='turno' class='formularioLateral'>Turno:</label>
						<select id='turno' class='formularioLateral' name='turno'>
							<option class='formularioLateral' value='M'>M</option>
							<option class='formularioLateral' value='M1'>M1</option>
							<option class='formularioLateral' value='M2'>M2</option>
							<option class='formularioLateral' value='T'>T</option>
							<option class='formularioLateral' value='T1'>T1</option>
							<option class='formularioLateral' value='T2'>T2</option>
							<option class='formularioLateral' value='N'>N</option>
							<option class='formularioLateral' value='N1'>N1</option>
							<option class='formularioLateral' value='N2'>N2</option>
							<option class='formularioLateral' value='S'>S</option>
						</select>
						<br />
						<label for='comision' class='formularioLateral'>Comisión:</label>
						<select id='comision' class='formularioLateral' name='comision'>
							<option class='formularioLateral' value='A'>A</option>
							<option class='formularioLateral' value='B'>B</option>
							<option class='formularioLateral' value='C'>C/option>
							<option class='formularioLateral' value='D'>D</option>
							<option class='formularioLateral' value='E'>E</option>
						</select>
						<br />
						<label for='cuatrimestre' class='formularioLateral'>Cuatrimestre:</label>
						<input type='number' max='2' min='1' value='2' id="cuatrimestre" class='formularioLateral' name="cuatrimestre">
						<label for='anio' class='formularioLateral anios'>Año:</label>
						<input type='number' value='2015' id="anio" class='formularioLateral anios' name="anio">
						
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
					</form>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:7%;">Aula</th>
						<th class="plantelActual" style="width:35%;">Materia</th>
						<th class="plantelActual" style="width:7%;">Cantidad</th>
						<th class="plantelActual" style="width:16%;">Día</th>
						<th class="plantelActual" style="width:10%;">turno</th>
						<th class="plantelActual" style="width:10%;">comisión</th>
						<th class="plantelActual" style="width:5%;">Eliminar</th>
					</tr>
					<?php
						require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM docente WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT aula, materia, cantidad_alumnos, dia, turno, comision 
									FROM asignacion_aulas WHERE activo = 1 
									ORDER BY materia, comision LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						echo $mysqli->error;
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
							echo "<tr class='formularioLateral plantelActual'>";
							
							foreach ($row as $value) {
								echo "<td class='formularioLateral plantelActual'>$value</td>";
							}
							
							echo "<td class='formularioLaterial eliminarEnTabla'><button type='button' class='formularioLateral botonEliminar' id='eliminarDocente' data-dni='$row[dni]'>X</button>";
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
			
			/*$('#unidad').change(function() {
				unidad = $('#unidad').val();
				if (unidad != "" ) {
					$.get("./fuentes/AJAX.php?act=mostrarDescripcionUnidadTematica", {"unidad":unidad}, function(data) {
						$('#descripcion').val(data);
					});
				}
			});*/
			
			$("#formulario_asignacion").submit(function(event) {
				event.preventDefault();
				formValues = $(this).serialize();
				
				$.post("./fuentes/AJAX.php?act=agregarAsignacionDeAula", formValues, function(data) {
					alert(data);
				});
				
			});
			
			$(".datepicker").datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:'yy-mm-dd',
				yearRange:'c-80:c',
				
			});
			
			$('.botonEliminar').click(function() {
				dni = $(this).data('dni');
				$.post("./fuentes/AJAX.php?act=eliminarDocente", {"dni":dni, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>

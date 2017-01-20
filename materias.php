<!DOCTYPE html>
<html>
	<head>
		
		<title>Materias</title>
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
			<h2 class="formularioLateral">Materias</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<!--<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente">-->
						<label class="formularioLateral" for="cod">Código:</label>
						<input type="text" class="formularioLateral iconCod" name="cod" required="required" id="cod"maxlength="10"/>
						<br />
						<label class="formularioLateral" for="nombre">Nombre: </label>
						<input name="nombre" class="formularioLateral iconNombre"  required="required" id="nombre" type="text" maxlength="100">
						<br />
						<label class="formularioLateral" for="carrera">Carrera: </label>
						<select name="carrera" class="formularioLateral iconCarrera"  required="required" id="carrera">
							<option value="">Seleccione la carrera</option>
							<?php 
								require "./conexion.php";
								$query = "SELECT id, cod, nombre FROM carrera WHERE activo = 1";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
									echo "<option value='$row[id]'>$row[nombre]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
						
						</select><br />
						<label class="formularioLateral" for="cuatrimestre">Cuatrimestre: </label>
						<input name="cuatrimestre" class="formularioLateral iconCuatrimestre"  required="required" id="cuatrimestre" type="number">
						<br />
						<label class="formularioLateral" for="plan">Plan:</label>
						<input type="text" class="formularioLateral iconPlan" name="Plan" required="required" id="plan" />
						<br />
						<label class="formularioLateral" for="contenidosminimos">Contenidos Mínimos: </label>
						<textarea name="contenidosminimos" class="formularioLateral"  required="required" id="contenidosminimos" style="height:100px;"></textarea>
						<br />
						<button type="button" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:10%;">Código</th>
						<th class="plantelActual" style="width:45%;">Nombre</th>
						<th class="plantelActual" style="width:20%;">Carrera</th>
						<th class="plantelActual" style="width:10%;">Plan</th>
						<th class="plantelActual" style="width:10%;">Cuatrimestre</th>
						<th class="plantelActual" style="width:5%;">Eliminar</th>
					</tr>
					<?php
						require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM materia WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT m.cod, m.nombre, c.cod as carrera, m.plan, m.cuatrimestre 
											FROM materia AS m
											INNER JOIN carrera AS c
											ON m.carrera = c.id 
											WHERE m.activo = 1 
											ORDER BY m.cod, m.nombre 
											LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
							echo "<tr class='formularioLateral plantelActual'>";
							
							foreach ($row as $value) {
								echo "<td class='formularioLateral plantelActual'>$value</td>";
							}
							
							echo "<td class='formularioLaterial eliminarEnTabla'><button type='button' class='formularioLateral botonEliminar' id='eliminarDocente' data-cod='$row[cod]'>X</button>";
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
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
			
			$("#guardarCargarOtro").click(function() {
				cod = $('#cod').val();
				nombre = $('#nombre').val();
				carrera = $('#carrera').val();
				cuatrimestre = $('#cuatrimestre').val();
				plan = $('#plan').val();
				contenidosminimos = $('#contenidosminimos').val();
				
				if (cod != "" && carrera != "" && nombre != "" && cuatrimestre != "" && plan != "" && contenidosminimos != "") {
					$.post("./fuentes/AJAX.php?act=agregarMateria", {"cod":cod, "nombre":nombre, "carrera":carrera, "plan":plan, "cuatrimestre":cuatrimestre, "contenidosminimos":contenidosminimos }, function(data) {
						
						location.reload();
					});
				}
			});
			
			/*$(".datepicker").datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:'yy-mm-dd',
				yearRange:'c-80:c',
				
			});*/
			
			$('#cod').change(function() {
				cod = $('#cod').val();
				$.post("./fuentes/AJAX.php?act=buscarMateria", {"cod":cod, }, function(data) {
						
						datosMateria = data.split(',');
						$('#nombre').val(datosMateria[1]);
						$('#carrera').val(datosMateria[2]);
						$('#plan').val(datosMateria[3]);
						$('#cuatrimestre').val(datosMateria[4]);
						$('#contenidosminimos').val(datosMateria[5]);
				});
			});
			
			$('.botonEliminar').click(function() {
				cod = $(this).data('cod');
				$.post("./fuentes/AJAX.php?act=eliminarMateria", {"cod":cod, }, function(data) {
					//alert(data);
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>
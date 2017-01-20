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
			<h2 class="formularioLateral">Docentes</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<!--<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente">-->
						<label class="formularioLateral" for="dni">DNI:</label>
						<input type="text" class="formularioLateral iconCod" name="dni" required="required" id="dni"maxlength="10"/>
						<br />
						<label class="formularioLateral" for="apellido">Apellido: </label>
						<input name="apellido" class="formularioLateral iconNombre"  required="required" id="apellido" type="text" maxlength="30">
						<br />
						<label class="formularioLateral" for="nombre">Nombre: </label>
						<input name="nombre" class="formularioLateral iconNombre"  required="required" id="nombre" type="text" maxlength="30">
						<br />
						<label class="formularioLateral" for="fechanacimiento">Fecha de Nacimiento: </label>
						<input name="fechanacimiento" class="formularioLateral iconFecha datepicker"  required="required" id="fechanacimiento" type="date">
						<br />
						<label class="formularioLateral" for="fechaingreso">Fecha de Ingreso:</label>
						<input type="date" class="formularioLateral iconFecha datepicker" name="fechaingreso" required="required" id="fechaingreso" />
						
						<button type="button" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:10%;">DNI</th>
						<th class="plantelActual" style="width:25%;">Apellido</th>
						<th class="plantelActual" style="width:30%;">Nombres</th>
						<th class="plantelActual" style="width:15%;">Fecha Nacimiento</th>
						<th class="plantelActual" style="width:15%;">Fecha Ingreso</th>
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
						
						$query = "SELECT dni, apellido, nombres, fechanacimiento, fechaingreso FROM docente WHERE activo = 1 ORDER BY apellido, nombres LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						
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
			
			$("#guardarCargarOtro").click(function() {
				dni = $('#dni').val();
				apellido = $('#apellido').val();
				nombres = $('#nombre').val();
				fechanacimiento = $('#fechanacimiento').val();
				fechaingreso = $('#fechaingreso').val();
				if (dni != "" && apellido != "" && nombres != "" && fechanacimiento != "" && fechaingreso != "") {
					$.post("./fuentes/AJAX.php?act=agregarDocente", {"dni":dni, "apellido":apellido, "nombre":nombres, "fechanacimiento":fechanacimiento, "fechaingreso":fechaingreso }, function(data) {
						alert(data);
						location.reload();
					});
				}
			});
			
			$(".datepicker").datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:'yy-mm-dd',
				yearRange:'c-80:c',
				
			});
			
			$('#dni').change(function() {
				dni = $('#dni').val();
				$.post("./fuentes/AJAX.php?act=buscarDNI", {"dni":dni, }, function(data) {
						datosDocente = data.split(',');
						$('#apellido').val(datosDocente[0]);
						$('#nombre').val(datosDocente[1]);
						$('#fechanacimiento').val(datosDocente[2]);
						$('#fechaingreso').val(datosDocente[3]);
					
				});
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

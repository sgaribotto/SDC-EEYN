<!DOCTYPE html>
<html>
	<head>
		
		<title>Responsables</title>
		<?php 
			require_once('./fuentes/meta.html');
			
			require_once 'programas.autoloader.php';
		?>
		
	</head>
	
	<body>
		
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Responsables</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<!--<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente">-->
						
						<label class="formularioLateral" for="usuario">Usuario: </label>
						<select name="usuario" class="formularioLateral iconUser"  required="required" id="usuario">
							<option value="">Seleccione el usuario</option>
							<?php 
								require "./conexion.php";
								$query = "SELECT id, CONCAT_WS(', ', apellido, nombres) AS nombre FROM personal WHERE activo = 1 AND NOT ISNULL(usuario) ORDER BY apellido, nombre";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
									echo "<option value='$row[id]'>$row[nombre]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
						
						</select><br />
						<label class="formularioLateral" for="materia">Materia: </label>
						<select name="materia" class="formularioLateral iconMateria"  required="required" id="materia">
							<option value="">Seleccione la materia</option>
							<?php 
								require "./conexion.php";
								$query = "SELECT id, cod, nombre FROM materia WHERE activo = 1";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
									echo "<option value='$row[cod]'>$row[cod] - $row[nombre]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
						
						</select><br />
						
						<button type="button" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:5%;">Id</th>
						<th class="plantelActual" style="width:40%;">Responsable</th>
						<th class="plantelActual" style="width:50%;">Materia</th>
						<th class="plantelActual" style="width:5%;">Eliminar</th>
					</tr>
					<?php
						require "./conexion.php";
						//PAginación
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM responsable WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						//tabla y resultados
						$query = "SELECT id, responsable, materia FROM vista_responsable LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						echo $mysqli->error;
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
							echo "<tr class='formularioLateral plantelActual'>";
							
							foreach ($row as $value) {
								echo "<td class='formularioLateral plantelActual'>$value</td>";
							}
							
							echo "<td class='formularioLaterial eliminarEnTabla'><button type='button' class='formularioLateral botonEliminar' id='eliminarDocente' data-cod='$row[id]'>X</button>";
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
			
			$("#guardarCargarOtro").click(function() {
				usuario = $('#usuario').val();
				materia = $('#materia').val();
				
				
				if (usuario != "" && materia != "") {
					$.post("./fuentes/AJAX.php?act=agregarResponsable", {"usuario":usuario, "materia":materia}, function(data) {
						location.reload();
					});
				}
			});
			
			$('.botonEliminar').click(function() {
				id = $(this).data('cod');
				$.post("./fuentes/AJAX.php?act=eliminarResponsable", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div#formulario').toggle();
			});
			
		});
	</script>
</html>

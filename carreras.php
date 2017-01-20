<!DOCTYPE html>
<html>
	<head>
		
		<title>Carreras</title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once('./fuentes/botonera.php');
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		
	</head>
	
	<body>
		<?php
			require("./fuentes/panelNav.php");
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Carreras</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<!--<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente">-->
						<label class="formularioLateral" for="cod">Código:</label>
						<input type="text" class="formularioLateral iconCod" name="cod" required="required" id="cod" maxlength="25"/>
						<br />
						<label class="formularioLateral" for="nombre">Nombre: </label>
						<input name="nombre" class="formularioLateral iconNombre"  required="required" id="nombre" type="text" maxlength="50">
						<br />
						<button type="button" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
						
									
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:5%;">Id</th>
						<th class="plantelActual" style="width:20%;">Código</th>
						<th class="plantelActual" style="width:75%;">Carrera</th>
						
					</tr>
					<?php
						require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM carrera WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT id, cod, nombre FROM carrera WHERE activo = 1 LIMIT $pagina, $hasta";
						$result = $mysqli->query($query);
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
						echo "<tr class='formularioLateral plantelActual'>
											<td class='formularioLateral plantelActual'>$row[id]</td>
											<td class='formularioLateral plantelActual'>$row[cod]</td>
											<td class='formularioLateral plantelActual'>$row[nombre]</td>
										</tr>";
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
			
			$('#cod').change(function() {
				cod = $('#cod').val();
				if (cod != "" ) {
					$.post("./fuentes/AJAX.php?act=mostrarNombreCarrera", {"cod":cod}, function(data) {
						$('#nombre').val(data);
					});
				}
			});
			
			$("#guardarCargarOtro").click(function() {
				cod = $('#cod').val();
				nombre = $('#nombre').val();
				
				
				if (cod != "" && nombre != "" ) {
					$.post("./fuentes/AJAX.php?act=agregarCarrera", {"cod":cod, "nombre":nombre }, function(data) {
						alert(data);
						location.reload();
					});
				}
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			

		});
		
		
	</script>
</html>
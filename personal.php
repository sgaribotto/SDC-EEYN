<!DOCTYPE html>
<html>
	<head>
		
		<title>Personal</title>
		<?php 
			
			require_once('./fuentes/meta.html');
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		
	</head>
	
	<body>
		
		
		<!--<div class="ayudaLateral">
			<h2 class="ayudaLateral">Ayuda</h2>
			<h3 calss="ayudaLateral">Datos generales</h3>
			<p class="ayudaLateral">
				Aquí buscamos responder las siguientes preguntas:
					<ul class="ayudaLateral">
						<li class="ayudaLateral">¿Quiénes somos?</li>
						<li class="ayudaLateral">¿Dónde estamos?</li>
					</ul>
				Completaremos los siguientes datos en el formulario de la derecha:
					<ul class="ayudaLateral">
						<li class="ayudaLateral">Denominación</li>
						<li class="ayudaLateral">Carrera</li>
						<li class="ayudaLateral">Ubicación en el plan de estudios</li>
						<li class="ayudaLateral">Duración y carga horaria</li>
						<li class="ayudaLateral">Composición del equipo docente</li>
					</ul>
						
			</p>
		</div>-->
		
		<?php
			require("./fuentes/panelNav.php");
			require_once('./fuentes/botonera.php');
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Personal</h2>
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
						<label class="formularioLateral" for="usuario">Usuario: </label>
						<input name="usuario" class="formularioLateral iconUser"  required="required" id="usuario" type="text" maxlength="30">
						<br />
						
						
						<button type="button" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:15%;">DNI</th>
						<th class="plantelActual" style="width:25%;">Apellido</th>
						<th class="plantelActual" style="width:30%;">Nombres</th>
						<th class="plantelActual" style="width:25%;">Usuario</th>
						<th class="plantelActual" style="width:5%;">Eliminar</th>
					</tr>
					<?php
						require('./conexion.php');
						//Paginación
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM personal WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						//Consulta y armado de tabla
						$query = "SELECT dni, apellido, nombres, usuario FROM personal WHERE activo = 1 ORDER BY apellido, nombres LIMIT $pagina, $hasta";
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
				dni = $('#dni').val();
				apellido = $('#apellido').val();
				nombre = $('#nombre').val();
				usuario = $('#usuario').val();
								
				if (dni != "" && apellido != "" && nombre != "" && usuario != "" ) {
					$.post("./fuentes/AJAX.php?act=agregarPersonal", {"dni":dni, "apellido":apellido, "nombre":nombre, "usuario":usuario }, function(data) {
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
			
			$('#dni').change(function() {
				dni = $('#dni').val();
				$.post("./fuentes/AJAX.php?act=buscarPersonal", {"dni":dni, }, function(data) {
						console.log("(" + data + ")");
						datosDocente = eval("(" + data + ")");
						console.log(datosDocente);
						$('#apellido').val(datosDocente.apellido);
						$('#nombre').val(datosDocente.nombres);
						$('#usuario').val(datosDocente.usuario);
						//$('#fechaingreso').val(datosDocente[3]);
					
				}, 'html');
			});
			
			$('.botonEliminar').click(function() {
				dni = $(this).data('dni');
				$.post("./fuentes/AJAX.php?act=eliminarPersonal", {"dni":dni, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>

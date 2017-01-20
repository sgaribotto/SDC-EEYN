<!DOCTYPE html>
<html>
	<head>
		
		<title>Aceptar Designaciones</title>
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
			<h2 class="formularioLateral">Aceptar Designaciones</h2>
			<div id="mostrarFormulario">Mostrar filtros</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<!--<form method="post" class="formularioLateral" action="procesardatos.php?formulario=equipoDocente">-->
						<label class="formularioLateral" for="periodo">Periodo:</label>
						<select class="formularioLateral iconPeriodo filterTrigger" name="periodo" id="periodo"/>
							<option class="formularioLateral" value="">Seleccionar periodo</option>
							<?php
								require "fuentes/conexion.php";
								
								$query = "SELECT DISTINCT CONCAT(anio, '-', cuatrimestre) as periodo
											FROM afectacion
											ORDER BY anio, cuatrimestre";
								$result = $mysqli->query($query);
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option class='formularioLateral' value='$row[periodo]' selected='selected' >$row[periodo]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
								 
						</select>
						<br />
						<label class="formularioLateral" for="estado">Estado:</label>
						<select class="formularioLateral iconPeriodo filterTrigger" name="estado" id="estado"/>
							<option class="formularioLateral" value="">Seleccionar estado</option>
							<?php
								require "fuentes/conexion.php";
								
								$query = "SELECT DISTINCT estado
											FROM afectacion";
								$result = $mysqli->query($query);
								
								$estadosDefault = ['', '', 'AprobadoCOORD', 'AprobadoSA', 'Pendiente', 'Pendiente', 'Pendiente'];
								$permiso = max($_SESSION['permiso']); 
								
								
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option class='formularioLateral' value='$row[estado]' ";
										if ($row['estado'] == $estadosDefault[$permiso]) {
											echo "selected='selected'";
										}
									echo ">$row[estado]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
								 
						</select>
						<br />
						<label class="formularioLateral" for="carrera">Carrera:</label>
						<select class="formularioLateral iconPeriodo filterTrigger" name="carrera" id="carrera"/>
							<option class="formularioLateral" value="">Seleccionar carrera</option>
							<?php
								require "fuentes/conexion.php";
								
								$query = "SELECT DISTINCT id, nombre
											FROM carrera";
								$result = $mysqli->query($query);
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option class='formularioLateral' value='$row[id]'>$row[nombre]</option>";
								}
								
								$result->free();
								$mysqli->close();
							?>
								 
						</select>
						<br />
						<label class="formularioLateral" for="materia">Materia: </label>
						<input name="materia" class="formularioLateral iconMateria filterTrigger"   id="materia" type="text" maxlength="30">
						<br />
						<label class="formularioLateral" for="docente">Docente: </label>
						<input name="docente" class="formularioLateral iconNombre filterTrigger"  required="required" id="docente" type="text" maxlength="30">
						<br />
				</fieldset>
			</div>
			<div class="dialog resumenMateria" id="dialogResumenMateria"></div>
		
			<hr>
			
		
			<div id="tablaAceptarDesignacion">
				<!--
					<?php
						/*require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM docente WHERE activo = 1");
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT dni, apellido, nombres, fechanacimiento, fechaingreso FROM docente WHERE activo = 1 ORDER BY apellido, nombres LIMIT $pagina, $cantidadResultados";
						$result = $mysqli->query($query);
						
						
						
						$result->free();
						$mysqli->close();*/

					?>
				</table>
				<ul class="linkPagina">
				<?php
					/*if ($totalPaginas > 1) {
						for ($i = 0; $i < $totalPaginas; $i++) {
							$url = $_SERVER['PHP_SELF'] . "?pagina=" . ($i + 1);
							echo "<li class='linkPagina'>
										<a href = $url>" . ($i + 1) . "</a>
									</li>";
							
						}
					}*/
				?>
				</ul>
			</div>-->
			
		</div>
		
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			var dialogOptions = {
				autoOpen: false,
				width:1000,
				height: 600,
				modal: true,
				appendTo: "#Botonera"
				
			};
			$('div.dialog').dialog(dialogOptions);
			
			$('#dialogResumenMateria').dialog('option', 'title', 'Resumen de la materia');
			/*$('#unidad').change(function() {
				unidad = $('#unidad').val();
				if (unidad != "" ) {
					$.get("./fuentes/AJAX.php?act=mostrarDescripcionUnidadTematica", {"unidad":unidad}, function(data) {
						$('#descripcion').val(data);
					});
				}
			});*/
			
			var actualizarTabla = function() {
				var periodo = $('#periodo').val();
				var estado = $('#estado').val();
				var carrera = $('#carrera').val();
				var materia = $('#materia').val();
				var docente = $('#docente').val();
				$('#tablaAceptarDesignacion').load("./fuentes/AJAX.php?act=actualizarTablaAceptarDesignaciones", {"CONCAT(a*anio,'-',a*cuatrimestre)":periodo, "a*estado":estado, "c*id":carrera, "m*nombre":materia, "CONCAT(d*apellido,'-',d*nombres)":docente }, function(data) {
						
						
						$('select.aceptarDesignacion').change(function() {
							var id = $(this).data('id');
							var estado = $(this).val();
							$.post("./fuentes/AJAX.php?act=cambiarEstadoDesignacion", {"id":id, "estado":estado}, function(data) {
							//console.log(data);
						});
						
					});
				});
			} 
			
			$('#tablaAceptarDesignacion').on('click', 'td.linkResumenMateria', function(event) {
				
				var cod = $(event.target).data('cod');
				$('#dialogResumenMateria').empty();
				
				$('#dialogResumenMateria').load('resumenmateria.php?materia=' + cod);
				
				$('#dialogResumenMateria').dialog(dialogOptions).dialog('open');
			});
			
			actualizarTabla();
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
			$('.filterTrigger').on('keyup keypress blur change', function() {
				actualizarTabla(); 
			});
			
			
			
		});
	</script>
</html>

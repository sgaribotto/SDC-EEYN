<!DOCTYPE html>
<html>
	<head>
		
		<title>Asignación de comisiones 1er cuatrimestre 2017</title>
		<?php 
			require_once 'programas.autoloader.php';
			require_once('./fuentes/meta.html');
			include './fuentes/constantes.php';
			$ANIO = 2017;
			$CUATRIMESTRE = 1;
			$materia = new clases\Materia($_SESSION['materia']);
			$conjunto = $materia->mostrarConjunto();
		?>
		
	</head>
	
	<body>
		
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Asignar comisiones 1er cuatriemestre 2017</h2>
			<div id="plantelActual">
				<table class="plantelActual">
					<thead class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:60%;">Docente</th>
						<th class="plantelActual" style="width:20%;">Dependencia</th>
						<th class="plantelActual" style="width:10%;">Materia</th>
						<th class="plantelActual" style="width:10%;">Comisión</th>
						<!--<th class="plantelActual" style="width:15%;">Cargo</th>-->
						<!--<th class="plantelActual" style="width:20%;">Carácter</th>-->
						<!--<th class="plantelActual" style="width:25%;">Fecha de Ingreso</th>-->
						<!--<th class="plantelActual" style="width:20%;">Eliminar</th>-->
					</tr>
					</thead>
					<tbody class="plantelActual" id="comisionesAsignadas"></tbody>
						
				</table>
			</div>
			<hr>
			<div id="formulario1">
				<fieldset class="formularioLateral">
					<form method="get" class="formularioLateral" for="./fuentes/AJAX.php?act=agregarAsignacionComision" id="agregarAsignacionComision">
						<label class="formularioLateral" for="docente">Docente:</label>
						<Select class="formularioLateral iconId" name="docente" id="dniDocente" required="required">
							<option value="" selected="selected">Seleccione de la lista - Escriba para buscar</option>
							<?php
								
								require "./conexion.php";
								$query = "SELECT DISTINCT d.id, a.materia, d.apellido, d.nombres 
											FROM afectacion AS a
											LEFT JOIN docente AS d ON d.id = a.docente
											WHERE a.anio = {$ANIO} AND a.cuatrimestre = {$CUATRIMESTRE}
												AND a.materia = '$_SESSION[materia]' AND a.activo = 1
											ORDER BY d.apellido, d.nombres";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option value='$row[id]'>$row[apellido], $row[nombres]</option>";
								}
								$result->free();
								$mysqli->close();
							?>
						</select>
						<img src="./images/icons/info.png" alt="Info" title="Si el docente que desea asignar no está en la lista, por favor ingreselo en el EQUIPO DOCENTE de la materia al cual puede acceder desde el menú de la izquierda" height="20px" style="cursor:help;margin-left:35px;">
						<br />
						<label class="formularioLateral" for="comision">Comisiones disponibles:</label>
						<Select class="formularioLateral iconId" name="comision" id="comision_asignada" required="required">
							<option value="" selected="selected">Seleccione de la lista - Escriba para buscar</option>
							<?php
								
								require "./conexion.php";
								//QUERY CON LOS TURNOS VIEJOS
								/*$query = "SELECT DISTINCT
											cc.turno, cc.cantidad, cc.materia, cc.dependencia, t.dia, 
											GROUP_CONCAT(DISTINCT t.turno ORDER BY t.turno SEPARATOR ' - ') AS horario
											
										FROM
											cantidad_comisiones AS cc
										LEFT JOIN materia AS m ON m.conjunto = cc.materia
										LEFT JOIN turnos AS t ON  t.materia = m.cod AND cc.turno = LEFT(t.turno, 1)
											WHERE cc.anio = $ANIO AND cc.cuatrimestre = $CUATRIMESTRE
												AND cc.materia = '{$materia->datosMateria['conjunto']}'
										GROUP BY cc.turno, t.dia";*/
										
								//QUERY USANDO LA TABLA COMISINES ABIERTAS
								$query = "SELECT ca.id, 
											ca.materia,
											ca.horario,
											ca.nombre_comision,
											ca.turno
										FROM comisiones_abiertas AS ca
										WHERE ca.materia = '{$conjunto}'
											AND anio = {$ANIO}
											AND cuatrimestre = {$CUATRIMESTRE}
										GROUP BY ca.id;";
											
								$result = $mysqli->query($query);
								
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									
									
										echo "<option value='$row[id]'>$row[materia]$row[nombre_comision]. Horarios: $row[horario]</option>";
									
								}
								$result->free();
								$mysqli->close();
							?>
						</select>
						<br />
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otro</button>
						<p class="formularioLateral errorValidar"></p>
						<br />
						<button type="button" class="formularioLateral iconContinuar" id="botonContinuar">Continuar</button>
						
					</form>
				</fieldset>
				
			</div>
		</div>
	</body>
	<script src="./fuentes/funciones.js"></script>
	<script>
		$(document).ready(function() {
			
						
			$("#agregarAsignacionComision").submit(function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values.act = "agregarAsignacionComision";
				
				$.get("./fuentes/AJAX.php?act=agregarAsignacionComision", values, function(data) {
					data = eval('(' + data + ')');
					console.log(data);
					if (data.error) {
						$('p.errorValidar').text(data.error);
						
					} else {
						actualizarTabla();
					}
				});
			});
			
			$("#botonContinuar").click(function() {
				location.assign("./objetivos.php");
			});
			
			$("select").combobox();
			
			var actualizarTabla = function() {
				//formValues = $('form.filtros').serialize();
				//console.log(formValues);
				$('tbody#comisionesAsignadas').load("fuentes/AJAX.php?act=tablaAsignacionComisiones", function(data) {
					
					$('.botonEliminar').click(function() {
						id = $(this).data('id');
						$.post("./fuentes/AJAX.php?act=eliminarComisionAsignada", {"id":id, }, function(data1) {
							actualizarTabla();
						});
				});
				
				});
			} 
			actualizarTabla();
			
		});
	</script>
	
  <style>
	  .custom-combobox {
		position: relative;
		display: inline-block;
	  }
	  .custom-combobox-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
	  }
	  .custom-combobox-input {
		margin: 0;
		padding: 5px 10px;
		width:300px;
	  }
  </style>
</html>

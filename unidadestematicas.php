<!DOCTYPE html>
<html>
	<head>
		
		<title>Unidades temáticas</title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once 'programas.autoloader.php';
			include './fuentes/constantes.php';
		?>
		
	</head>
	
	<body>
		
		
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Unidades temáticas</h2>
			<div id="plantelActual">
				<table class="plantelActual">
					<thead>
					<tr class="plantelActual">
						<th class="plantelActual" style="width:15%;">Unidad</th>
						<th class="plantelActual" style="width:80%;">Descripción</th>
						<!--<th class="plantelActual" style="width:20%;">Carácter</th>
						<th class="plantelActual" style="width:20%;">Fecha de Ingreso</th>
						<th class="plantelActual" style="width:20%;">Estado</th>-->
					</tr>
					</thead>
					<tbody class="unidadesTematicas"></tbody>
					<?php
						/*$materia = new clases\Materia($_SESSION['materia']);
						$unidadesTematicas = $materia->mostrarUnidadesTematicas("*", $ANIO, $CUATRIMESTRE);
						
						if (empty($unidadesTematicas)) {
							echo "<tr><td colspan='2'>No hay unidades cargadas</td></tr>";
						} else {
						
							foreach ($unidadesTematicas as $key => $value ) {
								echo "<tr class='formularioLateral plantelActual'>
											<td class='formularioLateral plantelActual'>$key</td>
											<td class='formularioLateral plantelActual'>$value</td>
											<td class='formularioLateral correlatividadesTable'><button type='button' class='botonEliminar' data-unidadtematica='$key' >X</button></td>
											</tr>";
							
							}
						}
							
*/
					?>
				</table>
			</div>
			<hr>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="./fuentes/AJAX.php?act=agregarUnidad" id="agregarUnidadTematica">
						<label class="formularioLateral" for="unidad">Unidad:</label>
						<input type="number" class="formularioLateral iconUnidad" name="unidad" required="required" id="unidad" min='1' />
						 <img src="./images/icons/info.png" alt="Info" title="Puede modificar una unidad cargada seleccionando su número" height="20px" style="cursor:help;margin-left:10px;">
						<br />
						<label class="formularioLateral" for="descripcion">Descripción: </label>
						<textarea name="descripcion" class="formularioLateral"  required id="descripcion" style="height:100px;"></textarea>
						<br />
						
						<button type="submit" class="formularioLateral iconAgregar" id="1guardarCargarOtro">Guardar y cargar otra</button>
						
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
			
			var actualizarTabla = function() {
				//formValues = $('form.filtros').serialize();
				//console.log(formValues);
				$('tbody.unidadesTematicas').load("fuentes/AJAX.php?act=tablaUnidadesTematicas", function(data2) {
					
					$('.botonEliminar').click(function() {
						id = $(this).data('unidadtematica');
						$.post("./fuentes/AJAX.php?act=eliminarUnidadTematica", {"unidadtematica":id, }, function(data1) {
							//console.log(data1);
							actualizarTabla();
							
						});
					});
				});
			} 
			actualizarTabla();
			
			$("form#agregarUnidadTematica").submit(function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values.act = "agregarUnidadTematica";
				
				$.post("./fuentes/AJAX.php?act=agregarUnidadTematica", values, function(data1) {
					actualizarTabla();
				});
			});
			
			
			$('#unidad').change(function() {
				unidad = $('#unidad').val();
				if (unidad != "" ) {
					$.get("./fuentes/AJAX.php?act=mostrarDescripcionUnidadTematica", {"unidad":unidad}, function(data) {
						$('#descripcion').val(data);
					});
				}
			});
			
			$("#botonContinuar").click(function() {
				location.assign("./evaluacion.php");
			});
		});
	</script>
</html>

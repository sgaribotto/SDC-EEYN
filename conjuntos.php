<!DOCTYPE html>
<html>
	<head>
		
		<title>Materias y uniones</title>
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
			<!--<div id="mostrarFormulario">Mostrar Formulario</div>-->
			<div id="formulario1">
				<fieldset class="formularioLateral">
					
					
					<form method="post" class="formularioLateral" action="#" id="agregarConjunto">
						<label class="formularioLateral" for="cod">Código:</label>
						<input type="text" class="formularioLateral iconCod" name="cod" required="required" id="cod" maxlength="10"/>
						<hr />
						<label class="formularioLateral" for="agregar">Código a agregar:</label>
						<input type="text" class="formularioLateral iconCod" name="agregar" required="required" id="agregar" maxlength="10"/>
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Agregar</button>
						<div id="materiasUnidas" class="materiasUnidas">
							<h3>Materias agrupadas</h3>
							<table class="materiasUnidas" style="width:100%;">
								<thead>
									<th style="width:5%;">Eliminar</th>
									<th style="width:20%;">Código</th>
									<th style="width:75%;">Nombre</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						
						
					</form>
				</fieldset>
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
			
			$("#agregarConjunto").submit(function(event) { //HACERLO CON FORM SERIALIZE
				event.preventDefault();
				$form = $(this);
				values = $form.serialize();
				//values.act = 'agregarConjunto';

				$.post("./fuentes/AJAX.php?act=agregarConjunto", values, function(data) {
					data = eval('(' + data + ')');
					if (data.error) {
						alert(data.error);
						$('#agregar').val('').focus();
					} else {
						$('#cod').change();
					}
					
				});
				
			});
			
			
			$('#cod').change(function() {
				$('#materiasUnidas table.materiasUnidas tbody').empty();
				
				cod = $('#cod').val();
				$.post("./fuentes/AJAX.php?act=buscarConjunto", {'cod':cod}, function(data) {
					//console.log(data);
					data = eval( "(" + data + ")");
					
					if (data.error) {
						alert(data.error);
						$('#cod').val('').focus();
					} else {
						//console.log(data);
						$.each(data, function(key, val) {
							//console.log(val.cod);
							html = "<tr class='materiasUnidas'>";
							if (val.cod != cod) {
								html += "<td><button class='botonEliminar' type='button' data-cod='" + val.cod + "'>X</button></td>";
							} else {
								html += "<td></td>";
							}
							html += "<td>" + val.cod + " </td><td> " + val.nombre + "</td>";
							
							html += "</tr>";
							//alert(html);
							$('#materiasUnidas table.materiasUnidas tbody').append(html);
						});
						
						$('.botonEliminar').click(function() {
							cod = $(this).data('cod');
							$.post("./fuentes/AJAX.php?act=eliminarConjunto", {"cod":cod, }, function(data) {
								//alert(data);
								//location.reload();
								$('#cod').change();
							});
						});
					}
				
				});
			});
			
			
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>

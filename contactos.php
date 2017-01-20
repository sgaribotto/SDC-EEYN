<!DOCTYPE html>
<html>
	<head>
		
		<title>Contactos docentes</title>
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
			<div id="mostrarFiltros">Mostrar Filtros</div>
			
			<div id="formulario" class="desplegable">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="fuentes/AJAX.php?act=agregarDatosContacto" id="datosContacto">
						<label class="formularioLateral" for="dni">Docente:</label>
						<Select class="formularioLateral iconId" name="dni" id="docente" required="required"/>
							<option value="" selected="selected">Seleccione de la lista - Escriba para buscar</option>
							<?php
								require "./conexion.php";
								$query = "SELECT id, dni, apellido, nombres FROM docente ORDER BY apellido, nombres";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option value='$row[id]'>$row[apellido], $row[nombres]</option>";
								}
								$result->free();
								$mysqli->close();
							?>
						</select>
						<img src="./images/icons/info.png" alt="Info" title="Ahora puede buscar docentes por nombre o dni. En caso de error o falta de un docente, informe a weeyn@unsam.edu.ar" height="20px" style="cursor:help;margin-left:35px;">
						<br />
						<label class="formularioLateral" for="telefono">Teléfono: </label>
						<input type="text" class="formularioLateral iconTelefono" name="telefono" id="telefono">
						<br />
						<label class="formularioLateral" for="telefonoAlternativo">Teléfono alternativo: </label>
						<input type="text" class="formularioLateral iconTelefono" name="telefonoAlternativo" id="telefonoAlternativo">
						<br />
						<label class="formularioLateral" for="mail">Mail: </label>
						<input type="text" class="formularioLateral iconMail" name="mail" id="mail">
						<br />
						<label class="formularioLateral" for="telefono">Mail alternativo: </label>
						<input type="text" class="formularioLateral iconMail" name="mailAlternativo" id="mailAlternativo">
						<br />
						<label class="formularioLateral" for="nombre">Observaciones: </label>
						<textarea name="observaciones" class="formularioLateral iconObservaciones" maxLength="254" style="height:54px;" id="observaciones"></textarea>
						<br />
						
						<button type="submit" class="formularioLateral iconGuardar" id="guardarCargarOtro">Guardar</button>
					</form>
				</fieldset>
			</div>
			
			
			<div id="filtros" class="desplegable">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral filtros" action="fuentes/AJAX.php?act=tablaContactosDocentes" >
						<label class="formularioLateral" for="filtro">Buscar:</label>
						<input type="text" class="formularioLateral iconCod" name="filtro" required="required" id="filtro" maxlength="10"/>
					</form>	
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="tablaDatos"></div>
			
			<div class="dialog resumenDocente" id="dialogResumenDocente"></div>
			
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
			
			var actualizarTabla = function() {
				formValues = $('form.filtros').serialize();
				//console.log(formValues);
				$('#tablaDatos').load("fuentes/AJAX.php?act=tablaContactosDocentes", formValues, function(data) {
				
				});
			} 
			actualizarTabla();
			
			$("#datosContacto").submit(function(event) {
				event.preventDefault();
				values = $(this).serialize();
				$.post("./fuentes/AJAX.php?act=agregarDatosContacto", values, function(data) {
					//console.log(data);
					alert("Se ha guardado con éxito");
				});
				
			});
			
			$(".datepicker").datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:'yy-mm-dd',
				yearRange:'c-80:c',
				
			});
			
			$("select#docente").combobox({
				
				select: function() { 
					$("select#docente").change();
				},
				
				
			});
			
			/*$("form#datosContacto").click(function(event) {
				$('select#docente').change();
				console.log(event.target);	
				
			});*/
			
			$('select#docente').change(function(event) {
				//alert('change');
				
				id = $(this).val();
				if (id) {
					$.post("./fuentes/AJAX.php?act=buscarDatosContacto", {"id":id, }, function(data) {
							var datos = eval("(" + data + ")");
							
							
							$('#telefono').val('');
							$('#telefonoAlternativo').val('');
							$('#mail').val('');
							$('#mailAlternativo').val('');
							$('#observaciones').val('');
							
							if (!datos.vacio) {
								if (datos.telefono) {
									$('#telefono').val(datos.telefono.valor);
								}
								if (datos.telefonoAlternativo) {
									$('#telefonoAlternativo').val(datos.telefonoAlternativo.valor);
								}
								if (datos.mail) {
									$('#mail').val(datos.mail.valor);
								}
								if (datos.mailAlternativo) {
									$('#mailAlternativo').val(datos.mailAlternativo.valor);
								}
								if (datos.observaciones) {
									$('#observaciones').val(datos.observaciones.valor);
								}
							}
					});
				}
			});
			
			$('.botonEliminar').click(function() {
				dni = $(this).data('dni');
				$.post("./fuentes/AJAX.php?act=eliminarDocente", {"dni":dni, }, function(data) {
					location.reload();
				});
			});
			
			function togglerButtonColor() {
				
				gris = '#f9f9f9';
				
				if ($('div#formulario').is(':visible')) {
					$('#mostrarFormulario').css('backgroundColor', 'black');
					$('#mostrarFormulario').css('color', gris);
				} else {
					$('#mostrarFormulario').css('backgroundColor', gris);
					$('#mostrarFormulario').css('color', 'black');
				}
				
				if ($('div#filtros').is(':visible')) {
					$('#mostrarFiltros').css('backgroundColor', 'black');
					$('#mostrarFiltros').css('color', gris);
				} else {
					$('#mostrarFiltros').css('backgroundColor', gris);
					$('#mostrarFiltros').css('color', 'black');
				}
			}
			
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').slideToggle(function() {
					if ($('div#formulario').is(':visible')) {
					$('#mostrarFormulario').css('backgroundColor', 'black');
					$('#mostrarFormulario').css('color', gris);
				} else {
					$('#mostrarFormulario').css('backgroundColor', gris);
					$('#mostrarFormulario').css('color', 'black');
				}
					
				});
				$('div #filtros').slideUp();
				
				gris = '#f9f9f9';
				$('#mostrarFiltros').css('backgroundColor', gris);
					$('#mostrarFiltros').css('color', 'black');
				
				
			});
			
			$('#mostrarFiltros').click(function() {
				$('div #filtros').slideToggle(function() {
					if ($('div#filtros').is(':visible')) {
						$('#mostrarFiltros').css('backgroundColor', 'black');
						$('#mostrarFiltros').css('color', gris);
					} else {
						$('#mostrarFiltros').css('backgroundColor', gris);
						$('#mostrarFiltros').css('color', 'black');
					}
				});
				$('div #formulario').slideUp();
				
				gris = '#f9f9f9';
				
				$('#mostrarFormulario').css('backgroundColor', gris);
				$('#mostrarFormulario').css('color', 'black');
				
				
			});
			$('#mostrarFiltros').click();
			
			$('#filtro').on('keyup', function(event) {
				if ($(this).val().length > 1) {
					actualizarTabla();
				}
			});
			
			$('#filtro').focus();
			
			$.ajaxSetup({
				contentType: "application/x-www-form-urlencoded;charset=UTF-8"
			});
			var dialogOptions = {
				autoOpen: false,
				width:1000,
				height: 600,
				modal: true,
				appendTo: "#Botonera",
				close: function() {
					$('#mostrarFormulario').off('click');
					$('#mostrarFormulario').click(function() {
						$('div #formulario').slideToggle();
					
					});
				},
					
			};
			
			$('div.dialog').dialog(dialogOptions);
			
			$('div#tablaDatos').on('click', 'td.masInfo', function(event) {
				
				var id = $(event.target).data('id');
				$('#dialogResumenDocente').empty();
				
				$('#dialogResumenDocente').load('resumendocente.php', {"docente":id});
				
				$('#dialogResumenDocente').dialog(dialogOptions).dialog('open');
			});
			
			
			
		});
	</script>
</html>

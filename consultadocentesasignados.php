<!DOCTYPE html>
<html>
	<head>
		
		<title>Docentes Asignados</title>
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
			<h2 class="formularioLateral">Docentes asignados</h2>
			
			<div id="mostrarFiltros">Mostrar Filtros</div>
			<div id="filtros">
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
				$('#tablaDatos').load("fuentes/AJAX.php?act=tablaDocentesAsignados", formValues, function(data) {
				
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
			//$('#mostrarFiltros').click();
			
			$('#filtro').on('keyup', function(event) {
				if ($(this).val().length > 2) {
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

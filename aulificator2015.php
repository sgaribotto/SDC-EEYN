<!DOCTYPE html>
<html>
	<head>
		
		<title>Asignación de aulas</title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once('fuentes/constantes.php');
			
			$ANIO = 2017;
			$CUATRIMESTRE = 1;
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		<?php require "./fuentes/jqueryScripts.html"; ?>
		<script src="./fuentes/funciones.js"></script>
		<link rel="stylesheet" type="text/css" href="css/aulificator2015.css">
	</head>
	
	<body>

		<?php
			require_once('./fuentes/botonera.php');
			//require("./fuentes/panelNav.php");
			require 'fuentes/conexion.php';
		?>
	
	
		<div id="filtros">
			
			<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" id="formulario_asignacion">
						<label for='dia' class='formularioLateral'>Día:</label>
						<select id="dia" class='formularioLateral filtros' name='dia'>
							<option class='formularioLateral' value='lunes'>Lunes</option>
							<option class='formularioLateral' value='martes'>Martes</option>
							<option class='formularioLateral' value='miércoles'>Miércoles</option>
							<option class='formularioLateral' value='jueves'>Jueves</option>
							<option class='formularioLateral' value='viernes'>Viernes</option>
							<option class='formularioLateral' value='sábado'>Sábado</option>
						</select>
						
						<label for='turno' class='formularioLateral'>Turno:</label>
						<select id='turno' class='formularioLateral filtros' name='turno'>
							<!--<option class='formularioLateral' value='M'>M</option>-->
							<option class='formularioLateral' value='M1'>M1</option>
							<option class='formularioLateral' value='M2'>M2</option>
							<!--<option class='formularioLateral' value='T'>T</option>-->
							<option class='formularioLateral' value='T1'>T1</option>
							<option class='formularioLateral' value='T2'>T2</option>
							<!--<option class='formularioLateral' value='N'>N</option>-->
							<option class='formularioLateral' value='N1'>N1</option>
							<option class='formularioLateral' value='N2'>N2</option>
							<option class='formularioLateral' value='S'>S</option>
						</select>
						
						<label for='cuatrimestre' class='formularioLateral'>Cuatrimestre:</label>
						<input type='number' max='2' min='1' value='<?php echo $CUATRIMESTRE; ?>' id="cuatrimestre" class='formularioLateral filtros' name="cuatrimestre">
						<label for='anio' class='formularioLateral anios'>Año:</label>
						<input type='number' value='<?php echo $ANIO; ?>' id="anio" class='formularioLateral anios filtros' name="anio">
						
						<label for="corteMinimo" class="formularioLateral">Corte mínimo:</label>
						<input type="number" min="0" max="30" value="6" class="formularioLateral" name="corteMinimo" id="corteMinimo">
						
						<label for="sobreocupar" class="formularioLateral">Sobreocupar %:</label>
						<input type="number" min="0" max="50" class="formularioLateral" value="10" name="sobreocupar" id="sobreocupar">
						
						<label for='asignarTodosLosDias' class="formularioLateral">Asignar Bloque</label>
						<input type="checkbox" name="asignarTodosLosDias" id="asignarTodosLosDias" class="formularioLateral" value="true" checked="checked"> 
						
						<button type="submit" class="formularioLateral autoAsignar">Autoasignar</button>
						
						<button type="button" class="formularioLateral reiniciar" id="reiniciarAsignaciones">Reiniciar</button>-
					</form>
				</fieldset>
		</div>
		
		<div id="listadoMaterias" class="trashcan">
		</div>
		
		
		
		<div id="aulas" class="">
		</div>
		
		<div id="dialogAula" class="dialog">
			<form method="post" class="ajusteAula dialogAula" id="ajusteAula" action="error404.html">
				<label for="materia" class="dialogAula">Materia</label>
				<br />
				<span type="text" class="dialogAula" name="materia" id="materia" ></span>
				<br />
				<label for="cantidadAsignada" class="dialogAula">Cantidad asignada</label>
				<br />
				<input type="number" min="1" class="dialogAula" name="cantidadAsignada" max="100" id="cantidadAsignada" />
				<button type="submit" class="dialogAula">Ajustar</button>
			</form>
		</div>
		
		
		<!--LOADER
		<div class="cssload-container"> 
			<ul class="cssload-flex-container"> 
				<li> 
					<span class="cssload-loading"></span> 
				</li> 
			</div> 
		</div>-->
		
	</body>
	<?php $mysqli->close(); ?>
	
	
	<script>
		$(document).ready(function() {
			
			var comision = "";
			var $loaderDiv = $('<div class="cssload-container"> \
							<ul class="cssload-flex-container"> \
								<li> \
									<span class="cssload-loading"></span> \
								</li> \
							</div> \
						</div>');
			
			function actualizarlistadoMaterias() {
				var formValues = $('#formulario_asignacion').serialize();
				formValues += "&act=listadoDeMaterias";
				
				//$('#listadoMaterias').empty();
				
				$.get('fuentes/aulificatorAJAX.php', formValues, function(data, success) {
					
					$.each(data, function(key, val) {
						
						asignados = 0;
						$.each(val.comisiones, function(comision, cantidad) {
							asignados += parseInt(cantidad) || 0;
						});
						
						$containerMateria = $("<div class='materia' id='" + key + "'></div>");
						$containerMateria
							.html('<table class="inner-materia"><tr><td colspan="2"><str>' + key + '  -  ' + val.nombre_materia + ' </str></td></tr>\
									<tr> <td>Faltan asignar: <span class="faltaAsignar">' + (val.cantidad - asignados) + '</span> Alumnos </td><td> Días:' + val.dias + '\
									</td></tr></table>'  )
							.data('id', key)
							.data('cantidad', val.cantidad - asignados)
							.data('nombre', val.nombre_materia)
							.data('turno', val.turno)
							.data('dias', val.dias)
							.appendTo('#listadoMaterias')
							.draggable({
								revert:'invalid',
								helper: function() {
											return $( "<div class='ui-widget-header' style='width:120px;'> " + $(this).text() + "</div>" );
										},
								opacity: .7,
								cursor: "move",
								cursorAt: { top: 60, left: 60 },
							});

						if (!($containerMateria.data('cantidad'))) {
							$containerMateria.hide();
						}
						
						$containerMateria.hover().addClass('grippy');
					});
					
					$('#listadoMaterias').children('div.cssload-container').remove();
					
				}, 'json');
			};
			
			function asignarDroppables() {
				$('div.aula.disponible').droppable({
					accept: 'div.materia',
				  activeClass: "droppable-active-custom",
				  hoverClass: "droppable-hover-custom",
				  
				  activate: function(event, ui) {
					  $droppable = $(this);
					  $draggable = ui.draggable;
					
					if ($droppable.data('capacidad') >= $draggable.data('cantidad')) {
						$droppable.css('background-color', 'green');
					} else if (($droppable.data('capacidad') * (1 + ($('#sobreocupar').val() / 100) ) ) >= $draggable.data('cantidad')  ) {
						$droppable.css('background-color', 'yellow');
					} else { 
						$droppable.css('background-color', 'red');
					}
					 
				  },
				  deactivate: function(event, ui) {
					  
					  $(this).css('background-color', '#fff');
				  },
				  drop: function( event, ui ) {
						//console.log(ui.draggable.data());
						$droppable = $(this);
						$(this).find('.activate-added').remove();
						var cantidad = 0;
						cantidad = Math.min($droppable.data('capacidad'), parseInt(ui.draggable.data('cantidad')));
						var getValues = {};
						getValues.act = 'asignacionDeMateria'; 
						getValues.aula = $droppable.data('cod');
						getValues.materia = ui.draggable.data('id'); 
						getValues.cantidad = cantidad;
						getValues.dia = $('#dia').val();
						getValues.turno = ui.draggable.data('turno');
						getValues.anio = $('#anio').val();
						getValues.cuatrimestre = $('#cuatrimestre').val();
						if ( $('#asignarTodosLosDias').prop('checked')) {
							getValues.asignarTodosLosDias = "checked";
						}
						//console.log(getValues);
						
						$.get('fuentes/aulificatorAJAX.php', getValues, function(data) {
							if (data.error) {
								alert(data.error);
							} else {
								comision = data.comision;
								id_asignacion = data.id_asignada;
								dataDroppable = {
									"aula": $droppable.data('cod'), 
									"materia": ui.draggable.data('id'), 
									"cantidad_asignada": cantidad,
									"dia": $('#dia').val(),
									"turno": ui.draggable.data('turno'),
									"comision": comision,
									"anio": $('#anio').val(),
									"cuatrimestre": $('#cuatrimestre').val(),
									"nombre": ui.draggable.data('nombre'),
									"id_asignacion": id_asignacion,
									
								};
								$droppable.removeClass('disponible')
									.data( dataDroppable );
								
								$droppable.find( "td.disponible" ).text( ui.draggable.data('id') + String(comision) )
									.removeClass('disponible');
								
								
								$nombre = $('<tr class="aula materia center-text"><td class="aula nombre-materia center-text" colspan="3">' + ui.draggable.data('nombre') + '</td></tr>');
								$inscriptos = $('<tr class="aula materia center-text"><td class="aula materia center-text cantidad-asignada" colspan="3">(' + cantidad + 'Alumnos)</td></tr>');
								$droppable.find('table')
									.append($nombre)
									.append($inscriptos);
								
								ui.draggable.data('cantidad', parseInt(ui.draggable.data('cantidad')) - cantidad);
								ui.draggable.find('span.faltaAsignar').text(ui.draggable.data('cantidad'));
								ui.draggable.fadeOut();
								if (ui.draggable.data('cantidad')) {
									ui.draggable.fadeIn();
								}
								$droppable.droppable('destroy');
								$droppable.draggable({
									revert:'invalid',
									helper: function() {
												return $( "<div class='ui-widget-header' style='width:120px;'> " + $(this).text() + "</div>" );
											},
									opacity: .7,
									cursor: "move",
									cursorAt: { top: 60, left: 60 },
								});
								
								if (data.borradas) {
									$.each(data.borradas, function(key, val) {
										alert('Se ha quitado la asignación de la materia ' + val.materia + ' del aula ' + val.aula + ' los días ' + val.dias + ' en el turno ' + val.turno);
									});
								}
							}
						}, 'json');
				  },
				});
			};
			
			function actualizarGrillaAulas() {
				formValues = $('#formulario_asignacion').serialize();
				formValues += "&act=grillaDeAulas";
				
				$('#aulas, #listadoMaterias').empty();
				$('#aulas, #listadoMaterias').append($loaderDiv);
				
				$.get('fuentes/aulificatorAJAX.php', formValues, function(data, success) {
					$('#aulas').empty();
					$.each(data, function(key, val) {
						$containerAula = $("<div class='aula'></div>");
						$containerAula.data(val)
						
							
						$containerAula.append("<table class='aula'></table>");
						$tablaContainer = $containerAula.find('table');
						
						lock = "locked";
						if (val.abierta == 1) {
							lock = "unlocked";
						}
						
						$codigo = $("<tr class='aula codigo'></tr>");
						$codigo.append("<td class='aula codigo left-text'>" + val.cod + "</td>")
							.append("<td class='aula codigo center-text " + lock + "'></td>")
							.append("<td class='aula capacidad right-text'>" + val.capacidad + "A</td>");
						
							
						$tablaContainer.append($codigo);
						
						
						
						if (val.id_asignacion) {
							
							$asignacion = $("<tr class='aula materia'></tr>");
							$asignacion.append("<td class='aula materia center-text materia-comision' colspan='3'>" + val.materia + val.comision + "</td>");
							$tablaContainer.append($asignacion);
							
							$materia = $("<tr class='aula materia'></tr>");
							$materia.append("<td class='aula nombre-materia center-text' colspan='3'>" + val.nombre + "</td>");
							$tablaContainer.append($materia);
							
							$cantidad = $("<tr class='aula materia'></tr>");
							$cantidad.append("<td class='aula materia center-text cantidad-asignada' colspan='3'>(" + val.cantidad_asignada + " Alumnos)</td>");
							$tablaContainer.append($cantidad);
							
							$containerAula.draggable({
								revert:'invalid',
								helper: function() {
											return $( "<div class='ui-widget-header' style='width:120px;'> " + $(this).text() + "</div>" );
										},
								opacity: .7,
								cursor: "move",
								cursorAt: { top: 60, left: 60 },
							});
							
							if (val.abierta == 0) {
								$containerAula.draggable('disable');
							}
							
							/*$containerAula.data('materia', val.materia)
								.data('comision', val.comision)
								.data('nombre', val.nombre);
								.data('cantidad_asignada', val.cantidad_asignada);*/
						} else {
							disponible = "";
							if (val.abierta == 1) {
								disponible = "Disponible";
							}
							$containerAula.addClass('disponible');
								$asignacion = $("<tr class='aula materia'></tr>");
								$asignacion.append("<td class='aula materia center-text disponible' colspan='3'>" + disponible + "</td>");
								$tablaContainer.append($asignacion);
							
						}
						
							//$tablaContainer.append($asignacion);
							
							$('#aulas').append($containerAula);
					});
					actualizarlistadoMaterias();
					asignarDroppables();
					
					$('#aulas').find('#aulas div.cssload-container').remove();
				}, 'json');
			};
			
			
			actualizarGrillaAulas();
			//actualizarlistadoMaterias();
			
			$('select.filtros, input.filtros').change(function() {
				actualizarGrillaAulas();
			});
			
			$('#formulario_asignacion').on('submit', function(event) {
				event.preventDefault();
				var values = $(this).serialize();
				$('#aulas, #listadoMaterias').addClass('rotador');
				$.get('autoasignaraulas.php', values, function(data) {
					//console.log(data);
					actualizarGrillaAulas();
					//alert('terminado');
					$('#aulas, #listadoMaterias').removeClass('rotador');
					
				});
			});
			
			$('#reiniciarAsignaciones').on('click', function(event) {
				var act = "reiniciarGrilla"; 
				var codigos = "("; 
				$('div.aula:not(.disponible)').each(function(index) {
					codigos += $(this).data('id_asignacion') + ",";
				});
				codigos += "null)";
				
				
				
				$.get('fuentes/aulificatorAJAX.php', {'act':act, 'codigos':codigos}, function(data) {
					//alert('reiniciado');
					actualizarGrillaAulas();
				}, 'html');
			});
					
			
			
			$('div.materia').draggable({
				revert:true,
				helper: function() {
					        return $( "<div class='ui-widget-header' style='width:120px;'> " + $(this).text() + "</div>" );
						},
				cursor: "move",
				cursorAt: { top: 60, left: 60 },
			});
			
			$('div.dialog').dialog({
				autoOpen:false,
				width: '38%',
				modal:true,
				
			});
				
			
			$('#aulas').on('click', 'div.aula', function(event) {
				$this = $(event.target);
				if ($this.is('td.locked') ) {
					event.stopPropagation();
					$this.removeClass('locked');
					$this.addClass('unlocked');
					aula = $this.prev().text();
					
					$this.parent().next().children('td.disponible').text('Disponible');
					
					$this.closest('div.aula.ui-draggable').draggable('enable');
					
					$this.closest('div.aula.ui-droppable').droppable('enable');
					$.get('fuentes/aulificatorAJAX.php', {'act': 'lock', 'valor': '1', 'aula': aula}, function(data) {
						
					});
				} else if ($this.is('td.unlocked')) {
					event.stopPropagation();
					$this.removeClass('unlocked');
					$this.addClass('locked');
					aula = $this.prev().text();
					$this.parent().next().children('td.disponible').text('');
					$this.closest('div.aula.ui-draggable').draggable('disable');
					$this.closest('div.aula.ui-droppable').droppable('disable');
					$.get('fuentes/aulificatorAJAX.php', {'act': 'lock', 'valor': '0', 'aula': aula}, function(data) {
						
					});
				} else if ($this.is(':not(.disponible)')) {
					
					data = $(this).data();
					$aula = $(this);
					//console.log(data);
					formValues = $('#formulario_asignacion').serialize();
					formValues += "&act=inscriptosMateria&materia=" + data.materia;
					
					data.faltaAsignar = $('div.materia[id="' + data.materia + '"]').data('cantidad') || 0;
					
					$dialog = $('#dialogAula');
					$dialog.dialog('open');
					$dialog.find('#materia').empty();
					$dialog.find('#cantidadAsignada').val('');
					dialogHtml = $dialog.html();
					$dialog.empty();
					$dialog.append($loaderDiv);
					$.get('fuentes/aulificatorAJAX.php', formValues, function(response) {
						data.inscriptos = response;
						
						$dialog.empty();
						$dialog.html(dialogHtml);
						$dialog.find('#materia').html(data.materia + '<br />' + data.nombre + '<br />(' + data.inscriptos + ' Inscriptos)');
						
						if (data.faltaAsignar) {
							html = $dialog.find('#materia').html();
							$dialog.find('#materia').html(html + "<br />Falta asignar: <span class='faltaAsignar'>" + data.faltaAsignar + "</span> Alumnos");
						}
						
						sobreOcupar = $('#sobreocupar').val();
						maxAsignable = Math.min(Math.round(parseInt(data.capacidad) * ((1 + sobreOcupar) / 100) ), parseInt(data.cantidad_asignada) + data.faltaAsignar);
						minAsignable = $('#corteMinimo').val();
						$dialog.find('#cantidadAsignada')
							.attr('max', maxAsignable)
							.attr('min', minAsignable)
							.val(data.cantidad_asignada);
						$dialog.dialog({
							title: "Aula " + data.cod + " (" + data.capacidad + " alumnos)",
						});
						
						//INTERCEPCIÓN DE EL ENVÍO PARA MANDAR AL AJAX EL AJUSTE
						$('#ajusteAula').submit(function(event) {
							event.preventDefault();
							var values = {};
							values.cantidad = $(this).find('#cantidadAsignada').val();
							values.id_asignacion = data.id_asignacion;
							values.ajuste = values.cantidad - data.cantidad_asignada;
							values.faltaAsignar = data.faltaAsignar - values.ajuste;
							data.faltaAsignar = values.faltaAsignar;
							$.get('fuentes/aulificatorAJAX.php?act=ajustarAula', values , function(response) {
								//AJUSTAR LA PARTE GRÁFICA
								//Sumar al listado de materias (ajustar la data)
								
								$materia = $('div.materia[id="' + data.materia + '"]');
								//console.log($materia);
								$materia.fadeOut();
								$materia.find('span.faltaAsignar').text(values.faltaAsignar);
								$materia.data('cantidad', values.faltaAsignar);
								if (values.faltaAsignar > 0) {
									$materia.fadeIn();
								}
								//Mostrar la nueva cantidad en el aula (ajustar la data)
								//data.faltaAsignar = values.faltaAsignar;
								data.cantidad_asignada = values.cantidad;
								$aula.find('td.cantidad-asignada').hide()
									.text('(' + values.cantidad + ' Alumnos)')
									.first()
									.fadeIn();
								
								
								//Ajustar el Dialog
								$spanFaltaAsignar = $dialog.find('span.faltaAsignar')
								$spanFaltaAsignar.hide().text(data.faltaAsignar).fadeIn();
							}, 'html');
							
						});
					});
				}	
					
			});
			
			$('div.trashcan').droppable({
					accept: 'div.aula',
				  activeClass: "droppable-active-trashcan",
				  hoverClass: "droppable-hover-trashcan",
				  drop: function( event, ui ) {
						$droppable = $(this);
						
						idAsignacion = ui.draggable.data('id_asignacion');
						$.get('fuentes/aulificatorAJAX.php', {'act': 'eliminarAsignacion', 'id': idAsignacion}, function(data) {
							//GESTIÓN DEL AULA
							ui.draggable.addClass('disponible');
							ui.draggable.find('tr.materia td').empty();
							ui.draggable.find('tr.materia td').first().html('<b>Disponible</b>').addClass('disponible');
							
							//AGREGAR MATERIA
							data = ui.draggable.data();
							$materiaBuscada = $('div.materia[id="' + data.materia + '"]'); 
							
							if ( $materiaBuscada.length > 0 ) {
								
								//console.log($materiaBuscada.data());
								$materiaBuscada.data('cantidad', parseInt($materiaBuscada.data('cantidad')) + parseInt(data.cantidad_asignada));
								
								$materiaBuscada.find('span.faltaAsignar').text($materiaBuscada.data('cantidad'));
								$materiaBuscada.fadeOut();
								$materiaBuscada.fadeIn();
								
							} else {
								$containerMateria = $("<div class='materia' id='" + data.materia + "'></div>");
								$containerMateria.html(data.materia + ' - ' + data.nombre + ' (Faltan asignar: <span class="faltaAsignar">' + (data.cantidad_asignada) + '</span> Alumnos)'  )
								.data('id', data.materia)
								.data('cantidad', data.cantidad_asignada)
								.data('nombre', data.nombre)
								.appendTo('#listadoMaterias')
								.draggable({
									revert:'invalid',
									helper: function() {
												return $( "<div class='ui-widget-header' style='width:120px;'> " + $(this).text() + "</div>" );
											},
									opacity: .7,
									cursor: "move",
									cursorAt: { top: 60, left: 60 },
								});
							}
							
							asignarDroppables();
							//console.log('ok');
							//BORRAR LA DATA
							
						}, 'html');
						
						
						 
				},
			});
			
			
		});
		
		
	</script>
		
</html>

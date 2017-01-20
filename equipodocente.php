<!DOCTYPE html>
<html>
	<head>
		
		<title>Plantel docente 1er cuatrimestre 2017</title>
		
		<?php 
			require_once('./fuentes/meta.html');
			include './fuentes/constantes.php';
			$ANIO = 2017;
			$CUATRIMESTRE = 1;
			require_once 'programas.autoloader.php';
		?>
		
	</head>
	
	<body>
		
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Equipo docente 1er cuatrimestre 2017</h2>
			<div id="plantelActual">
				<table class="plantelActual">
					<thead class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:70%;">Docente</th>
							<th class="plantelActual" style="width:30%;">Cargo</th>
							<!--<th class="plantelActual" style="width:20%;">Carácter</th>-->
							<!--<th class="plantelActual" style="width:25%;">Fecha de Ingreso</th>-->
							<!--<th class="plantelActual" style="width:20%;">Eliminar</th>-->
						</tr>
					</thead>
					<tbody class="plantelActual">
					</tbody>
				</table>
			</div>
			<hr>
			<div id="formulario1">
				<fieldset class="formularioLateral">
					<form method="get" class="formularioLateral" for="./fuentes/AJAX.php?act=agregarAfectacion" id="agregarDocente">
						<label class="formularioLateral" for="dni">Docente:</label>
						<Select class="formularioLateral iconId" name="dni" id="dniDocente"/>
							<option value="" selected="selected">Seleccione de la lista - Escriba para buscar</option>
							<?php
								require "./conexion.php";
								$query = "SELECT dni, apellido, nombres FROM docente ORDER BY apellido, nombres";
								$result = $mysqli->query($query);
								
								while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
									echo "<option value='$row[dni]'>$row[dni] - $row[apellido], $row[nombres]</option>";
								}
								$result->free();
								$mysqli->close();
							?>
						</select>
						<img src="./images/icons/info.png" alt="Info" title="Ahora puede buscar docentes por nombre o dni. En caso de error o falta de un docente, informe a weeyn@unsam.edu.ar" height="20px" style="cursor:help;margin-left:35px;">
						<br />
						<label class="formularioLateral" for="cargo">Cargo: </label>
						<select name="tipo" class="formularioLateral iconDocente" id="cargoDocente" required="required">
							<option class="formularioLateral" value="">Seleccione cargo</option>
							<option class="formularioLateral iconDocente" value="Titular">Titular</option>
							<option class="formularioLateral iconDocente" value="Asociado">Asociado</option>
							<option class="formularioLateral iconDocente" value="Adjunto">Adjunto</option>
							<option class="formularioLateral iconDocente" value="Jtp">JTP</option>
							<option class="formularioLateral iconDocente" value="Ayudante graduado">Ayudante graduado</option>
							<option class="formularioLateral iconDocente" value="Ayudante alumno">Ayudante alumno</option>
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
			
			var actualizarTabla = function() {
				//formValues = $('form.filtros').serialize();
				//console.log(formValues);
				$('tbody.plantelActual').load("fuentes/AJAX.php?act=tablaEquipoDocente", function(data2) {
					
					$('.botonEliminar').click(function() {
						id = $(this).data('id');
						$.post("./fuentes/AJAX.php?act=eliminarAfectacion", {"id":id, }, function(data1) {
							console.log(data1);
							actualizarTabla();
							
						});
					});
				});
			} 
			actualizarTabla();
			
			$("form#agregarDocente").submit(function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values.act = "agregarAfectacion";
				
				$.get("./fuentes/AJAX.php?act=agregarAfectacion", values, function(data1) {
					console.log(data1);
					if (data1.indexOf('success') != -1) {
						actualizarTabla();
					} else {
						$.get("./fuentes/AJAX.php?act=errorLogging", {"error": data1}, function(data) {
							if (data1.indexOf('Duplicate') != -1) {
								alert("El docente ya forma parte del equipo");
							} else { 
								alert('Error inesperado en la carga del docente, se ha notificado al webmaster. Inténtelo nuevamente más tarde. Si el error perdura, comuníquese a weeyn@unsam.edu.ar');
							}
							console.log(data);
							actualizarTabla();
							
						});
						
					}
				});
			});
			
			
			$("#botonContinuar").click(function() {
				location.assign("./asignarcomisiones.php");
			});
			
			$("select").combobox();
			
		});
	</script>
	
	
	<script>
		
	//COMBOBOX CON BÚSQUEDA
	  (function( $ ) {
		$.widget( "custom.combobox", {
		  _create: function() {
			this.wrapper = $( "<span>" )
			  .addClass( "custom-combobox" )
			  .insertAfter( this.element );
	 
			this.element.hide();
			this._createAutocomplete();
			this._createShowAllButton();
		  },
	 
		  _createAutocomplete: function() {
			var selected = this.element.children( ":selected" ),
			  value = selected.val() ? selected.text() : "";
	 
			this.input = $( "<input>" )
			  .appendTo( this.wrapper )
			  .val( value )
			  .attr( "title", "" )
			  .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
			  .autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" )
			  })
			  .tooltip({
				tooltipClass: "ui-state-highlight"
			  });
	 
			this._on( this.input, {
			  autocompleteselect: function( event, ui ) {
				ui.item.option.selected = true;
				this._trigger( "select", event, {
				  item: ui.item.option
				});
			  },
	 
			  autocompletechange: "_removeIfInvalid"
			});
		  },
	 
		  _createShowAllButton: function() {
			var input = this.input,
			  wasOpen = false;
	 
			$( "<a>" )
			  .attr( "tabIndex", -1 )
			  .attr( "title", "Show All Items" )
			  .tooltip()
			  .appendTo( this.wrapper )
			  .button({
				icons: {
				  primary: "ui-icon-triangle-1-s"
				},
				text: false
			  })
			  .removeClass( "ui-corner-all" )
			  .addClass( "custom-combobox-toggle ui-corner-right" )
			  .mousedown(function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
			  })
			  .click(function() {
				input.focus();
	 
				// Close if already visible
				if ( wasOpen ) {
				  return;
				}
	 
				// Pass empty string as value to search for, displaying all results
				input.autocomplete( "search", "" );
			  });
		  },
	 
		  _source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			response( this.element.children( "option" ).map(function() {
			  var text = $( this ).text();
			  if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
			}) );
		  },
	 
		  _removeIfInvalid: function( event, ui ) {
	 
			// Selected an item, nothing to do
			if ( ui.item ) {
			  return;
			}
	 
			// Search for a match (case-insensitive)
			var value = this.input.val(),
			  valueLowerCase = value.toLowerCase(),
			  valid = false;
			this.element.children( "option" ).each(function() {
			  if ( $( this ).text().toLowerCase() === valueLowerCase ) {
				this.selected = valid = true;
				return false;
			  }
			});
	 
			// Found a match, nothing to do
			if ( valid ) {
			  return;
			}
	 
			// Remove invalid value
			this.input
			  .val( "" )
			  .attr( "title", value + " didn't match any item" )
			  .tooltip( "open" );
			this.element.val( "" );
			this._delay(function() {
			  this.input.tooltip( "close" ).attr( "title", "" );
			}, 2500 );
			this.input.autocomplete( "instance" ).term = "";
		  },
	 
		  _destroy: function() {
			this.wrapper.remove();
			this.element.show();
		  }
		});
	  })( jQuery );
 

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

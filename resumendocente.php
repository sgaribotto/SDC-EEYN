		<?php
			header('Content-Type: text/html; charset=utf-8');
			require_once 'programas.autoloader.php';
			include './fuentes/constantes.php';
			$docente = new clases\Docente($_REQUEST['docente']);
			$datosDocente = array(
				'personales' => $docente->mostrarDatosDocente(),
				'contacto' => $docente->mostrarDatosContacto(),
				'afectaciones' => $docente->mostrarAfectaciones($ANIO, $CUATRIMESTRE),
				'designaciones' => $docente->mostrarDesignaciones($ANIO, $CUATRIMESTRE),
			);
			session_start();
		?>

		<div class="">
			<h2 class="formularioLateral tituloMateria">
				<?php 
					echo $datosDocente['personales']['apellido'];
					echo ", ";
					echo $datosDocente['personales']['nombres']; 
				?>
			</h2>
			<table class='aceptarDesignacion'><thead class='aceptarDesignacion'>
						<tr class='plantelActual'>
							<th class='aceptarDesignacion' style='width:50%;'>Materia</th>
							<!--<th class='aceptarDesignacion' style='width:10%;'>Carrera</th>-->
							
							<th class='aceptarDesignacion' style='width:20%;'>Cargo</th>
							<th class='aceptarDesignacion' style='width:30%;'>Comision</th>
							<!-- <th class='aceptarDesignacion' style='width:25%;'>Estado</th> -->
						</tr></thead>
				<tbody class="tablaInfo" style="width:100%;">
				<?php 
					foreach ($datosDocente['afectaciones'] as $afectacion) {
						
						echo "<tr class='aceptarDesignacion'>
								<td class='aceptarDesignacion'>$afectacion[conjunto] $afectacion[materia]</td>
								<td class='aceptarDesignacion'>$afectacion[tipoafectacion]</td>
								<td class='aceptarDesignacion'>$afectacion[comision]</td>
							</tr>";
					} ?>
				</tbody>
			</table>
			
			<hr />
			
			<h3 class="formularioLateral tablaInfo" style="margin:0;">Datos de contacto</h3>			
			<ul class="datosContacto">
				<?php
					
					foreach ($datosDocente['contacto'] as $datos) {
						
						
						$anchor[0] = "";
						$anchor[1] = "";
						if (strpos($datos['tipo'], 'ail')) {
							$anchor[0] = "<a href='mailto:$datos[valor]'>";
							$anchor[1] = "</a>"; 
						} elseif (strpos($datos['tipo'], 'elefono')) {
							$string = (string)$datos['valor'];
							//var_dump($string);
							if (strlen($string) == 10) {
								$formato = "";
								$formato .= substr($string, 0, 3);
								$formato .= ' - ';
								$formato .= substr($string, 3, 3);
								$formato .= ' - ';
								$formato .= substr($string, 6, 4);
								$datos['valor'] = $formato;
							} else if (strlen($string) == 8) {
								$formato = "";
								$formato .= substr($string, 0, 4);
								$formato .= ' - ';
								$formato .= substr($string, 4, 7);
								$datos['valor'] = $formato;
							}
						}
								
						echo "<li class='datosContacto'>";
						echo $datos['tipo'] . ": ";
						echo $anchor[0];
						echo $datos['valor'];
						echo $anchor[1];
						echo "</li>";
					}
				?>
			</ul>
		</div>
		
		<?php if (in_array(2, $_SESSION['permiso'])) { //SOLO SA ?>
			<hr />
			<div class="infoDesignacion">
				<h2 class="subtitulo">Designaciones</h2>
				<ul class="designacion">
				<?php
					foreach ($datosDocente['designaciones'] as $designacion) {
						echo "<li class='designacion'>" . 
							$designacion['dedicacion'] . 
							" (" . $designacion['categoria'] . ")" . "</li>";
					}
				?>
				</ul>
			</div>
		<?php } ?> 
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			
			$("#agregarDocente").submit(function(event) {
				event.preventDefault();
				$('.errorValidar').text(''); //RESETEO EL MENSAJE DE ERROR
				dniDocente = $('#dniDocente').val();
				//nombreDocente = $('#nombreDocente').val();
				cargoDocente = $('#cargoDocente').val();
				
				var url = $('h2.tituloMateria').text();
				url = url.substr(1, 6);
				url = parseFloat(url);	
							
				materia = url;
				
				if (cargoDocente != "" && dniDocente != "") {
					$.get("./fuentes/AJAX.php", {"act":"agregarAfectacion", "dni":dniDocente, "tipo":cargoDocente, "materia":materia}, function(data) {
						if (data == 1) {
							console.log(data);
								
							$('#dialogResumenMateria').empty();	
							$('#dialogResumenMateria').load('resumenmateria.php?materia=' + url);
						} else {
							$.get("./fuentes/AJAX.php?act=errorLogging", {"error": "agregarAfectación"}, function(data) {
								alert('Error en la carga del docente, se ha notificado al webmaster. Inténtelo nuevamente más tarde. Si el error perdura, comuníquese a weeyn@unsam.edu.ar');
								//console.log(data);
							});
							
						}
					});
				} else {
					
					/*if (cargoDocente == "") {
						$('.errorValidar').text('Debe elegir un cargo');
					}
					
					if ( dniDocente == "" ) {
						$('.errorValidar').text('Debe elegir un docente');
					}*/
				}
			});
			
			
			
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
		$("select.combo").combobox();
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
	  .resaltar {
		  color:#D4A190;
		  font-weight:bold;
	  }
	  
  </style>


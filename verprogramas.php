<!DOCTYPE html>
<html>
	<head>
		
		<title>Programa</title>
		<?php 
			require_once('./fuentes/meta.html');
			include './fuentes/constantes.php';
			require_once 'programas.autoloader.php';
			
			if (!isset($_SESSION['materiaTemporal'])) {
				$_SESSION['materiaTemporal'] = '1005';
			}
			if (!isset($_SESSION['cuatrimestreTemporal'])) {
				$_SESSION['cuatrimestreTemporal'] = $ANIO . ' - ' . $CUATRIMESTRE;
			}
			
			$periodo = explode(' - ', $_SESSION['cuatrimestreTemporal']);
			$ANIO = $periodo[0];
			$CUATRIMESTRE = $periodo[1];
			
			$materia = new clases\Materia($_SESSION['materiaTemporal']);
			$programa = new clases\Programa($_SESSION['materiaTemporal'], $_SESSION['id']);
			$camposPrograma = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);
			
		?>
		
	</head>
	
	<body>
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		
		<div class="programaCompleto">
			<h1 class="programaCompleto">Plan de estudios <?php echo $materia->datosMateria['nombre']; ?></h1>
				<form class="programaCompleto" method="post" action="./fuentes/AJAX.php?act=traerProgramaMateria" id="mostrarPrograma">
					<label for="materia" class="formularioLateral">Materia: </label>
					<select name="materia" class="formularioLateral iconMateria" id="materia">
						
						<?php 
							require "./conexion.php";
							$query = "SELECT id, cod, nombre FROM materia WHERE activo = 1";
							$result = $mysqli->query($query);
							
							while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
								$selected = "";
								if ($_SESSION['materiaTemporal'] == $row['cod']) {
									$selected = "selected='selected'";
								}
								echo "<option value='$row[cod]' $selected>$row[cod] - $row[nombre]</option>";
							}
							
							$result->free();
							$mysqli->close();
						?>
							
					</select>
					<br />				
					<label for="periodo" class="formularioLateral">Periodo lectivo:</label>
					<select name="periodo" class="formularioLateral iconCalendario"  id="periodo" style="width:150px;">
						<?php 
							require "./conexion.php";
							$query = "SELECT DISTINCT anio, cuatrimestre FROM programa ORDER by anio, cuatrimestre" ;
							$result = $mysqli->query($query);
							
							while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
								$selected="";
								if ($_SESSION['cuatrimestreTemporal'] == "$row[anio] - $row[cuatrimestre]") {
									$selected = "selected='selected'";
								}
								echo "<option value='$row[anio] - $row[cuatrimestre]' $selected>$row[anio] - $row[cuatrimestre]</option>";
							}
							
							$result->free();
							$mysqli->close();
						?>
							
					</select>
					
					<button type="submit" class="programaCompleto" style="margin-left:45px;">Mostrar programa</button>
					<button type="button" class="programaCompleto imprimir" id="botonImprimir">Imprimir</button>
				</form>
				
				<div id="accordion">
					<h2 class="programaCompleto">Datos Generales</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto">Nombre: <?php echo $materia->datosMateria['nombre']; ?></p>
						<p class="programaCompleto">Carrera: <?php echo $materia->datosMateria['carrera']; ?></p>
						<p class="programaCompleto">Plan: <?php echo $materia->datosMateria['plan']; ?></p>
						<p class="programaCompleto">Año: <?php echo round($materia->datosMateria['cuatrimestre'] / 2); ?></p>
						<p class="programaCompleto">Cuatrimestre: <?php echo (($materia->datosMateria['cuatrimestre'] % 2) == 1) ? 1 : 2; ?></p>
					</div>
					
					<h2 class="programaCompleto">Correlatividades</h2>
					<div class="detalleProgramaCompleto">
						<table id="correlatividades" class="formularioLateral correlatividadesTable" style="width:100%;">
							<tr class="formularioLateral correlatividadesTable" style="border-bottom:1px solid black;">
								<th class="formularioLateral correlatividadesTable" style="width:170px;border-bottom:1px solid #CCC;">Código</th>
								<th class="formularioLateral correlatividadesTable" style="border-bottom:1px solid #CCC;">Materia</th>
							</tr>
							<?php
								$correlativas = $materia->mostrarCorrelativas();
								
								
								if (!is_array($correlativas)) {
									echo "<tr><td colspan='2'>No hay correlatividades cargadas</td></tr>";
								} else {
								
									foreach ($correlativas as $cod => $nombreMateria) {
										echo "<tr class='formularioLateral correlatividadesTable'>
												<td class='formularioLateral correlatividadesTable'>$cod</td>
												<td class='formularioLateral correlatividadesTable'>$nombreMateria</td>
											</tr>";
									}
								}
								
								
							?>
						</table>
					</div>
					
					<h2 class="programaCompleto">Contenidos mínimos</h2>
					<div class="detalleProgramaCompleto">
						<?php
								$contenidosMinimos = $materia->mostrarDato('contenidosminimos');
								echo "<p class='formularioLateral contenidosMinimos'>$contenidosMinimos</p>";
							?>
					</div>
					
					<h2 class="programaCompleto">Equipo docente</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:40%;">Docente</th>
							<th class="plantelActual" style="width:20%;">Cargo</th>
							<!--<th class="plantelActual" style="width:20%;">Carácter</th>-->
							<th class="plantelActual" style="width:20%;">Fecha de Ingreso</th>
							<th class="plantelActual" style="width:20%;">Estado</th>
						</tr>
						<?php
							$equipoDocente = $materia->mostrarEquipoDocente('*', $ANIO, $CUATRIMESTRE, true);
							
							if (empty($equipoDocente)) {
								echo "<tr><td colspan='2'>No hay docentes cargados</td></tr>";
							} else {
							
								foreach ($equipoDocente as $row) {
									echo "<tr class='formularioLateral correlatividadesTable'>
												<td class='formularioLateral correlatividadesTable'>$row[docente]</td>
												<td class='formularioLateral correlatividadesTable'>$row[tipoafectacion]</td>
												
											</tr>";
								}
								
								
							}
						?>
					</table>
					</div>
					
					<h2 class="programaCompleto">Objetivos</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto">
							<?php 
								$detalle = (isset($camposPrograma['objetivos'])) ? $camposPrograma['objetivos'] : ""; 
								echo $detalle; 
							?>
						</p>
					</div>
												
					<h2 class="programaCompleto">Enfoque metodológico</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto">
							<?php 
								$detalle = (isset($camposPrograma['fundamentacion'])) ? $camposPrograma['fundamentacion'] : ""; 
								echo $detalle; 
							?>
						</p>
					</div>
					
					<h2 class="programaCompleto">Unidades temáticas</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
							<tr class="plantelActual">
								<th class="plantelActual" style="width:15%;">Unidad</th>
								<th class="plantelActual" style="width:80%;">Descripción</th>
								<!--<th class="plantelActual" style="width:20%;">Carácter</th>
								<th class="plantelActual" style="width:20%;">Fecha de Ingreso</th>
								<th class="plantelActual" style="width:20%;">Estado</th>-->
							</tr>
							<?php
								$unidadesTematicas = $materia->mostrarUnidadesTematicas("*", $ANIO, $CUATRIMESTRE, true);
								
								if (empty($unidadesTematicas)) {
									echo "<tr><td colspan='2'>No hay unidades cargadas</td></tr>";
								} else {
								
									foreach ($unidadesTematicas as $key => $value ) {
										echo "<tr class='formularioLateral plantelActual'>
													<td class='formularioLateral plantelActual'>$key</td>
													<td class='formularioLateral plantelActual'>$value</td>
													
													</tr>";
									
									}
								}
							?>
						</table>
					</div>
					
					<h2 class="programaCompleto">Evaluación y criterios de aprobación</h2>
					<div class="detalleProgramaCompleto">
						<p class="programaCompleto">
							<?php 
								$detalle = (isset($camposPrograma['evaluacion'])) ? $camposPrograma['evaluacion'] : ""; 
								echo $detalle; 
							?>
						</p>
					</div>
					
					<h2 class="programaCompleto">Bibliografía</h2>
					<div class="detalleProgramaCompleto">
						<table class="plantelActual">
						<tr class="plantelActual">
							<th class="plantelActual" style="width:40%;">Título</th>
							<th class="plantelActual" style="width:30%;">Autor</th>
							<th class="plantelActual" style="width:20%;">Editorial</th>
							<th class="plantelActual" style="width:10%;">Páginas</th>
							<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
						</tr>
						<?php
							
							
							
							
							$bibliografia = $materia->mostrarBibliografia($ANIO, $CUATRIMESTRE, true);
							
							if (empty($bibliografia)) {
								echo "<tr><td colspan='2'>No hay bibliografía cargadas</td></tr>";
							} else {
							
								foreach ($bibliografia as $key => $value ) {
									echo "<tr class='formularioLateral plantelActual'>
												<td class='formularioLateral plantelActual'>$value[titulo]</td>
												<td class='formularioLateral plantelActual'>$value[autor]</td>
												<td class='formularioLateral plantelActual'>$value[editorial]</td>
												<td class='formularioLateral plantelActual'>$value[paginas]</td>
												</tr>";
								
								}
							}
								

						?>
					</table>
					</div>
				</div>
			</div>
		</body>
			
			<script src="./fuentes/funciones.js"></script>
			
			<script>
				$(document).ready(function() {
					
					$("#accordion").accordion({
						collapsible:true,
						heightStyle:'content'
						
					});
					
					$('form#mostrarPrograma').submit(function(event) {
						event.preventDefault();
						values = $(this).serialize();
						
						$.post("./fuentes/AJAX.php?act=traerProgramaMateria", values, function(data) {
							location.reload();
						});
					});
					
					$('#botonImprimir').click(function(event) {
						location.assign('modeloprograma.php');
					});
					
					$('select').combobox();
					
					$('button').button();
				
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

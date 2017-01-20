<!DOCTYPE html>
<html>
	<head>
		
		<title>Contactos docentes</title>
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
			<img src="./images/logo_eeyn.png" class="logo-print print-only" Alt="EEYN - UNSAM"/>
			
				<?php
					
					
					$materia = new clases\Materia($_SESSION['materia']);

					$inscriptosMateria = $materia->mostrarInscriptos($ANIO, $CUATRIMESTRE);
					$correlativas = $materia->mostrarCorrelativas();
					$ratios = $materia->mostrarRatioAprobacion($ANIO, $CUATRIMESTRE);
					$inscriptosPorTurno = $materia->mostrarInscriptosPorTurno($ANIO, $CUATRIMESTRE);
					$carrera = $materia->datosMateria['cod_carrera'];
					
					$inscriptosEstimados = $materia->mostrarEstimacionPreliminar($ANIO, $CUATRIMESTRE, $carrera);
					
					//print_r($_SESSION);
				?>

				
					
				<div class="">
					<h2 class="formularioLateral tituloMateria">
						<?php 
							print_r($materia->mostrarConjunto());
							print_r($materia->mostrarNombresConjunto());
						?>
					</h2>
					
					<h4 class='formularioLateral responsables'>Responsable: 
						
						<?php
							$responsables = $materia->mostrarResponsable();
							foreach ($responsables as $nro => $responsable) {
								if ($nro > 0) {
									echo " / ";
								}
								echo $responsable;
							}
						?>
					</h4>

					<!--<h3 class="formularioLateral tablaInfo" style="margin:0;">Cuatrimestres anteriores</h3>-->
					<div class="chart" id="chart_div"></div>		
					<div class="marcoTablaResumenMateria">
						<!--<table class="formularioLateral tablaInfo">
							<thead class="formularioLateral tablaInfo">
								<tr class="formualrioLateral tablaInfo">
									<th class="formularioLateral tablaInfo">Periodo</th>
									<th class="formularioLateral tablaInfo">Inscriptos</th>
									<th class="formularioLateral tablaInfo">Profesores</th>
									<th class="formularioLateral tablaInfo">Auxiliares</th>
									<th class="formularioLateral tablaInfo">Promoción</th>
									<th class="formularioLateral tablaInfo">Aprobados</th>
									<th class="formularioLateral tablaInfo">Reprobados</th>
									<th class="formularioLateral tablaInfo">Ausentes</th>
									
								</tr>
							</thead>
							<tbody class="formularioLateral tablaInfo">-->
								<?php 
									require "fuentes/conexion.php";
									
									/*$query = "SELECT CONCAT(anio_academico, '-', LEFT(periodo_lectivo, '1') ) as periodo, COUNT(*) as cantidad, 
											COUNT(DISTINCT REPLACE(IF(nombre_comision LIKE '%AE%', NULL, nombre_comision), materia, '') ) as cantidad_comisiones
										FROM inscriptos
										WHERE materia IN {$materia->datosMateria['conjunto']} AND anio_academico >= 2012
										GROUP BY periodo 
										ORDER BY anio_academico DESC, periodo DESC";*/
										
									$query = "SELECT CONCAT(a.anio_academico, '-', a.periodo_lectivo) AS periodo, 
												COUNT(*) AS inscriptos,
												SUM(IF(a.resultado = 'Aprob', 1, 0)) AS aprobados,
												SUM(IF(a.resultado = 'Reprob', 1, 0)) AS reprobados,
												SUM(IF(a.resultado = 'Ausente', 1, 0)) AS ausentes,
												SUM(IF(a.resultado = 'Promocion', 1, 0)) AS promociones
											FROM actas AS a
											WHERE anio_academico >= 2012 AND materia IN {$materia->mostrarConjunto()}
												AND (anio_academico < $ANIO 
													OR (anio_academico = $ANIO 
														AND periodo_lectivo < $CUATRIMESTRE))
											GROUP BY anio_academico, periodo_lectivo
											ORDER BY anio_academico DESC, periodo_lectivo DESC";
											
									$result = $mysqli->query($query);
									//echo $mysqli->error;
									$data = array();
									$dataChart = array();
									while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
										/*$data[$row['periodo']]['inscriptos'] = $row['cantidad'];
										$data[$row['periodo']]['comisiones'] = $row['cantidad_comisiones'];*/
										$data[$row['periodo']]['inscriptos'] = $row['inscriptos'];
										$data[$row['periodo']]['aprobados'] = $row['aprobados'];
										$data[$row['periodo']]['reprobados'] = $row['reprobados'];
										$data[$row['periodo']]['ausentes'] = $row['ausentes'];
										$data[$row['periodo']]['promociones'] = $row['promociones'];
										
										$dataChart['promociones'][$row['periodo']] = $row['promociones'];
										$dataChart['aprobados'][$row['periodo']] = $row['aprobados'];
										$dataChart['reprobados'][$row['periodo']] = $row['reprobados'];
										$dataChart['ausentes'][$row['periodo']] = $row['ausentes'];
									}
									
									$tabla = array();
									foreach ($dataChart as $periodo => $calidad) {
										$tabla[$periodo] = "{ name: '" . $periodo . "', data: [";
										foreach ($calidad as $calidad => $cantidad) {
											$tabla[$periodo] .= "{name: '". $calidad . "', y:";
											$tabla[$periodo] .= $cantidad . "}, ";
										}
										$tabla[$periodo] .= "], stacking: 'normal', ";
										
										$tabla[$periodo] .= " }";
									}
									
									$tablaChart = "[" . implode(', ', $tabla) ."]";
									
									$result->free();
									$mysqli->close();
								?>
				</div>
		</div>
	</body>
	<script src="./fuentes/funciones.js"></script>
	<script>
		$(document).ready( function() {
			$('select.aceptarDesignacion').change(function() {
				var id = $(this).data('id');
				var estado = $(this).val();
				$.post("./fuentes/AJAX.php?act=cambiarEstadoDesignacion", {"id":id, "estado":estado}, function(data) {
					//console.log(data);
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
		});
	</script>
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
	
	<!-- GRAFICO HIGHCHARTS -->
	
	
	<script>
			$(document).ready( function () { 
				var labels = false;
				var options = {
					credits: {
						enabled:false,
					},
					chart: {
						renderTo: 'chart_div',
						type: 'column',
						zoomType: 'x',
						style: {'font-family': 'Cantarell'},
						events: {
						},
						
					},
					title: {
						text: 'Resultados de las cursadas',
					},
					plotOptions: {
						series: {
							dataLabels: { 
								enabled:true,
							}
						},
					},
					xAxis: {
						type: 'category',
						/*categories: <?php echo $categoriasPeriodos; ?>,*/
						title: {text: "Periodo lectivo (año - cuatrimestre)"},
						labels: {rotation: 45}
					},
					
					yAxis: {
						allowDecimals: false,
						//tickInterval: 100,
						title: {text: "Cantidad de inscriptos"},
						stackLabels: {
							enabled: true,
						},
					},
					series: <?php echo $tablaChart; ?>,
					legend: {
						enabled: true,
					},
					 exporting: {
						 
						enabled: true,
						type: 'application/pdf',
						buttons: {
							contextButton: {
								menuItems: [{
									text: 'Ver evolución',
									onclick: function () {
											
										options.plotOptions.stacking = null;
										options.chart.type = 'line';
										
										chart = new Highcharts.Chart(options);
										var charta = $('#chart_div').highcharts(),
											s = charta.series,
											sLen = s.length;
										
										for(var i =0; i < sLen; i++){
											s[i].update({
												stacking: null   
											}, false);   
										}
										
										chart.redraw();
										console.log(chart);
										/*this.exportChart({
											width: 250
										});*/
									}
								}, {
									text: 'Cambiar a columnas',
									onclick: function () {
										options.chart.type = 'column';
										chart = new Highcharts.Chart(options);
										/*this.exportChart({
											width: 250
										});*/
									}
								},{
									text: 'Cambiar a áreas',
									onclick: function () {
										options.chart.type = 'area';
										chart = new Highcharts.Chart(options);
										/*this.exportChart({
											width: 250
										});*/
									}
								},{
									text: 'Mostrar etiquetas',
									onclick: function () {
										//console.log(chart);
										
										labels = !labels;
										for (i = 0; i < chart.series.length; i++) {
											
											chart.series[i].update({
												dataLabels: {
													enabled: labels,
													style: {fontSize: '.8em'}
												}
											});
										}
										
										
										
										//chart = new Highcharts.Chart(options);
										/*this.exportChart({
											width: 250
										});*/
									}
								},{
									text: 'Exportar como PDF',
									
									onclick: function () {
										for (i = 0; i < chart.series.length; i++) {
											
											chart.series[i].update({
												dataLabels: {
													enabled: true,
													style: {fontSize: '.5em'} ,
												}
											});
										}
										chart.exportChart();
										for (i = 0; i < chart.series.length; i++) {
											chart.series[i].update({
													dataLabels: {
														enabled: labels,
														style: {fontSize: '.8em'} ,
													}
												});
											}
									},
									separator: false
								}]
							}
						}
					}
					
				};
				
				Highcharts.setOptions({
					colors: ['#66cc99', '#2d8659', '#ff6666', '#e60000']
				});
				
				var chart = new Highcharts.Chart(options);
				
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
</html>


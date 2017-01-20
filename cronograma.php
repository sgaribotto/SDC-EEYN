<!DOCTYPE html>
<html>
	<head>
		
		<title>Plan de clases</title>
		<?php 
			require_once('./fuentes/meta.html');
			require 'programas.autoloader.php';
			
			include './fuentes/constantes.php';
			require('./conexion.php');
			$materia = new clases\Materia($_SESSION['materia']);
			$cronograma = $materia->mostrarCronograma($ANIO, $CUATRIMESTRE);
			//print_r($cronograma);
		?>
		<style>
			table, tr, td {
				text-align:center;
			}
		</style>
	</head>
	
	<body>
		
		
		<?php
			require_once('./fuentes/botonera.php');
			require("./fuentes/panelNav.php");
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Cronograma</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario" style="<?php if(!isset($_GET['clase'])) { echo "display:none;"; } else { echo "display:block;"; } ?>">
				<fieldset class="formularioLateral">
					
					
					<form method="post" class="formularioLateral" action="./fuentes/AJAX.php?act=agregarPlanDeClase">
						
						<!--DESCRIPCIÓN - UNIDAD TEMÁTICA -->
						<fieldset class="subFormulario formularioLateral agregadosCronograma">
							<legend class="subFormulario formularioLateral agregadosCronograma">Descripción</legend>
							<label class="formularioLateral" for="clase">Clase:</label>
							<input type="number" class="formularioLateral iconUnidad" name="clase" required="required" id="clase" min="1" value="<?php if(isset($_GET['clase'])) { echo $_GET['clase']; } else { echo "1"; } ?>" />
							<img src="./images/icons/info.png" alt="Info" title="Puede modificar una clase cargada seleccionando su número" height="20px" style="cursor:help;margin-left:10px;">
							<br />
							<label class="formularioLateral" for="fecha">Fecha: </label>
							<input name="fecha" class="formularioLateral iconFecha datepicker"  required="required" id="fecha" type="date">
							<!--<a href="http://guarani.unsam.edu.ar/eyn_alumnos/acceso/descargar_archivo?archivo=calacadeeyn15.pdf">
							Calendario Académico</a>--> <br />
							
							<label class="formularioLateral" for="unidadtematica">Unidad Temática: </label>
							<input name="unidadtematica" class="formularioLateral iconUnidad"  required="required" id="unidadtematica" type="number" min="1">
							<br />
							<p class="formularioLaterial descripcionAjax" id="descripcionUnidadTematica"></p>
							<label class="formularioLateral" for="descripcion">Descripción de la clase: </label><br />
							<textarea name="descripcion" class="formularioLateral"  required="required" id="descripcion" style="height:100px;"></textarea>
							<br />
							<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar clase</button><span class="ayuda">(Debe guardar la clase para continuar con la carga)</span>
						</fieldset>
					</form>	
						<!--EQUIPO DOCENTE-->
						
						<fieldset class="subFormulario formularioLateral agregadosCronograma">
							<legend class="subFormulario formularioLateral agregadosCronograma">Equipo docente</legend>
						
							
							
							
							<br />
							<label class="formularioLateral" for="docente">Docentes asignados:</label>
							<select class="formularioLateral iconDocente" name="docente[]" required="required" id="docente" />
								<!--<option value="Todos" selected="selected">Todos</option>-->
								<?php 
									
									
									$equipoDocente = $materia->mostrarEquipoDocente('*', $ANIO, $CUATRIMESTRE);
									
									foreach ($equipoDocente as $value) {
										echo "<option value='$value[id]'>$value[docente]</option>";
									}
								?>
							</select>
							<br />
							<label for="horasClase" class="formularioLateral">Horas de clase: </label>
							<input type="number" class="formularioLateral iconDocente" id="horasClase" name="horasClase" min="0" />
							
							
							<button type="button" class="formularioLateral iconAgregar" id="agregarDocente">Agregar Docente</button>
							<br />
							<div id="agregadosCronograma">
								<table class="agregadosCronograma formularioLateral">
									<thead>
									<tr class="agregadosCronograma formularioLateral">
										<th class="agregadosCronograma formularioLateral" style="width:80%;">Docente</th>
										<th class="agregadosCronograma formularioLateral" style="width:20%;">Horas Clase</th>
										<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
									</tr>
									</thead>
									<tbody class="docente formularioLateral"></tbody>
									
								</table>
							</div>
							
						</fieldset>
						
						
						<!--MÉTODOS UTILIZADOS-->
						<fieldset class="subFormulario formularioLateral agregadosCronograma">
							<legend class="subFormulario formularioLateral agregadosCronograma">Método didáctico</legend>
							<label class="formularioLateral" for="metodo">Método:</label>
							
							<select class="formularioLateral iconDocente" name="metodo[]" required="required" id="metodo" />
								<option value="Expositivo y de preguntas" data-activo="20">Expositivo y de preguntas</option>
								<option value="Análisis de casos" data-activo="100">Análisis de casos</option>
								<option value="Resolución de problemas" data-activo="100">Resolución de problemas</option>
								<option value="Preguntas IGP" data-activo="80">Preguntas IGP</option>
								<option value="otro" data-activo="0">Otros</option>
							</select>
							<br />
							
							
							<label class="formularioLateral" for="activo">Clasificación: </label>
							<span class="sliderLabel formularioLateral labelPasivo">Pasivo</span>
								<input name="activo" class="formularioLateral"  id="activo" type="range" min="0" max="100" step="5" value="20">
							<span class="sliderLabel formularioLateral labelActivo">Activo</span>
							<br />
							<label for="porcentajeClase" class="formularioLateral">Porcentaje de la Clase: </label><input type="number" class="formularioLateral" name="porcentajeClase" id="porcentajeClase" min="0" max="100" value='100' />
							<!--<label class="formularioLateral" for="pasivo">Pasivo: </label>
							<input name="pasivo" class="formularioLateral iconPasivo"  required="required" id="pasivo" type="number" min="0" max="100">
							<br />-->
							<button type="button" class="formularioLateral iconAgregar" id="agregarMetodo">Agregar método</button>
							<br />
							<div id="agregadosCronograma">
								<table class="agregadosCronograma formularioLateral">
									<thead>
									<tr class="agregadosCronograma formularioLateral">
										<th class="agregadosCronograma formularioLateral" style="width:55%;">Método</th>
										<th class="agregadosCronograma formularioLateral" style="width:15%;">Activo</th>
										<th class="agregadosCronograma formularioLateral" style="width:15%;">Pasivo</th>
										<th class="agregadosCronograma formularioLateral" style="width:15%;">% Clase</th>
										<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
									</tr>
									</thead>
									<tbody class="metodo formularioLateral"></tbody>
									
								</table>
							</div>
						</fieldset>
						
						<!--<label class="formularioLateral" for="activo">Bibliografía: </label>
						<textarea name="bibliografia" class="formularioLateral"  required="required" id="bibliografia" style="height:100px;"></textarea>
						<br />-->
						
						<!--BIBLIOGRAFÍA-->
						<fieldset class="subFormulario formularioLateral agregadosCronograma">
							<legend class="subFormulario formularioLateral agregadosCronograma">Bibliografía</legend>
							<label class="formularioLateral" for="bibliografia">Bibliografía:</label>
							<select class="formularioLateral iconLibro" name="bibliografia[]" required="required" id="bibliografia" />
								<!--<option value="Todos" selected="selected">Todos</option>-->
								<?php 
									
									$bibliografia = $materia->mostrarBibliografia($ANIO, $CUATRIMESTRE);
									
									foreach ($bibliografia as $value) {
										echo "<option value='$value[id]'>$value[autor] - $value[titulo]</option>";
									}
								?>
							</select>
							<br />
							<label for="paginasIndividual" class="formularioLateral">Páginas: </label>
							<input type="number" class="formularioLateral iconPaginas" required="required" id="paginasIndividual" name="paginasIndividual" min="0" />
							
							<button type="button" class="formularioLateral iconAgregar" id="agregarBibliografia">Agregar bibliografía</button>
							<br />
							<div id="agregadosCronograma">
								<table class="agregadosCronograma formularioLateral">
									<thead>
									<tr class="agregadosCronograma formularioLateral">
										<th class="agregadosCronograma formularioLateral" style="width:80%;">Título</th>
										<th class="agregadosCronograma formularioLateral" style="width:20%;">Páginas</th>
										<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
									</tr>
									</thead>
									<tbody class="bibliografia formularioLateral"></tbody>
									
								</table>
							</div>
						</fieldset>
						
						
						
						
						<br />
						
						<!--<button type="submit" class="formularioLateral iconContinuar" id="botonContinuar">Continuar</button>-->
					
				</fieldset>
			</div>
			
		
		<hr>
		
			<div id="plantelActual">
				
					<?php
						
						require "./conexion.php";
						
						
						
						if (empty($cronograma)) {
							echo "<tr><td colspan='5'>No hay clases cargadas</td></tr>";
						} else {
						
							foreach ($cronograma as $key => $value ) {
								
								$query = "SELECT id, valor, tipo 
											FROM agregados_cronograma
											WHERE materia = $_SESSION[materia] AND clase = $value[clase] AND anio = $ANIO AND cuatrimestre = $CUATRIMESTRE AND activo = 1";
								$result = $mysqli->query($query);
								
								$agregadosClase = array();
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
									$agregadosClase[$row['tipo']][] = json_decode($row['valor']);
								}
							
								$fecha = new DateTime($value['fecha']);
								
								$pasivo = 100 - $value['activo'];
								echo "<h3 class='formularioLateral'>Clase $value[clase] - Fecha: {$fecha->format('d-m')} <button type='button' class='botonEliminar' style='position:absolute;right:10px;font-size:.8em;padding:2px;margin:0px;' data-id='$value[id]' >X</button></h3>";
								echo "<div class='intraTab'>
											<ul>
											<li><a href='#tabs-1'>Unidad temática</a></li>
											<li><a href='#tabs-2'>Descripción</a></li>
											<li><a href='#tabs-3'>Método</a></li>
											<li><a href='#tabs-4'>Bibliografía</a></li>
											<li><a href='#tabs-5'>Docentes</a></li>
										  </ul>";
											
								echo "		<div class='' id='tabs-1'>
												<p class='contenido'>Unidad $value[unidadtematica]</p>
												<p class='contenido'>$value[detalleunidadtematica]</p>
											</div>";
											
								echo "	<div class='' id='tabs-2'>
												<p class='contenido'>$value[descripcion]</p>
											</div>";
								
								//MÉTODOS DE DICTADO			
								echo "	<div class=''id='tabs-3'>";
								if (isset($agregadosClase['metodo'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:55%;'>Método</th>
													<th class='agregadosCronograma' style='width:15%;'>Activo</th>
													<th class='agregadosCronograma' style='width:15%;'>Pasivo</th>
													<th class='agregadosCronograma' style='width:15%;'>% Clase</th>
												</tr>
											</thead>
											<tbody>";
										$totalClase = 0;
										$totalActivo = 0;
										foreach ($agregadosClase['metodo'] as $object) {
											echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'>$object->metodo</td>
													<td class='agregadosCronograma'>$object->activo %</td>
													<td class='agregadosCronograma'>" . (100 - $object->activo) . " %</td>
													<td class='agregadosCronograma'>$object->porcentajeClase %</td>
												</tr>";
											$totalClase += $object->porcentajeClase;
											$totalActivo += ($object->activo * $object->porcentajeClase * .01);
										}										
									echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'><b>TOTALES</b></td>
													<td class='agregadosCronograma'><b>$totalActivo %</b></td>
													<td class='agregadosCronograma'><b>" . (100 - $totalActivo) . " %</b></td>
													<td class='agregadosCronograma'><b>$totalClase %</b></td>
												</tr>";
									echo "</tbody></table>";
								}
								echo "</div>";
								
								//BIBLIOGRAFÍA
								echo "<div class='' data-clase='$value[clase]' id='tabs-4'>";
								if (isset($agregadosClase['bibliografia'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:80%;'>Título</th>
													<th class='agregadosCronograma' style='width:20%;'>Páginas</th>
												</tr>
											</thead>
											<tbody>";
										$totalPaginas = 0;	
										
										foreach ($agregadosClase['bibliografia'] as $object) {
											echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'>" . str_replace("/ ", "<br />", $object->titulo) . " </td> 
													<td class='agregadosCronograma'>" . $object->paginas . "</td>
												</tr>";
											$totalPaginas += $object->paginas;
										}
										
										echo "<tr class='agregadosCronograma'>
													<td class='agregadosCronograma'><b>TOTAL PÁGINAS</b></td> 
													<td class='agregadosCronograma'><b>" . $totalPaginas . "</b></td>
												</tr>";
										
									echo "</tbody></table>";
								}
								echo"</div>";
								
								//EQUIPO DOCENTE
								echo "<div class='' data-clase='$value[clase]' id='tabs-5'>";
								if (isset($agregadosClase['docente'])) {
									echo "<table class='agregadosCronograma'>
											<thead class='agregadosCronograma'>
												<tr class='agregadosCronograma'>
													<th class='agregadosCronograma' style='width:80%;'>Docente</th>
													<th class='agregadosCronograma' style='width:20%;'>Horas de clase</th>
												</tr>
											</thead>
											<tbody>";
									foreach ($agregadosClase['docente'] as $object) {
										echo "<tr class='agregadosCronograma'>
												<td class='agregadosCronograma'>" . str_replace("/ ", "<br />", $object->docente) . "</td>
												<td class='agregadosCronograma'>" . $object->horasClase . "</td>
											</tr>";
									}
									echo "</tbody></table>";
								}
								echo "</div>";
									
								echo	"</div>";
							}
						}
							

					?>
				
			</div>
			
		</div>
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			
			var actualizarTabla = function (tabla) {
				cuerpo = $('#' + tabla);
				clase = $('#clase').val();
				$.post("./fuentes/AJAX.php?act=actualizarTabla&tabla=" + tabla, {"clase":clase, }, function(data) {
					//console.log(data);
					$('tbody.' + tabla).html(data);
					if ( $('.alerta100').text() != 100) {
						$('.alerta100').css('color', 'red');
					}
					
					if ( tabla = 'metodo' ) {
						$('input#porcentajeClase').attr('max', 100 - $('td.porcentajeCubierto').text());
						$('input#porcentajeClase').val( 100 - $('td.porcentajeCubierto').text());
					}
					
					$('.botonEliminarAgregadoCronograma').click(function() {
						id = $(this).data('id');
						$.post("./fuentes/AJAX.php?act=eliminarAgregadoCronograma", {"id":id, }, function(data) {
							actualizarTabla('bibliografia');
							actualizarTabla('metodo');
							actualizarTabla('docente');
							console.log(data);
						});
					});	
				});
			};
			
			$('#clase').change(function() {
				clase = $('#clase').val();
				if (clase != "" ) {
					$.post("./fuentes/AJAX.php?act=mostrarPlanDeClase", {"clase":clase}, function(data) {
						datos = data.split('|');
						
						if (datos.length == 11) {
							$('#fecha').val(datos[1]);
							$('#docente').val(datos[2].split('/ '));
							$('#unidadtematica').val(datos[3]);
							$('#descripcion').val(datos[4]);
							/*$('#metodo').val(datos[5].split('/ '));
							$('#activo').val(datos[6]);
							$('#pasivo').val(datos[7]);
							$('#bibliografia').val(datos[8].split('/ '));
							$('#paginas').val(datos[9]);
							//$('#descripcion').val(data);*/
						} else {
							$('#fecha').val("");
							$('#docente').val("");
							$('#unidadtematica').val("");
							$('#descripcion').val("");
							/*$('#metodo').val("");
							$('#activo').val("");
							$('#pasivo').val("");
							$('#bibliografia').val("");
							$('#paginas').val("");*/
						}
					});
					
					actualizarTabla('bibliografia');
					actualizarTabla('metodo');
					actualizarTabla('docente');
				}
			});
			
			$('#clase').trigger('change');
			
			$('#unidadtematica').change(function() {
				unidadtematica = $('#unidadtematica').val();
				if (unidadtematica != "" ) {
					$.post("./fuentes/AJAX.php?act=mostrarDescripcionUnidadTematica2", {"unidadtematica":unidadtematica}, function(data) {
						//console.log(data);
						$("#descripcionUnidadTematica").text(data);
						
					});
				}
			});
				
			
			/*$("#guardarCargarOtro").click(function() {
				clase = $('#clase').val();
				unidadtematica = $('#unidadtematica').val();
				fecha = $('#fecha').val();
				docente = $('#docente').val();
				descripcion = $('#descripcion').val();
				metodo = $('#metodo').val();
				activo = $('#activo').val();
				pasivo = $('#pasivo').val();
				bibliografia = $('#bibliografia').val();
				paginas = $('#paginas').val();
				
				
				if (clase != "" && unidadtematica != "" && fecha != "" && paginas != "" && docente != "" && descripcion != "" && metodo != "" && activo != "" && bibliografia != "") {
					$.post("./fuentes/AJAX.php?act=agregarPlanDeClase", {"clase":clase, "unidadtematica":unidadtematica, "fecha":fecha, "paginas":paginas, "docente":docente, "descripcion":descripcion, "metodo":metodo, "activo":activo, "bibliografia":bibliografia}, function(data) {
						//console.log(data);
						location.reload();
					});
				}
			});*/
			
			/*$("#botonContinuar").click(function() {
				location.assign("./datosgenerales.php");
			});*/
			
			$('#activo').change(function() {
				$('#pasivo').val(100 - $('#activo').val());
			});
			
			$('#pasivo').change(function() {
				$('#activo').val(100 - $('#pasivo').val());
			});
			
			$(".datepicker").datepicker(
				{
					dateFormat:"yy-mm-dd",
				}
			);
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
			/*$('div.dialogClase').dialog({
				autoOpen: false,
				  
			});
			
			$( ".tdClase" ).click(function() {
				clase = $(this).text();
				$( "#detalleClase" + clase ).dialog({
					title:"Detalle Clase " + clase,
					
				});
				$( "#detalleClase" + clase ).dialog( "open" );
				
			});*/
			
			$('.botonEliminar').click(function() {
				id = $(this).data('id');
				$.post("./fuentes/AJAX.php?act=eliminarPlanDeClase", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$('.botonEliminarAgregadoCronograma').click(function() {
				id = $(this).data('id');
				alert(id);
				$.post("./fuentes/AJAX.php?act=eliminarAgregadoCronograma", {"id":id, }, function(data) {
					actualizarTabla('bibliografia');
					actualizarTabla('metodo');
					actualizarTabla('docente');
				});
			});
			
			
			
			$('#clase').change(function() {
				
			});
			
			$('#plantelActual').accordion({
				collapsible:true,
				heightStyle:'content',
			});
			
			$('.intraTab').tabs();
			
			$('button#agregarBibliografia').click(function() {
				clase = $('#clase').val();
				idLibro = $('#bibliografia').val();
				titulo = $('#bibliografia option:selected').text();
				paginas = $('#paginasIndividual').val();
				
				if (clase != "" && idLibro != "" && paginas != "") {
					$.post("./fuentes/AJAX.php?act=agregarAgregadosCronograma", {"clase":clase, "idLibro":idLibro, "paginas":paginas, "tipo":"bibliografia", 'titulo':titulo }, function(data) {
						actualizarTabla('bibliografia');
					});
				} else {
					alert("Debe seleccionar el número de clase, el título de la bibliografía y la cantidad de páginas");
				}
				
			});
			
			$('button#agregarMetodo').click(function() {
				clase = $('#clase').val();
				activo = $('#activo').val();
				metodo = $('#metodo option:selected').text();
				porcentajeClase = $('#porcentajeClase').val();
					
				if (clase != "" && activo != "" && metodo != "" && porcentajeClase > 0) {
					$.post("./fuentes/AJAX.php?act=agregarAgregadosCronograma", {"clase":clase, "activo":activo, "tipo":"metodo", 'metodo':metodo, 'porcentajeClase': porcentajeClase }, function(data) {
					actualizarTabla('metodo');
					});
				} else {
					alert("Debe seleccionar el número de clase, el método, su composición en activo y pasivo y el porcentaje ocupado de la clase");
				}
				
			});
			
			$('button#agregarDocente').click(function() {
				clase = $('#clase').val();
				horasClase = $('#horasClase').val();
				docente = $('#docente option:selected').text();
				idDocente = $('#docente').val();
					
				if (clase != "" && horasClase != "" && docente != "" && idDocente != "") {
					$.post("./fuentes/AJAX.php?act=agregarAgregadosCronograma", {"clase":clase, "horasClase":horasClase, "tipo":"docente", 'docente':docente, 'idDocente': idDocente }, function(data) {
						actualizarTabla('docente');
					});
				} else {
					alert("Debe seleccionar una clase, un docente y completar la cantidad de horas");
				}
				
			});
			
			
			$('input#activo').change(function(){
				$('.labelActivo').text('Activo: ' + $('input#activo').val() + '%');
				$('.labelPasivo').text('Pasivo: ' + (100 - $('input#activo').val()) + '%');
			});
			
			//Valore siniciales de activo
			$('.labelActivo').text('Activo: ' + $('input#activo').val() + '%');
			$('.labelPasivo').text('Pasivo: ' + (100 - $('input#activo').val()) + '%');
			$('input#porcentajeClase').attr('max', 100 - $('td.porcentajeCubierto').text());
			$('input#porcentajeClase').val( 100 - $('td.porcentajeCubierto').text());
			console.log($('td.porcentajeCubierto').text());
			
			$('#metodo').change(function() {
				$('input#activo').val( $('#metodo option:selected').data('activo') );
				$('.labelActivo').text('Activo: ' + $('input#activo').val() + '%');
				$('.labelPasivo').text('Pasivo: ' + (100 - $('input#activo').val()) + '%');
				
			});
		});
	</script>
</html>

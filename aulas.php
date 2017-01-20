<!DOCTYPE html>
<html>
	<head>
		
		<title>Aulas</title>
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
			require "./fuentes/constantes.php";
		?>
		<div class="formularioLateral">
			<h2 class="formularioLateral">Aulas</h2>
			<div id="mostrarFormulario">Mostrar Formulario</div>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="#" id="formularioCarga">
						
						<label class="formularioLateral" for="materia">Aula: </label>
						<input type="text" maxlength="5" class="formularioLateral" name="aula" id="aula" />
						<br />
						<label class="formularioLateral" for="capacidad">Capacidad:</label>
						<input type="number" min="0" max="1000" class="formularioLateral" id="capacidad" name="capacidad" value="0" />
						<br />
						
						<label class="formularioLateral" for="mas_info">Más información: </label>
						<textarea name="mas_info" class="formularioLateral"   id="mas_info" style="height:40px;"></textarea>
						
						
						<button type="submit" class="formularioLateral iconAgregar" id="guardarCargarOtro">Guardar y cargar otra</button>
					</form>
				</fieldset>
			</div>
			
		
			<hr>
		
		
			<div id="plantelActual">
				<table class="plantelActual">
					<thead class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual sort-numeric" style="width:15%;" data-key="cod">Aula</th>
						<th class="plantelActual sort-numeric" style="width:15%;"data-key="capacidad">Capacidad</th>
						<th class="plantelActual sort-alpha" style="width:45%;"data-key="mas_info">Más Información</th>
						<th class="plantelActual sort-alpha" style="width:5%;"data-key="abierta">Abierta</th>
						<th class="plantelActual" style="width:5%;">Eliminar</th>
					</tr>
					</thead>
					<tbody class="plantelActual"></tbody>
					<?php
						/*require('./conexion.php');
						
						$cantidadResultados = (isset($_GET['cantidadResultados'])) ? $_GET['cantidadResultados'] : 10;
						$pagina = (isset($_GET['pagina'])) ? (($_GET['pagina'] - 1) * $cantidadResultados) : 0;
						$hasta = $pagina + $cantidadResultados;
						
						$result = $mysqli->query("SELECT COUNT(id) FROM aulas WHERE activo = 1");
													
						$totalPaginas = $result->fetch_row();
						$result->free();
						
						$totalPaginas = $totalPaginas[0] / $cantidadResultados;
						
						$query = "SELECT cod, capacidad, mas_info, id
									FROM aulas
									WHERE activo = 1
									LIMIT $pagina, $cantidadResultados";
						//echo $query;
						$result = $mysqli->query($query);
						echo $mysqli->error;
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							
							echo "<tr class='formularioLateral plantelActual'>";
							
							foreach ($row as $key => $value) {
								if ($key != 'id') {
									echo "<td class='formularioLateral plantelActual'>$value</td>";
								}
							}
							
							echo "<td class='formularioLaterial eliminarEnTabla'><button type='button' class='formularioLateral botonEliminar' id='eliminarDocente' data-id='$row[id]'>X</button>";
							echo "</tr>";
						}
						
						$result->free();
						$mysqli->close();*/

					?>
				</table>
				<ul class="linkPagina">
				<?php
					/*if ($totalPaginas > 1) {
						for ($i = 0; $i < $totalPaginas; $i++) {
							$url = $_SERVER['PHP_SELF'] . "?pagina=" . ($i + 1);
							echo "<li class='linkPagina'>
										<a href = $url>" . ($i + 1) . "</a>
									</li>";
							
						}
					}*/
				?>
				</ul>
			</div>
			
		</div>
		
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			values = {};
			values.act = "mostrarAulas";
			function actualizarTabla() {
				
				$.get('./fuentes/AJAXJSON.php', values, function(data) {
					//alert('2');
					data = eval(data);
					if (data.error) {
						alert(data.error);
					} else {
						$.each(data, function(key, val) {
							html = "<tr class='informacion'>";
							$.each(val, function(key2, val2) {
								if (key2 != 'id') {
									html += "<td data-val:" + val + ">" + val2 + "</td>";
								}
							});
							html += "</tr>";
							$('tbody.plantelActual').append(html);
						});
					}
				}, "html");
				
					
			}
			
			function ordenarTabla() {
				var $table = $('table.plantelActual');
				var $headers = $('thead.plantelActual th').slice(0, -1);
				$headers
					.each(function() {
						var keyType = this.className.replace(/^sort-/, '');
						$(this).data('keyType', keyType);
					})
					.wrapInner('<a href="#"></a>')
					.addClass('sort');
				
				var sortKeys = {
					alpha: function($cell) {
						var key = $cell.text();
						key += $.trim($cell.text()).toUpperCase();
						return key;
					},
					numeric: function($cell) {
						var num = $cell.text().replace(/^[^\d.]*/, '');
						var key = parseFloat(num);
						if (isNaN(key)) {
							key = 0;
						}
						return key;
					},
					date: function($cell) {
						var key = Date.parse('1 ' + $cell.text());
						return key;
					}
				};
				
				$headers.click(function(event) {
					event.preventDefault();
					var $header = $(this);
					var column = $header.index();
					var keyType = $header.data('keyType');
					if (!$.isFunction(sortKeys[keyType])) {
						return;
					}
					var rows = $table.find('tbody > tr').each(function() {
						var $cell = $(this).children('td').eq(column);
						$(this).data('sortKey', sortKeys[keyType]($cell));
					}).get();
					rows.sort(function(a, b) {
						var keyA = $(a).data('sortKey');
						var keyB = $(b).data('sortKey');
						if (keyA < keyB) return -1;
						if (keyA > keyB) return 1;
						return 0;
					});
					
					$.each(rows, function(index, row) {
						$table.children('tbody').append(row);
					});
				});
			}
			
			actualizarTabla();
			ordenarTabla();
			//Buscar si se carga algo para que traiga toda la info del aula
			$('#aula').change(function(event) {
				aula = $(this).val();
				$.get('./fuentes/AJAX.php', {'aula': aula, 'act': 'mostrarAula'}, function(data) {
					data = data.split(' | ');
					$('#capacidad').val(parseInt(data[0]));
					
					$('#mas_info').text(data[1]);
				}, 'html');
			});
			
			$('#formularioCarga').submit( function(event) {
				event.preventDefault();
				values = $(this).serialize();
				values += "&act=agregarAula";
				
				$.get("./fuentes/AJAX.php", values, function(data) {
					location.reload();
				});
			});
			
			
			$('.botonEliminar').click(function() {
				id = $(this).data('id');
				$.post("./fuentes/AJAX.php?act=eliminarAula", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
		});
	</script>
</html>

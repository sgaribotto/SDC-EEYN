<!DOCTYPE html>
<html>
	<head>
		
		<title>Bibliografía</title>
		<?php 
			require_once('./fuentes/meta.html');
			
			require 'programas.autoloader.php';
			include './fuentes/constantes.php';
		?>
		
	</head>
	
	<body>
		
		<?php
			require("./fuentes/panelNav.php");
			require_once('./fuentes/botonera.php');
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Bibliografía Obligatoria</h2>
			<div id="plantelActual">
				<table class="plantelActual">
					<tr class="plantelActual">
						<th class="plantelActual" style="width:40%;">Título</th>
						<th class="plantelActual" style="width:30%;">Autor</th>
						<th class="plantelActual" style="width:20%;">Editorial</th>
						<th class="plantelActual" style="width:10%;">Páginas</th>
						<!--<th class="plantelActual" style="width:20%;">Estado</th>-->
					</tr>
					<?php
						
						
						
						$materia = new clases\Materia($_SESSION['materia']);
						$bibliografia = $materia->mostrarBibliografia($ANIO, $CUATRIMESTRE);
						
						if (empty($bibliografia)) {
							echo "<tr><td colspan='2'>No hay bibliografía cargadas</td></tr>";
						} else {
						
							foreach ($bibliografia as $key => $value ) {
								echo "<tr class='formularioLateral plantelActual'>
											<td class='formularioLateral plantelActual'>$value[titulo]</td>
											<td class='formularioLateral plantelActual'>$value[autor]</td>
											<td class='formularioLateral plantelActual'>$value[editorial]</td>
											<td class='formularioLateral plantelActual'>$value[paginas]</td>
											<td class='formularioLateral correlatividadesTable'><button type='button' class='botonEliminar' data-id='$value[id]' >X</button></td>
											</tr>";
							}
						}
							

					?>
				</table>
			</div>
			<hr>
			<div id="formulario">
				<fieldset class="formularioLateral">
					<form method="post" class="formularioLateral" action="./fuentes/AJAX.php?act=agregarBibliografia" id="agregarBibliografia">
						<label class="formularioLateral" for="titulo">Título:</label>
						<input type="text" class="formularioLateral iconLibro" name="titulo" required="required" id="titulo" />
						<br />
						<label class="formularioLateral" for="autor">Autor: </label>
						<input name="autor" class="formularioLateral iconAutor"  required="required" id="autor" type="text">
						<br />
						<label class="formularioLateral" for="editorial">Editorial: </label>
						<input name="editorial" class="formularioLateral iconEditorial"  required="required" id="editorial" type="text">
						<br />
						<label class="formularioLateral" for="paginas">Cantidad de páginas: </label>
						<input name="paginas" class="formularioLateral iconPaginas"  required="required" id="paginas" type="number" min="1">
						<br />
						
						<button type="submit" class="formularioLateral iconAgregar" id="1guardarCargarOtro">Guardar y cargar otra</button>
						
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
			
			$("form#agregarBibliografia").submit(function() {
				titulo = $('#titulo').val();
				autor = $('#autor').val();
				editorial = $('#editorial').val();
				paginas = $('#paginas').val();
				
				if (titulo != "" && autor != "" && editorial != "" && paginas != "") {
					$.post("./fuentes/AJAX.php?act=agregarBibliografia", {"titulo":titulo, "autor":autor, "editorial":editorial, "paginas":paginas}, function(data) {
						location.reload();
					});
				}
			});
			
			$('.botonEliminar').click(function() {
				id = $(this).data('id');
				$.post("./fuentes/AJAX.php?act=eliminarBibliografia", {"id":id, }, function(data) {
					location.reload();
				});
			});
			
			$("#botonContinuar").click(function() {
				location.assign("./cronograma.php");
			});
		});
	</script>
</html>

<!DOCTYPE html>
<html>
	<head>
		
		<title>Solicitar Designaciones</title>
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
			<h2 class="formularioLateral">Pedido de designación</h2>
			
			<div class="dialog resumenMateria" id="dialogResumenMateria"></div>

			<div id="designaciones">
				<table class='pedidoDesignacion'>
					<tr class='pedidoDesignacion'>
						<th class='pedidoDesignacion'>Modalidad</th>
						<th class='pedidoDesignacion'>Apellido</th>
						<th class='pedidoDesignacion'>Nombre</th>
						<th class='pedidoDesignacion'>Doc</th>
						<th class='pedidoDesignacion'>Nro. Doc</th>
						<th class='pedidoDesignacion'>Categoría</th>
						<th class='pedidoDesignacion'>Carácter</th>
						<th class='pedidoDesignacion'>Dedicación</th>
						<th class='pedidoDesignacion'>Bruto Mensual</th>
						<th class='pedidoDesignacion'>Alta</th>
						<th class='pedidoDesignacion'>Baja</th>
						<th class='pedidoDesignacion'>FF</th>
						<th class='pedidoDesignacion'>Sub-Dep.</th>
						<th class='pedidoDesignacion'>Sede</th>
						<th class='pedidoDesignacion'>Materia / Función</th>
						<th class='pedidoDesignacion'>Comisión</th>
					</tr>
					
					<?php
						require 'fuentes/conexion.php';
						
						$query = "SELECT 'ND' AS modalidad,
									d.apellido,
									d.nombres,
									'DNI' AS documento,
									d.dni,
									a.tipoafectacion as categoria,
									'ND' AS caracter,
									'ND' AS dedicacion,
									'ND' AS bruto_mensual,
									'ND' AS alta,
									'ND' AS baja,
									'ND' AS ff,
									c.cod as sub_dep,
									'EEYN' AS sede,
									m.nombre as nombre_materia,
									'ND' as comision
								FROM afectacion AS a
								LEFT JOIN docente AS d ON a.docente = d.id
								LEFT JOIN materia AS m ON a.materia = m.cod
								LEFT JOIN carrera AS c ON m.carrera = c.id
								WHERE a.activo = 1
									AND a.anio = 2015
									AND a.cuatrimestre = 2
									AND a.estado LIKE '%aprobado%'
									AND c.id =  1";
						$result = $mysqli->query($query);
						echo $mysqli->error;
						
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							echo "<tr class='pedidoDesignacion contenido'>";
							
							foreach ($row as $value) {
								echo "<td class='pedidoDesignacion contenido'>$value</td>";
							}
							
							echo "</tr>";
						}
								
								
									
					?>
				</table>
			</div>
			
		</div>
		
	</body>
	<?php require "./fuentes/jqueryScripts.html"; ?>
	<script src="./fuentes/funciones.js"></script>
	
	<script>
		$(document).ready(function() {
			var dialogOptions = {
				autoOpen: false,
				width:1000,
				height: 600,
				modal: true,
				appendTo: "#Botonera"
				
			};
			$('div.dialog').dialog(dialogOptions);
			
			$('#dialogResumenMateria').dialog('option', 'title', 'Resumen de la materia');
			/*$('#unidad').change(function() {
				unidad = $('#unidad').val();
				if (unidad != "" ) {
					$.get("./fuentes/AJAX.php?act=mostrarDescripcionUnidadTematica", {"unidad":unidad}, function(data) {
						$('#descripcion').val(data);
					});
				}
			});*/
			
			
			
			$('#tablaAceptarDesignacion').on('click', 'td.linkResumenMateria', function(event) {
				
				var cod = $(event.target).data('cod');
				$('#dialogResumenMateria').empty();
				
				$('#dialogResumenMateria').load('resumenmateria.php?materia=' + cod);
				
				$('#dialogResumenMateria').dialog(dialogOptions).dialog('open');
			});
			
			actualizarTabla();
			
			$('#mostrarFormulario').click(function() {
				$('div #formulario').toggle();
			});
			
			$('.filterTrigger').on('keyup keypress blur change', function() {
				actualizarTabla(); 
			});
			
			
			
		});
	</script>
</html>

<!DOCTYPE html>
<html>
	<head>
		
		<title>Evaluación y criterios de aprobación</title>
		<?php 
			require_once('./fuentes/meta.html');
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
			include './fuentes/constantes.php';
		?>
	</head>
	
	<body>
		
		<?php
			require("./fuentes/panelNav.php");
			require_once('./fuentes/botonera.php');
		?>
		
		
		
			
		<div class="formularioLateral">
			<h2 class="formularioLateral">Información de afectaciones</h2>
			<table class="formularioLateral tablaInfo">
				<thead class="formularioLateral tablaInfo">
					<tr class="formularioLateral tablaInfo">
						<th class="formularioLateral tablaInfo">Periodo</th>
						<th class="formularioLateral tablaInfo">Cantidad</th>
						<th class="formularioLateral tablaInfo">Cargo</th>
						<th class="formularioLateral tablaInfo">Estado</th>
					</tr>
				</thead>
				<tbody class="formularioLateral tablaInfo">
			<?php
				require "fuentes/conexion.php";
				
				$query = "SELECT CONCAT(a.anio, '-', a.cuatrimestre) AS periodo, COUNT(a.materia) as cantidad, a.tipoafectacion, a.estado
							FROM afectacion as a
							WHERE activo = 1
							GROUP BY periodo, a.tipoafectacion, a.estado
							ORDER BY estado DESC";
				$result = $mysqli->query($query);
				
				while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
					echo "<tr class='formularioLateral tablaInfo'>
							<td class='formularioLateral tablaInfo'>$row[periodo]</td>
							<td class='formularioLateral tablaInfo'>$row[cantidad]</td>
							<td class='formularioLateral tablaInfo'>$row[tipoafectacion]</td>
							<td class='formularioLateral tablaInfo'>$row[estado]</td>
					
						</tr>";
					}
				$result->free();
				$mysqli->close();
				
			
			?>
				</tbody>
			</table>
		</div>
		
	</body>
</html>

<!DOCTYPE html>
<html>
	<head>
		
		<title></title>
		<?php 
			require_once('fuentes/meta.html');
			require_once('fuentes/botonera.php');
			require_once('fuentes/constantes.php');
			require_once 'programas.autoloader.php';
			
			$CUATRIMESTRE = 1;
		?>
		
	</head>
	
	<body>
		<h1>Segunda Estimación (actas cerradas)</h1>
		<table>
			<thead>
				<th>Cod</th>
				<th>Nombre</th>
				<th>Cuatrimestre</th>
				<th>Carrera</th>
				<th>Mañana</th>
				<th>Noche</th>
			</thead>
			<tbody>
<?php

	require 'conexion.php';
	
	$query = "SELECT MAX(m.cod) AS cod, m.conjunto, m.cuatrimestre, 
			GROUP_CONCAT(DISTINCT m.nombre SEPARATOR ' / ' ) AS materia,
			c.cod AS carrera
		FROM materia AS m
		LEFT JOIN carrera AS c ON c.id = m.carrera
		GROUP BY m.conjunto
		ORDER BY m.cuatrimestre";
	$result = $mysqli->query($query);
	$materias = array();
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$materias[$row['cod']] = $row;
	}
	
	foreach ($materias as $cod => $info) {
		$materia = new clases\Materia($cod);
		$anio = $ANIO;
		$cuatrimestre = $CUATRIMESTRE;
		if ($info['carrera'] == 'LITUR' ) {
			if ($cuatrimestre == 2) {
				$cuatrimestre = 1;
			} else {
				$cuatrimestre = 2;
				$anio = $anio - 1;
			}
		}
			
		$estimados = $materia->segundaEstimacion($anio, $cuatrimestre, $info['carrera']);
		if (!isset($estimados['M']['recursantes'])) {
			$estimados['M']['recursantes'] = 0;
		}
		if (!isset($estimados['M']['nuevos'])) {
			$estimados['M']['nuevos'] = 0;
		}
		if (!isset($estimados['N']['recursantes'])) {
			$estimados['N']['recursantes'] = 0;
		}
		if (!isset($estimados['N']['nuevos'])) {
			$estimados['N']['nuevos'] = 0;
		}
		
		echo "<tr>";
		echo "<td>{$info['conjunto']}</td>";
		echo "<td>{$info['materia']}</td>";
		echo "<td>{$info['cuatrimestre']}</td>";
		echo "<td>{$info['carrera']}</td>";
		
		echo "<td>". ($estimados['M']['recursantes'] + $estimados['M']['nuevos']) . "</td>";
		echo "<td>". ($estimados['N']['recursantes'] + $estimados['N']['nuevos']) . "</td>";
		echo "</tr>";
	}
?>
			</tbody>
		</table>
	</body>
</html>

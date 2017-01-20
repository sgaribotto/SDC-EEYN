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
				<th>Mañana <br />Segunda</th>
				<th>Mañana <br />Preliminar</th>
				<th>Mañana <br />Diferencia</th>
				<th>Noche <br />Segunda</th>
				<th>Noche <br />Preliminar</th>
				<th>Noche <br />Diferencia</th>
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
	
	$totales = array();
	$totales['preliminar']['M'] = 0;
	$totales['preliminar']['N'] = 0;
	$totales['segunda']['M'] = 0;
	$totales['segunda']['N'] = 0;
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
		$estimacionPreliminar = $materia->mostrarEstimacionPreliminar($anio, $cuatrimestre, $info['carrera']);
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
		if (!isset($estimacionPreliminar['M']['recursantes'])) {
			$estimacionPreliminar['M']['recursantes'] = 0;
		}
		if (!isset($estimacionPreliminar['M']['nuevos'])) {
			$estimacionPreliminar['M']['nuevos'] = 0;
		}
		if (!isset($estimacionPreliminar['N']['recursantes'])) {
			$estimacionPreliminar['N']['recursantes'] = 0;
		}
		if (!isset($estimacionPreliminar['N']['nuevos'])) {
			$estimacionPreliminar['N']['nuevos'] = 0;
		}
		
		
		$totales['preliminar']['M'] += ($estimacionPreliminar['M']['recursantes'] + $estimacionPreliminar['M']['nuevos']);
		$totales['preliminar']['N'] += ($estimacionPreliminar['N']['recursantes'] + $estimacionPreliminar['N']['nuevos']);
		$totales['segunda']['M'] += ($estimados['M']['recursantes'] + $estimados['M']['nuevos']);
		$totales['segunda']['N'] += ($estimados['N']['recursantes'] + $estimados['N']['nuevos']);
	
		echo "<tr>";
		echo "<td>{$info['conjunto']}</td>";
		echo "<td>{$info['materia']}</td>";
		echo "<td>{$info['cuatrimestre']}</td>";
		echo "<td>{$info['carrera']}</td>";
		
		echo "<td>". ($estimados['M']['recursantes'] + $estimados['M']['nuevos']) . "</td>";
		echo "<td>". ($estimacionPreliminar['M']['recursantes'] + $estimacionPreliminar['M']['nuevos']) . "</td>";
		$diferencia = 0;
		if (($estimacionPreliminar['M']['recursantes'] + $estimacionPreliminar['M']['nuevos']) != 0) {
			$diferencia = ( 1 - ($estimados['M']['recursantes'] + $estimados['M']['nuevos']) / ($estimacionPreliminar['M']['recursantes'] + $estimacionPreliminar['M']['nuevos'])); 
		}
		echo "<td> " . $diferencia  . " </td>";
		echo "<td>". ($estimados['N']['recursantes'] + $estimados['N']['nuevos']) . "</td>";
		echo "<td>". ($estimacionPreliminar['N']['recursantes'] + $estimacionPreliminar['N']['nuevos']) . "</td>";
		if (($estimacionPreliminar['N']['recursantes'] + $estimacionPreliminar['N']['nuevos']) != 0) {
			$diferencia = ( 1 - ($estimados['N']['recursantes'] + $estimados['N']['nuevos']) / ($estimacionPreliminar['N']['recursantes'] + $estimacionPreliminar['N']['nuevos'])); 
		}
		echo "<td> " . $diferencia  . " </td>";
		echo "</tr>";
		
	}
	
	echo "<tr>";
		echo "<td colspan='4'>TOTALES</td>";
		echo "<td>" . $totales['segunda']['M'] . "</td>";
		echo "<td>" . $totales['preliminar']['M'] . "</td>";
		echo "<td>" . $totales['segunda']['N'] . "</td>";
		echo "<td>" . $totales['preliminar']['N'] . "</td>";
		echo "</tr>";
?>
			</tbody>
		</table>
	</body>
</html>

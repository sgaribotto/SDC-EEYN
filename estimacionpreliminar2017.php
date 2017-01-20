<!DOCTYPE html>
<html>
	<head>
		
		<title></title>
		<?php 
			require_once('fuentes/meta.html');
			require_once('fuentes/botonera.php');
			require_once('fuentes/constantes.php');
			require_once 'programas.autoloader.php';
			
		?>
		
	</head>
	
	<body>
		
<?php
	require 'conexion.php';
	
	$query = "SELECT m.cod, m.conjunto
		FROM materia AS m
		ORDER BY m.cuatrimestre, conjunto";
	$result = $mysqli->query($query);
	$materias = array();
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$materias[$row['cod']] = $row;
	}
	
	$inscriptosEstimados = array();
	foreach ($materias as $cod => $info) {
		$materia = new clases\Materia($cod);
		
		$carrera = $materia->datosMateria['cod_carrera'];
		$conjunto = $materia->mostrarConjunto();
		$estimados = $materia->mostrarEstimacionPreliminarPorCod($ANIO, $CUATRIMESTRE, $carrera);
		
		foreach ($estimados as $turno => $datos) {
			if (isset($inscriptosEstimados[$turno][$conjunto])) {
				if (isset($datos['nuevos'])) {
					$inscriptosEstimados[$turno][$conjunto]['nuevos'] += $datos['nuevos'];
				} else {
					$inscriptosEstimados[$turno][$conjunto]['nuevos'] += 0;
				}
				
				if (isset($datos['recursantes'])) {
					$inscriptosEstimados[$turno][$conjunto]['recursantes'] += $datos['recursantes'];
				} else {
					$inscriptosEstimados[$turno][$conjunto]['recursantes'] += 0;
				}
			} else {
				if (isset($datos['nuevos'])) {
					$inscriptosEstimados[$turno][$conjunto]['nuevos'] = $datos['nuevos'];
				} else {
					$inscriptosEstimados[$turno][$conjunto]['nuevos'] = 0;
				}
				
				if (isset($datos['recursantes'])) {
					$inscriptosEstimados[$turno][$conjunto]['recursantes'] = $datos['recursantes'];
				} else {
					$inscriptosEstimados[$turno][$conjunto]['recursantes'] = 0;
				}
			}
		}
		
		
	}
	
	foreach ($inscriptosEstimados as $turno => $info) {	
		echo "<h1>Turno: {$turno}</h1>
		<table>
			<thead>
				<th>Cod</th>
				<th>turno</th>
				<th>Nuevos</th>
				<th>Recursantes</th>
			</thead>
			<tbody>";
		
		foreach ($info as $conjunto => $inscriptos) {
			echo "<tr>";
		
			echo "<td>'{$conjunto}</td>";
			echo "<td>{$turno}</td>";
			
			//echo "<td>{$info['cuatrimestre']}</td>";
			//echo "<td>{$info['carrera']}</td>";
			
			echo "<td>". $inscriptos['nuevos'] . "</td>";
			echo "<td>". $inscriptos['recursantes'] . "</td>";
			//echo "<td>". $inscriptos['total'] . "</td>";
			echo "</tr>";
		}
		
		echo "</tbody></table>";
		
	}
?>
			</tbody>
		</table>
	</body>
</html>

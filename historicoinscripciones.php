<!DOCTYPE html>
<html>
	<head>
		
		<title></title>
		<?php 
			require_once('fuentes/meta.html');
			require_once('fuentes/botonera.php');
			require_once('fuentes/constantes.php');
			require_once 'programas.autoloader.php';
			
			$ANIO = 2016;
			$CUATRIMESTRE = 2;
		?>
		
	</head>
	
	<body>
		
<?php
	require 'conexion.php';
	
	$query = "SELECT b.anio_academico, b. periodo_lectivo, m.conjunto, 
				b.materia,
				GROUP_CONCAT(DISTINCT b.nombre_materia ORDER BY cod + 0 SEPARATOR ' / ') AS nombre,
				GROUP_CONCAT(DISTINCT b.carreras) AS carreras,
				GROUP_CONCAT(DISTINCT m.cuatrimestre ORDER BY m.cuatrimestre) AS cuatrimestre,
				b.turno,
				MAX(b.cantidad_comisiones) AS cantidad_comisiones,
				SUM(b.cantidad_inscriptos) AS cantidad_inscriptos
			FROM (
			   SELECT anio_academico,
					periodo_lectivo,
					materia, 
					nombre_materia,
					
					GROUP_CONCAT(DISTINCT carrera ORDER BY carrera SEPARATOR ' Y ') AS carreras,
					IF(nombre_comision LIKE '%M%', 'M',
						If(nombre_comision LIKE '%T%', 'T', 'N')
					) AS turno,
					COUNT(DISTINCT RIGHT(nombre_comision, LENGTH(nombre_comision) - LENGTH(materia))) AS cantidad_comisiones, 
					COUNT(DISTINCT nro_documento) AS cantidad_inscriptos
					
				FROM inscriptos
				WHERE periodo_lectivo = 1
					AND anio_academico > 2014
					AND carrera IN ('EYN-1', 'EYN-3', 'EYN-4', 'CPUT', 'LITUR')
				GROUP BY materia, turno, anio_academico, periodo_lectivo
			) AS b

			LEFT JOIN programas.materia AS m ON m.cod = b.materia

			GROUP BY anio_academico, periodo_lectivo, turno, conjunto

			ORDER BY cuatrimestre, conjunto, turno, anio_academico, periodo_lectivo";
	echo "<pre>" . $query . "</pre>";
	$result = $mysqli->query($query);
	
	$turnos = ['M', 'N', 'T'];
	
	$inscriptos = array();
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$inscriptos[$row['turno']][$row['conjunto']][$row['anio_academico']]['comisiones'] = $row['cantidad_comisiones'];
		$inscriptos[$row['turno']][$row['conjunto']][$row['anio_academico']]['inscriptos'] = $row['cantidad_inscriptos'];
		$inscriptos[$row['turno']][$row['conjunto']]['nombre'] = $row['nombre'];
		$inscriptos[$row['turno']][$row['conjunto']]['carreras'] = $row['carreras'];
		$inscriptos[$row['turno']][$row['conjunto']]['cuatrimestre'] = $row['cuatrimestre'];
	}
	
	foreach ($inscriptos as $turno => $materias) {
		echo "<h2>Turno: {$turno}</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr>";
		echo "<td>Cod</td>";
		echo "<td>Nombre</td>";
		echo "<td>Carreras</td>";
		echo "<td>Cuat</td>";
		echo "<td>Com 15-1</td>";
		echo "<td>Insc 15-1</td>";
		echo "<td>Com 16-1</td>";
		echo "<td>Insc 16-1</td>";
		echo "<td>Com 17-1</td>";
		echo "<td>Insc 17-1</td>";
		echo "<td>Nuevos 17-1</td>";
		echo "<td>Recurs 17-1</td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		
		foreach ($materias as $conjunto => $datos) {
			//print_r($datos);
			echo "<tr>";
			echo "<td>'{$conjunto}</td>";
			echo "<td>{$datos['nombre']}</td>";
			echo "<td>{$datos['carreras']}</td>";
			echo "<td>{$datos['cuatrimestre']}</td>";
			if (isset($datos[2015])) {
				echo "<td>{$datos[2015]['comisiones']}</td>";
				echo "<td>{$datos[2015]['inscriptos']}</td>";
			} else {
				echo "<td>0</td><td>0</td>";
			}
			if (isset($datos[2016])) {
				echo "<td>{$datos[2016]['comisiones']}</td>";
				echo "<td>{$datos[2016]['inscriptos']}</td>";
			} else {
				echo "<td>0</td><td>0</td>";
			}
			
				
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
?>
			</tbody>
		</table>
	</body>
</html>

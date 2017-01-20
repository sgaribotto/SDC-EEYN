<?php
	//display_errors(E_ALL);
	error_reporting(E_ALL);

	//ini_set("memory_limit",-1);

	//Algoritmo para repartir alumnos en aulas

	$dia = $_REQUEST['dia'];
	$turno = $_REQUEST['turno'];
	$letraTurno = substr($turno, 0, 1);
	$anio = $_REQUEST['anio'];
	$cuatrimestre = $_REQUEST['cuatrimestre'];
	$sobreOcupar = 1 + ($_REQUEST['sobreocupar'] / 100);
	$corteMinimo = $_REQUEST['corteMinimo'];
	/*$dia = 'lunes';
	$turno = 'M';
	$letraTurno = 'M';
	$anio = 2015;
	$cuatrimestre = 2;*/
	
	require 'fuentes/conexion.php';
	
	//AULAS DISPONIBLES
	$query = "SELECT  
				a.cod, 
				ROUND(a.capacidad * ($sobreOcupar)) AS capacidad
			FROM aulas AS a
			LEFT JOIN asignacion_aulas AS aa 
				ON (a.cod = aa.aula AND aa.anio = $anio 
					AND aa.cuatrimestre = $cuatrimestre
					AND aa.dia = '$dia'
					AND (aa.turno = '$turno' OR aa.turno = '$letraTurno'))
					AND aa.activo = 1 
			WHERE a.activo = 1 AND ISNULL(aa.materia) AND a.abierta = 1
			ORDER BY a.cod+0";
	
	$result = $mysqli->query($query);
	echo $mysqli->error;
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$aulas[] = $row;
	}
	echo "aulas: ";
	print_r($aulas);
	
	$result->free();
	
	$capacidadTotal = 0;
	foreach ($aulas as $aula) {
		$capacidadTotal += $aula['capacidad'];
	}
	
	//ALUMNOS AUN NO ASIGNADOS
	
	$query = "SELECT m.conjunto, 
				GROUP_CONCAT(DISTINCT m.nombre SEPARATOR '/') AS nombre, 
				SUM(v.cantidad) AS cantidad,
				aa.cantidad_alumnos AS cantidad_asignada,
				aa.comision,
				t.turno
			FROM vista_inscriptos_por_materia AS v 
			LEFT JOIN materia AS m ON m.cod = v.materia
			LEFT JOIN turnos AS t ON t.materia = m.cod  
			LEFT JOIN asignacion_aulas AS aa ON aa.materia = m.conjunto AND aa.anio = $anio AND aa.activo = 1
				AND aa.cuatrimestre = $cuatrimestre AND (aa.turno = '$turno' OR aa.turno = '$letraTurno') AND aa.dia = '$dia'
			WHERE v.anio_academico = $anio 
				AND v.periodo_lectivo = $cuatrimestre
				AND v.turno = '$letraTurno'
				AND t.dia = '$dia' 
				AND (t.turno = '$turno' OR t.turno = '$letraTurno')
			GROUP BY conjunto, comision, aula, t.turno";
	//echo $query;
	$result = $mysqli->query($query);
	echo $mysqli->error;
	$datosMaterias = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		foreach ($row AS $key => $value) {
			if ($key != 'comision' and $key != 'cantidad_asignada') {
				$datosMaterias[$row['conjunto']][$key] = $value;
				//print_r($datosMaterias[$row['conjunto']]);
			} elseif ($key == 'comision') {
				$datosMaterias[$row['conjunto']]['comisiones'][$value] = $row['cantidad_asignada'];
			}
			
		}
		if (!isset($datosMaterias[$row['conjunto']]['suma_asignada']) ) {
			$datosMaterias[$row['conjunto']]['suma_asignada'] = $row['cantidad_asignada'];
		} else {
			$datosMaterias[$row['conjunto']]['suma_asignada'] += $row['cantidad_asignada'];
		}
		
	}
	
	foreach ($datosMaterias as $row) {
		$inscriptos[] = $row;
	}
	
	print_r($inscriptos);
	
	$result->free();
	
	$totalInscriptos = 0;
	foreach ($inscriptos as $key => $value) {
		$inscriptos[$key]['faltaAsignar'] = $value['cantidad'] - $value['suma_asignada'];
		$totalInscriptos += $value['cantidad'] - $value['suma_asignada'];
	}
	
	echo "<br />Inscriptos: ";
	print_r($inscriptos);
	echo "<hr />";
	
echo "Capacidad Total: $capacidadTotal<br />";
echo "Total Inscriptos: $totalInscriptos<br />";

	function asignarAulas($aulas, $inscriptos, $corteMinimo, $dia, $turno, $anio, $cuatrimestre, $mysqli) {
		//Ordeno las aulas por capacidad
		usort($aulas, function($a, $b) {
			if ($a['cod'] != 'LAB' and $b['cod'] != 'LAB') {
				if ($a['capacidad'] == $b['capacidad']) {
					return ($a['cod'] - $b['cod']);
				}
				
				return - ($a['capacidad'] - $b['capacidad']); //ORDEN INVERSO
			} else {
				return  ($b['cod'] - $a['cod']);
			}
		}); 

		//Ordeno los cursos por cantidad de inscriptos
		usort($inscriptos, function($a, $b) {
			return  - ($a['faltaAsignar'] - $b['faltaAsignar']); //ORDEN INVERSO
		});
		
		//print_r($inscriptos);
		$aula = $aulas[0]['cod'];
		$capacidadAula = $aulas[0]['capacidad'];
		$materia = $inscriptos[0]['conjunto'];
		$turno = $inscriptos[0]['turno'];
		//si el primer curso tiene más inscriptos lleno el aula más grande
		if ($inscriptos[0]['faltaAsignar'] <= 0) {
			array_shift($inscriptos);
		} else {
			if ($inscriptos[0]['faltaAsignar'] <= $aulas[0]['capacidad']) {
				echo $inscriptos[0]['faltaAsignar'] .  " de " . $inscriptos[0]['conjunto'] . 
					" ocupan el aula " . $aulas[0]['cod'] . " de " . $aulas[0]['capacidad'] . "<br />";
					
				$cantidad = $inscriptos[0]['faltaAsignar'];
				
				array_shift($aulas);
				array_shift($inscriptos);
				
				
				
			} else {
				if ($corteMinimo <= $inscriptos[0]['faltaAsignar'] - $aulas[0]['capacidad']) {
					//echo "$aulas[0][capacidad] de los $inscriptos[0][faltaAsignar] de $inscriptos[0][conjunto] ocupan un aula de $aulas[0][capacidad]<br />";
					
					$inscriptos[0]['faltaAsignar'] = $inscriptos[0]['faltaAsignar'] - $aulas[0]['capacidad'];
					
					$cantidad = $aulas[0]['capacidad'];
					
					array_shift($aulas);
					usort($inscriptos, function($a, $b) {
						return  - ($a['faltaAsignar'] - $b['faltaAsignar']); //ORDEN INVERSO
					});
					
				} else {
					$estosInscriptos = $inscriptos[0];
					//echo ($aulas[0]['capacidad'] - ($corteMinimo - $inscriptos[0]['faltaAsignar'] + $aulas[0]['capacidad'] ) )." de los $inscriptos[0][faltaAsignar] de $inscriptos[0][conjunto] ocupan un aula de $aulas[0][capacidad]<br />";
					$inscriptos[0]['faltaAsignar'] = $inscriptos[0]['faltaAsignar'] - $corteMinimo;
					
					$cantidad = $inscriptos[0]['faltaAsignar'] - $corteMinimo;
					
					if ($aulas[0]['capacidad'] >= $inscriptos[0]['faltaAsignar']) {
						array_shift($inscriptos);
					}
					array_shift($aulas);
					$inscriptos[] = array( 
										'faltaAsignar'=>$corteMinimo,
										'cod' => $estosInscriptos['conjunto'],
									);
				}
			}
				
				//echo "Sobran $inscriptos[0]";
					echo "<br />";
					
					
			
			
				$comision = calcularComision($materia, $dia, $turno, $anio, $cuatrimestre, $mysqli);
				
				$query = "SELECT DISTINCT t.dia
							FROM turnos AS t
							WHERE materia IN $materia AND turno = '$turno';";
				$result = $mysqli->query($query);
				echo $mysqli->error;
				$diasParaAsignar = array();
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					$diasParaAsignar[] = $row['dia'];
				}
				//print_r($diasParaAsignar);
				$result->free();
				
				foreach ($diasParaAsignar as $dia) {
					$query = "SELECT aula, materia, comision, turno, anio, cuatrimestre
								FROM asignacion_aulas 
								WHERE aula = $aula 
									AND turno = '$turno' 
									AND dia = '$dia'
									AND anio = $anio 
									AND cuatrimestre = $cuatrimestre";
					$result = $mysqli->query($query);
					echo "BORROROS<br/>";
					echo $query;
					print_r($result);
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						print_r($row);
						$query = "DELETE FROM asignacion_aulas 
									WHERE materia = '$row[materia]' 
										AND comision = '$row[comision]'
										AND turno = '$row[turno]' 
										AND anio = $row[anio] 
										AND cuatrimestre = $row[cuatrimestre]";
						$mysqli->query($query);
						echo $mysqli->error;
					}
				}
				
				foreach ($diasParaAsignar as $dia) {
					
					$query = "INSERT INTO asignacion_aulas (aula, materia, cantidad_alumnos, dia, turno, comision, anio, cuatrimestre)
								VALUES ('$aula', '$materia', $cantidad, '$dia', '$turno', $comision, 
								$anio, $cuatrimestre)";
					//echo $query;
					$mysqli->query($query);
					$error = $mysqli->error;
				}
		
			
			echo "RESUMEN PARCIAL<br>";
			echo "Se asignaron $cantidad Alumnos de $materia en el aula $aula de " . $capacidadAula . " alumnos";
			/*echo "Quedan libres las aulas: ";
				print_r($aulas);
				echo "<br />";
				echo "Falta asignar a los inscriptos: ";
				print_r($inscriptos);
				echo "<br /><hr />";*/
		}
		//Repito	
		if (!empty($aulas) and !empty($inscriptos)) {
			asignarAulas($aulas, $inscriptos, $corteMinimo, $dia, $turno, $anio, $cuatrimestre, $mysqli);
		} else {
			echo "Quedan libres las aulas: ";
			print_r($aulas);
			echo "<br />";
			echo "Falta asignar a los inscriptos: ";
			print_r($inscriptos);
			echo "<br />";
		}
	}
	
	function calcularComision($materia, $dia, $turno, $anio, $cuatrimestre, $mysqli) { //PARA USARSE DENTRO DE LA FUNCIÓN ASIGNAR AULAS QUE YA ASIGNA LAS VARIABLES
		
		$query = "SELECT DISTINCT comision + 0 AS comision
					FROM asignacion_aulas
					WHERE dia = '$dia' AND
						turno = '$turno' AND
						anio = $anio AND
						cuatrimestre = $cuatrimestre AND
						materia = '$materia'";
		$result = $mysqli->query($query);
		$comisionesEncontradas = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$comisionesEncontradas[] = $row['comision'];
		}
			
		echo $mysqli->error;
		for ($i = 1; $i < 11; $i++) {
			if (!in_array($i, $comisionesEncontradas) ) {
				$comision = $i;
				break;
			}
		}
		
		$result->free();
	return $comision;
	}
				
	
	asignarAulas($aulas, $inscriptos, $corteMinimo, $dia, $turno, $anio, $cuatrimestre, $mysqli);
?>

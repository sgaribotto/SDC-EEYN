<?php
	echo header('Content-Type: application/json; charset=utf-8');
//Consultas vía AJAX
	//Autoload de la clase.
	session_start();
	function __autoload($class) {
		$classPathAndFileName = "../clases/" . $class . ".class.php";
		require_once($classPathAndFileName);
	}
	
	if (isset($_GET['act'])) {
		require 'conexion.php';
		require 'constantes.php';
		
		$ANIO = 2017;
		$CUATRIMESTRE = 1;
	
		switch($_GET['act']) {
			
			case "listadoDeMaterias":
				foreach ($_REQUEST as $string) {
					if (is_string($string) ) {
						$string = $mysqli->real_escape_string($string);
					}
				}
				
				$dia = $_REQUEST['dia'];
				$turno = $_REQUEST['turno'];
				$letraTurno = substr($turno, 0, 1);
				$anio = $_REQUEST['anio'];
				$cuatrimestre = $_REQUEST['cuatrimestre'];
				
				
					
				$query = "SELECT v.conjunto, 
								LEFT(GROUP_CONCAT(DISTINCT v.nombre_materia SEPARATOR '/'), 50) AS nombre, 
								SUM(v.cantidad) / COUNT(DISTINCT t.dia) AS cantidad,
								aa.cantidad_alumnos AS cantidad_asignada,
								aa.comision,
								t.turno,
                                GROUP_CONCAT(DISTINCT t.dia SEPARATOR ', ') AS dias
							FROM inscriptos_con_conjunto AS v 
							LEFT JOIN turnos AS t ON t.materia = v.materia  
							LEFT JOIN asignacion_aulas AS aa ON aa.materia = v.conjunto AND aa.anio = $anio AND aa.activo = 1
								AND aa.cuatrimestre = $cuatrimestre AND (aa.turno = '$turno' OR aa.turno = '$letraTurno') AND aa.dia = '$dia'
							WHERE v.anio_academico = $anio 
								AND v.periodo_lectivo = $cuatrimestre
								AND v.turno = '$letraTurno'
								 
								AND (t.turno = '$turno' OR t.turno = '$letraTurno')
							GROUP BY conjunto, comision, aula, t.turno
                            HAVING dias LIKE '%$dia%'";
							
				if ($anio == 2017 and $cuatrimestre = 1) {
					$query = "SELECT e.materia AS conjunto, LEFT(e.nombre_materia, 75) AS nombre_materia, e.cantidad,
								aa.cantidad_alumnos AS cantidad_asignada,
								aa.comision, t.turno,
                                GROUP_CONCAT(DISTINCT t.dia SEPARATOR ', ') AS dias
							FROM estimacion AS e
							LEFT JOIN turnos_con_conjunto AS t ON t.materia = e.materia
							LEFT JOIN asignacion_aulas AS aa ON aa.materia = e.materia AND aa.anio = $anio AND aa.activo = 1
								AND aa.cuatrimestre = $cuatrimestre AND (aa.turno = '$turno' OR aa.turno = '$letraTurno') AND aa.dia = '$dia'
							WHERE e.turno = '$letraTurno'
								AND (t.turno = '$turno' OR t.turno = '$letraTurno')
							GROUP BY e.materia, aa.comision, aa.aula, t.turno
                            HAVING dias LIKE '%$dia%'";
				}
				//echo $query;
				$result = $mysqli->query($query);
				if ($mysqli->errno) {
					exit($mysqli->error);
				}
				
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
				$data = json_encode($datosMaterias);
				echo $data;
				break;
				
			case "grillaDeAulas":
			
				foreach ($_REQUEST as $string) {
					if (is_string($string) ) {
						$string = $mysqli->real_escape_string($string);
					}
				}
				
				$dia = $_REQUEST['dia'];
				$turno = $_REQUEST['turno'];
				$letraTurno = substr($turno, 0, 1);
				$anio = $_REQUEST['anio'];
				$cuatrimestre = $_REQUEST['cuatrimestre'];
				
				$query = "SELECT  
								a.cod, 
								a.capacidad,
								a.abierta,
								aa.id AS id_asignacion,
								aa.materia,
								aa.cantidad_alumnos as cantidad_asignada,
								aa.aula,
								aa.comision,
								t.turno,
								t.dia,
								GROUP_CONCAT(DISTINCT m.nombre SEPARATOR '<br />' ) AS nombre
							FROM aulas AS a
							LEFT JOIN asignacion_aulas AS aa 
                                ON (a.cod = aa.aula AND aa.anio = $anio 
									AND aa.cuatrimestre = $cuatrimestre 
									AND aa.activo = 1 
									AND aa.dia = '$dia'
									AND (aa.turno = '$turno' OR aa.turno = '$letraTurno'))
							LEFT JOIN materia AS m ON m.conjunto = aa.materia
							LEFT JOIN turnos AS t ON m.cod = t.materia AND t.turno = '$turno' AND t.dia = '$dia'
							
							WHERE a.activo = 1
							GROUP BY aa.materia, aa.aula, a.cod, t.dia
							ORDER BY a.cod+0";
				//echo $query;
				$result = $mysqli->query($query);
				if ($mysqli->errno) {
					print_r($mysqli->error);
				}
				//echo $query;
				$asignaciones = array();
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					foreach($row as $key => $value) {
						$asignaciones[$row['cod']][$key] = $value;
					}
				}
				
				$responseAjax = json_encode($asignaciones);
				
				echo $responseAjax;
				
				$result->free();
				break;
				
			case "inscriptosMateria":
				foreach ($_REQUEST as $string) {
					if (is_string($string) ) {
						$string = $mysqli->real_escape_string($string);
					}
				}
				
				$dia = $_REQUEST['dia'];
				$turno = $_REQUEST['turno'];
				$letraTurno = substr($turno, 0, 1);
				$anio = $_REQUEST['anio'];
				$cuatrimestre = $_REQUEST['cuatrimestre'];
				$materia = $_REQUEST['materia'];
				
				$query = "SELECT SUM(cantidad) as cantidad
							FROM vista_inscriptos_por_materia
							WHERE materia IN " . $materia . "
								AND anio_academico = $anio
								AND periodo_lectivo = $cuatrimestre
								AND turno = '$letraTurno'";
				if ($anio == 2017 and $cuatrimestre == 1) {
					$query = "SELECT cantidad
									FROM estimacion
									WHERE materia = '$materia'
									AND turno = '$letraTurno'";
				}
				$result = $mysqli->query($query);
				echo $mysqli->error;
				$inscriptos = $result->fetch_array(MYSQLI_ASSOC);
				echo $inscriptos['cantidad'];
				$result->free();
				break;
				
			case 'asignacionDeMateria':
				
				
				foreach ($_REQUEST as $string) {
					if (is_string($string) ) {
						$string = $mysqli->real_escape_string($string);
					}
				}
				//print_r($_REQUEST);
				$aula = $_REQUEST['aula'];
				$cantidad = $_REQUEST['cantidad'];
				$dia = $_REQUEST['dia'];
				$turno = $_REQUEST['turno'];
				$anio = $_REQUEST['anio'];
				$cuatrimestre = $_REQUEST['cuatrimestre'];
				$materia = $_REQUEST['materia'];
				$asignarTodosLosDias = false;
				if (isset($_REQUEST['asignarTodosLosDias'])) {
					$asignarTodosLosDias = true;
					//echo "BLOQUE";
				}
				
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
				
				//Cálculo de turnos
				
				if ($asignarTodosLosDias) {
					$query = "SELECT DISTINCT t.dia
								FROM turnos_con_conjunto AS t
								WHERE materia = '{$materia}' AND turno = '$turno';";
					$result = $mysqli->query($query);
					echo $mysqli->error;
					$diasParaAsignar = array();
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$diasParaAsignar[] = $row['dia'];
					}
					//print_r($diasParaAsignar);
					$result->free();
				} else {
					$diasParaAsignar = array();
					$diasParaAsignar[] = $dia;
				}
				//print_r($diasParaAsignar);
				foreach ($diasParaAsignar as $dia) {
					$query = "SELECT materia, GROUP_CONCAT(dia SEPARATOR ', ') AS dias, turno, cuatrimestre, anio, comision, aula
								FROM asignacion_aulas
								WHERE aula = $aula
									AND turno = '$turno'
									AND anio = $anio
									AND cuatrimestre = $cuatrimestre
								GROUP by materia, aula, turno, comision, anio, cuatrimestre
								HAVING dias LIKE '%$dia%'";
					$result = $mysqli->query($query);
					
					$asignacionesParaBorrar = array();
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$asignacionesParaBorrar[] = $row;
					}
					//print_r($asignacionesParaBorrar);
					
					$borradas = array();
					foreach ($asignacionesParaBorrar as $key => $value) {
						
						$query = "DELETE FROM asignacion_aulas 
									WHERE aula = $value[aula]
									AND materia = '$value[materia]'
									AND turno = '$value[turno]' 
									AND comision = '$value[comision]'
									AND anio = $anio 
									AND cuatrimestre = $cuatrimestre";
						$mysqli->query($query);
					}
					if ($mysqli->affected_rows) {
						$data['borradas'][] = $value;
					}
					
					$query = "INSERT INTO asignacion_aulas (aula, materia, cantidad_alumnos, dia, turno, comision, anio, cuatrimestre)
									VALUES ('$aula', '$materia', $cantidad, '$dia', '$turno', $comision, 
									$anio, $cuatrimestre)";
					//echo $query;
					$mysqli->query($query);
					$error = $mysqli->error;
				
					if ($mysqli->errno) {
						$error = strtolower($mysqli->error);
						//echo strpos($error, 'duplicate') . "<br />";
						if (!(strpos($error, 'duplicate') === false)) {
							if (!(strpos($error, 'aula') === false)) {
								$data['error'] = "El aula ya está asignada en el turno seleccionado";
							} else {
								$data['error'] = "La materia y comisión seleccionada ya tiene un aula asignada";
							}
						} else {
							$data['error'] = "Error desconocido en la base de datos, por favor comuniquese con Santiago";
						}
					}
				}
				
				$query = "SELECT LAST_INSERT_ID() AS id_asignada;";
				$result = $mysqli->query($query);
				$id_asignada = $result->fetch_array(MYSQLI_ASSOC)['id_asignada'];
				
				$letrasComision = ['xx', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
				$data["comision"] = $letrasComision[$comision];
				$data['id_asignada'] = $id_asignada;
				
				echo json_encode($data);
				
				break;
				
			case "eliminarAsignacion":
				$id = $_REQUEST['id'];
				$query = "SELECT materia, dia, turno, cuatrimestre, anio, comision, aula
							FROM asignacion_aulas
							WHERE id = $id";
				$result = $mysqli->query($query);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				
				
				
				$query = "DELETE FROM asignacion_aulas 
							WHERE materia = '$row[materia]'
							AND turno = '$row[turno]'
							AND aula = '$row[aula]'
							AND comision = '$row[comision]'
							AND anio = $row[anio]
							AND cuatrimestre = $row[cuatrimestre]";
				$mysqli->query($query);
				
				break;
			
			case "reiniciarGrilla":
				$codigos = $_REQUEST['codigos'];
				$query = "SELECT materia, dia, turno, cuatrimestre, anio, comision, aula
							FROM asignacion_aulas
							WHERE id IN $codigos";
				$result = $mysqli->query($query);
				
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					
					$query = "DELETE FROM asignacion_aulas 
								WHERE materia = '$row[materia]'
									AND turno = '$row[turno]'
									AND cuatrimestre = $row[cuatrimestre]
									AND anio = $row[anio]
									AND comision = '$row[comision]'
									AND aula = $row[aula]";
					$mysqli->query($query);
					echo $mysqli->error;
				}
				break;
				
			case "ajustarAula":
				$id = $_REQUEST['id_asignacion'];
				$cantidad = $_REQUEST['cantidad'];
				
				$query = "SELECT materia, dia, turno, cuatrimestre, anio, comision, aula
							FROM asignacion_aulas
							WHERE id = $id";
							
				$result = $mysqli->query($query);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				
				$query = "UPDATE asignacion_aulas SET cantidad_alumnos = $cantidad 
							WHERE materia = '$row[materia]'
							AND turno = '$row[turno]'
							AND aula = '$row[aula]'
							AND comision = '$row[comision]'
							AND anio = $row[anio]
							AND cuatrimestre = $row[cuatrimestre]";
				$mysqli->query($query);
				$result->free();
				echo $mysqli->error;
				break;
				
			case "report":
				$query = "SELECT aa.aula, aa.dia, aa.materia, aa.turno, aa.comision, aa.cantidad_alumnos, m.nombre AS nombre
							FROM asignacion_aulas AS aa
							LEFT JOIN materia AS m ON aa.materia = m.conjunto OR aa.materia = CONCAT(m.conjunto, 'B')
							WHERE aa.anio = {$ANIO}
							AND aa.cuatrimestre = {$CUATRIMESTRE}
							AND aa.activo = 1
						GROUP BY aa.turno, aa.dia, aa.aula, aa.materia
						ORDER BY aa.turno, aa.dia, aa.aula, aa.materia;";
						
				$result = $mysqli->query($query);
				echo $mysqli->error;
				
				$datosReporte = array();
				
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						//$datosReporte[$row['turno']][$row['dia']][$row['aula']][$key] = $value;
						$datosReporte[] = $row;
				}
				
				echo json_encode($datosReporte);	
				break;
				
			case 'listadoAulas':
				$query = "SELECT id, cod, capacidad FROM aulas WHERE activo = 1";
				$result = $mysqli->query($query);
				
				$aulas = array();
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					foreach($row as $key => $value) {
						$aulas[$row['cod']][$key] = $value;
					}
				}
				
				echo json_encode($aulas);
				break;
				
			case 'lock':
				$query = "UPDATE aulas SET abierta = '$_GET[valor]' WHERE cod = '$_GET[aula]'";
				echo $query;
				$mysqli->query($query);
				echo $mysqli->error;
				break;
				
			default:
				echo "No existe la acción solicitada";
				
		}
		
		$mysqli->close();
	} else {
		echo "No se solicitó ninguna acción";
	}
	
	
?>

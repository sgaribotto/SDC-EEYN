<?php
	header('Content-Type: text/html; charset=utf-8');
//Consultas vía AJAX
	//Autoload de la clase.
	session_start();
	require_once '../programas.autoloader.php';
	require './constantes.php';

	
	if (isset($_GET['act'])) {
		
			switch($_GET['act']) {
				
				case "errorLogging":
					$errorLog = fopen('errorLog.txt', 'a+');
					
					$error = $_GET['error'];
					$date = date('Y - m - d');
					$sesion = json_encode($_SESSION);
					
					$log = $date . "\t" . $error . "\t" . $sesion . "\n";
					
					fwrite($errorLog, $log);
					fclose($errorLog);
					break;
				
				case "buscarDocente":
					require("./conexion.php");
					$query = "SELECT CONCAT_WS(', ', apellido, nombres) AS docente FROM docente WHERE dni = '$_GET[dni]' ";
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$row = $result->fetch_array(MYSQLI_ASSOC);
						
						echo $row['docente'];
					}
					
					break;
				
				case "agregarAfectacion":
				
					$ANIO = 2017;
					$CUATRIMESTRE = 1;
					
					$docente = new clases\Docente($_GET['dni']);
					
					if (isset($_SESSION['materia'])) {
						$materiaSesion = $_SESSION['materia'];
						$materia = $materiaSesion;
					}
					
					if (isset($_GET['materia'])) {
						$materia = $_GET['materia'];
					}
					
					
					if (isset($materia)) {
						echo $docente->agregarAfectacion($materia, $_GET['tipo'], $ANIO, $CUATRIMESTRE);
					}
					break;
					
				case "agregarUnidadTematica":
					
					$materia = new clases\Materia($_SESSION['materia']);
					$materia->agregarUnidadTematica($_POST['unidad'], $_POST['descripcion'], $ANIO, $CUATRIMESTRE);
					echo "<script>location.assign('../unidadestematicas.php')</script>";
					break;
					
				case "mostrarDescripcionUnidadTematica":
					
					$materia = new clases\Materia($_SESSION['materia']);
					$descripcion = $materia->mostrarUnidadesTematicas($_GET['unidad'], $ANIO, $CUATRIMESTRE);
					if (isset($descripcion[$_GET['unidad']])) {
						echo $descripcion[$_GET['unidad']];
					}
					break;
					
				case "agregarBibliografia":
					
					$materia = new clases\Materia($_SESSION['materia']);
					$materia->agregarBibliografia($_POST['titulo'], $_POST['autor'], $_POST['editorial'], $_POST['paginas'], $ANIO, $CUATRIMESTRE);
					echo "<script>location.assign('../bibliografia.php')</script>";
					break;
					
				case "agregarCarrera":
					require "./conexion.php";
					
					$query = "SELECT cod FROM carrera WHERE cod = '$_POST[cod]' ";
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$query = "UPDATE carrera SET nombre = '$_POST[nombre]', activo = 1 WHERE cod = '$_POST[cod]'  ";
					} else {
						$query = "INSERT INTO carrera (cod, nombre) VALUES ('$_POST[cod]', '$_POST[nombre]')";
					}
					$result->free();
					
					echo $query;
					$mysqli->query($query);
					
					
					$mysqli->close();
					break;
					
				case "mostrarNombreCarrera":
					require "./conexion.php";
					
					$query = "SELECT cod, nombre FROM carrera WHERE cod = '$_POST[cod]' ";
					$result = $mysqli->query($query);
					$row = $result->fetch_array();
					
					echo $row['nombre'];
					
					break;
				
				case "agregarDocente":
					require "./conexion.php";
					
					$query = "SELECT dni FROM docente WHERE dni = '$_POST[dni]' ";
					$result = $mysqli->query($query);
					$_POST['apellido'] = $mysqli->real_escape_string($_POST['apellido']);
					if ($result->num_rows == 1) {
						$query = "UPDATE docente SET apellido = '$_POST[apellido]', nombres = '$_POST[nombre]', fechanacimiento = '$_POST[fechanacimiento]', fechaingreso = '$_POST[fechaingreso]', activo = 1 WHERE dni = '$_POST[dni]'  ";
						$result->free();
					} else {
						$query = "INSERT INTO docente SET dni = '$_POST[dni]', apellido = '$_POST[apellido]', nombres = '$_POST[nombre]', fechanacimiento = '$_POST[fechanacimiento]', fechaingreso = '$_POST[fechaingreso]'";
					}
					echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					break;
				
				case "buscarDNI":
					require "./conexion.php";
					
					$query = "SELECT apellido, nombres, fechanacimiento, fechaingreso FROM docente WHERE dni = '$_POST[dni]' ";
					$result = $mysqli->query($query);
					if ($result->num_rows == 1) {
						$row = $result->fetch_row();
						$datosDocente = ",";
						foreach ($row as $value) {
							$datosDocente .= $value . ",";
						} 
						echo $datosDocente;
					}
					
					
					$result->free();
					$mysqli->close();
					break;
					
				case "eliminarDocente":
					require "./conexion.php";
					$query = "UPDATE docente SET activo = 0 WHERE dni = '$_POST[dni]' LIMIT 1";
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "agregarTurno":
					require "./conexion.php";
					
					$query = "INSERT INTO turnos SET materia = '$_REQUEST[materia]', dia = '$_REQUEST[dia]', turno = '$_REQUEST[turno]', observaciones = '$_REQUEST[observaciones]'";
					
					$mysqli->query($query);
					echo $mysqli->error;
					$mysqli->close();
					break;
				
				case "eliminarTurno":
					require "./conexion.php";
					$query = "DELETE FROM turnos WHERE id = $_POST[id]";
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "agregarMateria":
					require "./conexion.php";
					
					$query = "SELECT cod FROM materia WHERE cod = '$_POST[cod]' ";
					echo $query;
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$query = "UPDATE materia SET nombre = '$_POST[nombre]', carrera = '$_POST[carrera]', plan = '$_POST[plan]', cuatrimestre = '$_POST[cuatrimestre]', contenidosminimos = '$_POST[contenidosminimos]', activo = 1 WHERE cod = '$_POST[cod]'  ";
						$result->free();
					} else {
						$query = "INSERT INTO materia SET cod = '$_POST[cod]', nombre = '$_POST[nombre]', carrera = '$_POST[carrera]', plan = '$_POST[plan]', cuatrimestre = '$_POST[cuatrimestre]', contenidosminimos = '$_POST[contenidosminimos]'";
					}
					echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "buscarMateria":
					require "./conexion.php";
					
					$query = "SELECT nombre, carrera, plan, cuatrimestre, contenidosminimos, conjunto FROM materia WHERE cod = '$_POST[cod]' ";
					
					$result = $mysqli->query($query);
					if ($result->num_rows == 1) {
						$row = $result->fetch_row();
						$datosMateria = "/*/";
						foreach ($row as $value) {
							$datosMateria .= $value . "/*/";
						} 
						echo $datosMateria;
					}
					
					
					$result->free();
					$mysqli->close();
					break;
				
				case "eliminarMateria":
					require "./conexion.php";
					$query = "UPDATE materia SET activo = 0 WHERE cod = '$_POST[cod]' LIMIT 1";
					$mysqli->query($query);
					$mysqli->close();
					break;
				
				case "agregarPersonal":
					require "./conexion.php";
					
					$query = "SELECT dni FROM personal WHERE dni = '$_POST[dni]' ";
					$password = md5($_POST['usuario']);
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$query = "UPDATE personal SET apellido = '$_POST[apellido]', nombres = '$_POST[nombre]', usuario = '$_POST[usuario]', password = '$password', activo = 1 WHERE dni = '$_POST[dni]'  ";
						$result->free();
					} else {
						$query = "INSERT INTO personal SET dni = '$_POST[dni]', apellido = '$_POST[apellido]', nombres = '$_POST[nombre]', password = '$password', usuario = '$_POST[usuario]' ";
					}
					echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					break;
				
				case "buscarPersonal":
					require "./conexion.php";
					
					$query = "SELECT apellido, nombres, usuario FROM personal WHERE dni = '$_POST[dni]' ";
					$result = $mysqli->query($query);
					if ($result->num_rows == 1) {
						$row = $result->fetch_array(MYSQLI_ASSOC);
						$datos = json_encode($row);
						echo $datos;
					}
					$result->free();
					$mysqli->close();
					break;
					
				case "eliminarPersonal":
					require "./conexion.php";
					$query = "UPDATE personal SET activo = 0 WHERE dni = '$_POST[dni]' LIMIT 1";
					$mysqli->query($query);
					$mysqli->close();
					break;
				
				case "agregarResponsable":
					require "./conexion.php";
					
					$query = "SELECT id FROM responsable WHERE usuario = '$_POST[usuario]' AND materia = '$_POST[materia]' ";
					echo $query;
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$query = "UPDATE responsable SET activo = 1 WHERE usuario = '$_POST[usuario]' AND materia = '$_POST[materia]' ";
						$result->free();
					} else {
						$query = "INSERT INTO responsable SET usuario = '$_POST[usuario]', materia = '$_POST[materia]' ";
					}
					echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "eliminarResponsable":
					require "./conexion.php";
					$query = "UPDATE responsable SET activo = 0 WHERE id = '$_POST[id]' LIMIT 1";
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "mostrarPlanDeClase";
					$materia = new clases\Materia($_SESSION['materia']);
					$planDeClase = $materia->mostrarPlanDeClase($_POST['clase'], $ANIO, $CUATRIMESTRE);
					
					$datos = "";
					foreach ($planDeClase as $value) {
						$datos .= $value . '|';
					}
					
					echo $datos;
					break;
					
				case "agregarPlanDeClase":
					require "./conexion.php";
					
					$query = "SELECT id FROM cronograma WHERE clase = '$_POST[clase]' AND materia = '$_SESSION[materia]' AND anio = $ANIO AND cuatrimestre = $CUATRIMESTRE ";
					//echo $query . "<br />";
					
					/*$docentes = implode('/ ', $_POST['docente']);
					$bibliografia = implode('/ ', $_POST['bibliografia']);
					$metodo = implode('/ ', $_POST['metodo']);*/
										
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						
						$query = "UPDATE cronograma SET fecha = '$_POST[fecha]', unidadtematica = '$_POST[unidadtematica]', descripcion = '$_POST[descripcion]'
										WHERE clase = '$_POST[clase]' AND materia = '$_SESSION[materia]' AND anio = $ANIO AND cuatrimestre = $CUATRIMESTRE ";
						$result->free();
					} else {
						$query = "INSERT INTO cronograma SET fecha = '$_POST[fecha]', unidadtematica = '$_POST[unidadtematica]', descripcion = '$_POST[descripcion]', 
										clase = '$_POST[clase]', materia = '$_SESSION[materia]', anio = $ANIO, cuatrimestre = $CUATRIMESTRE ";
					}
					//echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					echo "<script>location.assign('../cronograma.php?clase=" . $_POST['clase'] . "');</script>";
					break;
					
				case "mostrarPlanDeClase";
					$materia = new clases\Materia($_SESSION['materia']);
					$planDeClase = $materia->mostrarPlanDeClase($_POST['clase']);
					
					$datos = "";
					foreach ($planDeClase as $value) {
						$datos .= $value . '|';
					}
					
					echo $datos;
					break;
					
				case "agregarAula":
					require "./conexion.php";
					
					$query = "SELECT id FROM aulas WHERE cod = '$_REQUEST[aula]'";
										
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						
						$query = "UPDATE aulas SET activo = 1, capacidad = $_REQUEST[capacidad], mas_info = '$_REQUEST[mas_info]'
										WHERE cod = '$_REQUEST[aula]'";
						$result->free();
					} else {
						$query = "INSERT INTO aulas SET activo = 1, capacidad = $_REQUEST[capacidad], mas_info = '$_REQUEST[mas_info]', cod = '$_REQUEST[aula]'";
					}
					//echo $query;
					
					$mysqli->query($query);
					$mysqli->close();
					//echo "<script>location.assign('../cronograma.php?clase=" . $_POST['clase'] . "');</script>";
					break;
					
				case "mostrarAula":
					require "./conexion.php";
					
					$query = "SELECT capacidad, mas_info 
								FROM aulas 
								WHERE cod = '$_REQUEST[aula]'";
					//echo $query;
					$result = $mysqli->query($query);
					$row = array();
					if ($result->num_rows == 1) {
						$row = $result->fetch_array(MYSQLI_ASSOC);
						//print_r($row);
						
					}
					$data = implode(' | ', $row);
					
					$result->free();
					$mysqli->close();
					echo $data;
					break;
					
				case "eliminarAula":
					require "./conexion.php";
					$query = "UPDATE aulas SET activo = 0 WHERE id = $_REQUEST[id] LIMIT 1";
					echo $query;
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "eliminarAfectacion":
					require "./conexion.php";
					$id = $mysqli->real_escape_string($_POST['id']);
					$query = "UPDATE afectacion SET activo = 0 WHERE id = {$id} LIMIT 1";
					
					$mysqli->query($query);
					echo $mysqli->error;
					$mysqli->close();
					break;
					
				case "eliminarUnidadTematica":
					require "./conexion.php";
					$query = "UPDATE unidad_tematica SET activo = 0 
							WHERE 
								unidad = '$_POST[unidadtematica]' 
								AND materia = '$_SESSION[materia]' 
								AND anio = $ANIO 
								AND cuatrimestre = $CUATRIMESTRE";
					//echo $query;
					$mysqli->query($query);
					//echo $mysqli->error;
					$mysqli->close();
					break;
					
				case "eliminarBibliografia":
					require "./conexion.php";
					$query = "UPDATE bibliografia SET activo = 0 WHERE id = '$_POST[id]' LIMIT 1";
					echo $query;
					$mysqli->query($query);
					$mysqli->close();
					break;
				
				case "eliminarPlanDeClase":
					require "./conexion.php";
					$query = "DELETE FROM cronograma WHERE id = '$_POST[id]' LIMIT 1";
					echo $query;
					$mysqli->query($query);
					$mysqli->close();
					break;
					
				case "listaDocentes":
					require "./conexion.php";
					$query = "SELECT dni, apellido, nombres FROM docente ORDER BY apellido, nombres";
					$result = $mysqli->query($query);
					
					$listaDocentes = "";
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						$listaDocentes .= $row['dni'] . " -- " . $row['dni'] . " - " . $row['apellido'] . ", " . $row['nombres'] . "***";
					}
					
					echo $listaDocentes;
					$mysqli->close();
					break;
					
				case "agregarAgregadosCronograma":
					require "./conexion.php";
					$tipo = $_POST['tipo'];
					$clase = $_POST['clase'];
					$valor = json_encode($_POST, JSON_UNESCAPED_UNICODE);
					
					$query = "INSERT INTO agregados_cronograma (tipo, materia, clase, valor, anio, cuatrimestre) VALUES
								('$tipo', $_SESSION[materia], $clase, '$valor', $ANIO, $CUATRIMESTRE)";
					echo $query;
					$mysqli->query($query);
					
					$mysqli->close();
					
					break;
					
				case "actualizarTabla":
					require "./conexion.php";
					$clase = $_POST['clase'];
					$materia = $_SESSION['materia'];
					$tabla = $_GET['tabla'];
					
					$query = "SELECT valor, id
										FROM agregados_cronograma
										WHERE clase = $clase AND materia = $materia AND anio = $ANIO AND cuatrimestre = $CUATRIMESTRE AND activo = 1 AND tipo = '$tabla' ";
					$result = $mysqli->query($query);
					switch ($tabla){
						case "bibliografia":
							$valores = array();
							$totalPaginas = 0;
							$tabla = "";
							while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
								$valores[$row['id']] = $row['valor'];
								
							}
							
							foreach ($valores as $id => $value) {
								$valoresTabla = json_decode($value);
								//print_r($valoresTabla);
								$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->titulo . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->paginas . '</td>';
								$tabla .= '<td class="formularioLateral correlatividadesTable"><button type="button" class="botonEliminarAgregadoCronograma" data-id="' . $id . '" >X</button></td>';
							$tabla .= '</tr>';
								$totalPaginas += $valoresTabla->paginas;
							}
							
							$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . '<b>TOTAL DE PÁGINAS</b>' . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral"><b>' . $totalPaginas . '</b></td>';
								$tabla .= '</tr>';
							break;
							
							
							
						case "metodo":
							$valores = array();
							$totalActivo = 0;
							$claseCubierta = 0;
							$tabla = "";
							while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
								$valores[$row['id']] = $row['valor'];
								
								
							}
							
							foreach ($valores as $id => $value) {
								$valoresTabla = json_decode($value);
								//print_r($valoresTabla);
								$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->metodo . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->activo . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . (100 - $valoresTabla->activo) . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->porcentajeClase . '</td>';
								$tabla .= '<td class="formularioLateral correlatividadesTable"><button type="button" class="botonEliminarAgregadoCronograma" data-id="' . $id . '" >X</button></td>';
								$tabla .= '</tr>';
								$totalActivo += ($valoresTabla->activo * $valoresTabla->porcentajeClase / 100);
								$claseCubierta += $valoresTabla->porcentajeClase;
							}
							
							$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . '<b>TOTALES</b>' . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral"><b>' . $totalActivo . '</b></td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral"><b>' . (100 - $totalActivo) . '</b></td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral alerta100 porcentajeCubierto"><b>' . $claseCubierta . '</b></td>';
								$tabla .= '</tr>';
							break;
						
						case "docente":
							$valores = array();
							$tabla = "";
							while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
								$valores[$row['id']] = $row['valor'];
							}
							
							foreach ($valores as $id => $value) {
								$valoresTabla = json_decode($value);
								//print_r($valoresTabla);
								$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->docente . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . $valoresTabla->horasClase . '</td>';
								$tabla .= '<td class="formularioLateral correlatividadesTable"><button type="button" class="botonEliminarAgregadoCronograma" data-id="' . $id . '" >X</button></td>';
								$tabla .= '</tr>';
								
							}
							
							/*$tabla .= '<tr class="agregadosCronograma formularioLateral">';
								$tabla .= '<td class="agregadosCronograma formularioLateral">' . '<b>TOTALES</b>' . '</td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral"><b>' . $totalActivo . '</b></td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral"><b>' . (100 - $totalActivo) . '</b></td>';
								$tabla .= '<td class="agregadosCronograma formularioLateral alerta100"><b>' . $claseCubierta . '</b></td>';
								$tabla .= '</tr>';*/
							break;
							
							
					}
					print_r($tabla);
					
					$result->free();
					
					$mysqli->close();
					break;
					
				case "eliminarAgregadoCronograma":
					require "./conexion.php";
					
					$query = "DELETE FROM agregados_cronograma WHERE id = $_POST[id]";
					$mysqli->query($query);
					echo $query;
					$mysqli->close();
					break;
					
				case "toggleMenuAdmin":
					if (isset($_SESSION['admin']) and $_SESSION['admin']) {
						$_SESSION['admin'] = false;
					} else {
						$_SESSION['admin'] = true;
					}
					print_r($_SESSION['admin']);
					break;
					
				case "esAdmin":
					print_r($_SESSION['admin']);
					break;
					
				case "traerProgramaMateria":
					$_SESSION['materiaTemporal'] = $_POST['materia'];
					$_SESSION['cuatrimestreTemporal'] = $_POST['periodo'];
					break;
					
				case "actualizarTablaAceptarDesignaciones":
					require "./conexion.php";
					
					$carreras = array();
								$estados = array();
								
								if (in_array(2, $_SESSION['permiso'])) { //PERMISOS DEL SECRETARIO
									 $carreras[] = 2;
									 $carreras[] = 1;
									 $carreras[] = 3;
									 $carreras[] = 4;
									 $opcionesEstado = array(
										'Pendiente' => 'disabled',
										'AprobadoCOORD' => ['AprobadoSA', 'RechazadoSA'],
										'RechazadoCOORD' => 'disabled',
										'AprobadoSA' => 'disabled',
										'RechazadoSA' => 'disabled',
										'AprobadoADMIN' => 'disabled',
										'RechazadoADMIN' => ['AprobadoSA', 'RechazadoSA'],
										'designado' => 'disabled',
									);
								} 
								if (in_array(3, $_SESSION['permiso'])) { //PERMISOS DEL DIRECTOR DE ADMINISTRACIÓN
									 $carreras[] = 2;
									 $carreras[] = 1;
									 $carreras[] = 3;
									 $carreras[] = 4;
									 $opcionesEstado = array(
										'Pendiente' => 'disabled',
										'AprobadoCOORD' => 'disabled',
										'RechazadoCOORD' => 'disabled',
										'AprobadoSA' => ['AprobadoADMIN', 'RechazadoADMIN'],
										'RechazadoSA' => 'disabled',
										'AprobadoADMIN' => 'disabled',
										'RechazadoADMIN' => 'disabled',
										'designado' => 'disabled',
									);
								} 
								if (in_array(4, $_SESSION['permiso'])) { //Permisos del director de carrera ECONOmiA
									 $carreras[] = 2;
									 $carreras[] = 4;
									 $opcionesEstado = array(
										'Pendiente' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoCOORD' => 'disabled',
										'RechazadoCOORD' => 'disabled',
										'AprobadoSA' => 'disabled',
										'RechazadoSA' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoADMIN' => 'disabled',
										'RechazadoADMIN' => 'disabled',
										'designado' => 'disabled',
									);
								} 
								if (in_array(5, $_SESSION['permiso'])) { //Permisos del director de carrera ADMIN
									 $carreras[] = 1;
									 $carreras[] = 4;
									 $opcionesEstado = array(
										'Pendiente' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoCOORD' => 'disabled',
										'RechazadoCOORD' => 'disabled',
										'AprobadoSA' => 'disabled',
										'RechazadoSA' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoADMIN' => 'disabled',
										'RechazadoADMIN' => 'disabled',
										'designado' => 'disabled',
									);
								} 
								if (in_array(6, $_SESSION['permiso'])) { //Permisos del director de carrera TURISMO
									 $carreras[] = 3;
									 $opcionesEstado = array(
										'Pendiente' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoCOORD' => 'disabled',
										'RechazadoCOORD' => 'disabled',
										'AprobadoSA' => 'disabled',
										'RechazadoSA' => ['AprobadoCOORD', 'RechazadoCOORD'],
										'AprobadoADMIN' => 'disabled',
										'RechazadoADMIN' => 'disabled',
										'designado' => 'disabled',
									);
								} 
								
							$inCarreras = "(" . implode(', ', $carreras) . ")";			
					
					$where = " WHERE a.activo = 1 AND c.id in $inCarreras";
					
					foreach ($_POST as $key => $value) {
						if ($value != "") {
							$where .= " AND " . str_replace('*', '.', $key) . " LIKE '%$value%' ";
						}
					}
					
					$query = "SELECT a.id, CONCAT(d.apellido, ', ', d.nombres) AS docente, m.nombre, m.cod as cod_materia, c.cod, 
								CONCAT(a.anio, '-', a.cuatrimestre) as periodo, a.tipoafectacion, a.estado
							FROM afectacion AS a
							LEFT JOIN docente AS d ON a.docente = d.id
							LEFT JOIN materia AS m ON a.materia = m.cod
							LEFT JOIN carrera AS c ON m.carrera = c.id
								$where";
								
					///echo $query;
					echo "<br />";
					
					
					$result = $mysqli->query($query);
					/*if ($mysqli->errno) {
						printf("Database error:<br /> %s", $mysqli->error);
						exit();
					}*/ //ERRORES DE MYSQL
					
					echo "<table class='aceptarDesignacion'><thead class='aceptarDesignacion'>
						<tr class='plantelActual'>
							<th class='aceptarDesignacion' style='width:35%;'>Docente</th>
							<th class='aceptarDesignacion' style='width:35%;'>Materia</th>
							<!--<th class='aceptarDesignacion' style='width:10%;'>Carrera</th>
							<th class='aceptarDesignacion' style='width:10%;'>Periodo</th>-->
							<th class='aceptarDesignacion' style='width:15%;'>Cargo</th>
							<th class='aceptarDesignacion' style='width:15%;'>Estado</th>
						</tr></thead>";
					echo "<tbody class='aceptarDesignacion'>";
					while ($row = $result->fetch_array(MYSQL_ASSOC)) {
						echo "<tr class='aceptarDesignacion'>
								<td class='aceptarDesignacion'>$row[docente]</td>
								<td class='aceptarDesignacion linkResumenMateria' data-cod='$row[cod_materia]'>$row[nombre]</td>
								<!--<td class='aceptarDesignacion'>$row[cod]</td>
								<td class='aceptarDesignacion'>$row[periodo]</td>-->
								<td class='aceptarDesignacion'>$row[tipoafectacion]</td>";
								
								
								$estado = "<select class='aceptarDesignacion' data-id='$row[id]'>
											<option class='aceptarDesignacion' value='$row[estado]' selected='selected'>$row[estado]</option>";
									
									if ($opcionesEstado[$row['estado']] != 'disabled') {
										
										foreach ($opcionesEstado[$row['estado']] as $opcion) {
											$estado .= "<option class='aceptarDesignacion'>$opcion</option>";
										}
									}
								$estado .= "</select>";
								
								echo "<td class='aceptarDesignacion'>$estado</td>
							</tr>";
					}
					
					
					
					echo "</tbody></table>";
					
					$result->free();
					$mysqli->close();
					break;
					
				case "cambiarEstadoDesignacion":
					require "conexion.php";
					$id = $_POST['id'];
					$estado = $_POST['estado'];
					
					$query = "UPDATE afectacion SET estado = '$estado' WHERE id = $id ";
					//echo $query;
					$mysqli->query($query);
					
					$mysqli->close();
					break;
					
				case "signacionDeAula":
					require 'conexion.php';
					//print_r($_REQUEST);
					$query = "INSERT INTO asignacion_aulas (aula, materia, cantidad_alumnos, dia, turno, comision, anio, cuatrimestre)
								VALUES ('$_REQUEST[aula]', '$_REQUEST[conjunto]', $_REQUEST[cantidad], '$_REQUEST[dia]', '$_REQUEST[turno]', 
								'$_REQUEST[comision]', $_REQUEST[anio], $_REQUEST[cuatrimestre])";
					$mysqli->query($query);
					$error = $mysqli->error;
					
					if ($mysqli->errno) {
						$error = strtolower($mysqli->error);
						//echo strpos($error, 'duplicate') . "<br />";
						if (!(strpos($error, 'duplicate') === false)) {
							if (!(strpos($error, 'aula') === false)) {
								echo "El aula ya está asignada en el turno seleccionado";
							} else {
								echo "La materia y comisión seleccionada ya tiene un aula asignada";
							}
						} else {
							echo "Error desconocido en la base de datos, por favor comuniquese con Santiago";
						}
					}
							
					$mysqli->close();
								
					break;
				
				case "eliminarAsignacionDeAula":
					require "./conexion.php";
					//print_r ($_REQUEST);
					$query = "DELETE FROM asignacion_aulas WHERE id = $_REQUEST[id]";
					$mysqli->query($query);
					echo $mysqli->error;
					$mysqli->close();
					break;
					
				case "listarMaterias":
					require 'conexion.php';
					
					$filtro['m.nombre'] = $_REQUEST['materia'];
					$filtro['m.cuatrimestre'] = $_REQUEST['cuatrimestre'];
					$filtro['m.carrera'] = $_REQUEST['carrera'];
					
					$tipos = array();
					$carreras = '()';
					$tipos['designados'] = "('designados')";
					
					if (in_array(2, $_SESSION['permiso']) ) {
						$tipos['pendientes'] = "('AprobadoCOORD', 'RechazadoADMIN')";
						$tipos['aceptados'] = "('AprobadoSA', 'AprobadoADMIN')";
						$tipos['rechazados'] = "('RechazadoCOORD', 'RechazadoSA')";
						$tipos['propuestos'] = "('Pendiente')";
						$carreras = '(1, 2, 3, 4, 5)';
					} elseif (in_array(3, $_SESSION['permiso'])  ) {
						$tipos['pendientes'] = "('AprobadoSA')";
						$tipos['aceptados'] = "('AprobadoADMIN')";
						$tipos['rechazados'] = "('RechazadoCOORD', 'RechazadoSA', 'RechazadoADMIN')";
						$tipos['propuestos'] = "('Pendiente', 'AprobadoCOORD')";
						$carreras = '(1, 2, 3, 4, 5)';
					} elseif (in_array(4, $_SESSION['permiso']) or in_array(5, $_SESSION['permiso']) or in_array(6, $_SESSION['permiso']) ) {
						$tipos['pendientes'] = "('RechazadoSA', 'Pendiente')";
						$tipos['aceptados'] = "('AprobadoCOORD', 'AprobadoSA', 'AprobadoADMIN')";
						$tipos['rechazados'] = "('RechazadoCOORD','RechazadoADMIN')";
						$tipos['propuestos'] = "('')";
						if (in_array(4, $_SESSION['permiso'])) {
							$carreras = '(2, 4)';
						}
						if (in_array(5, $_SESSION['permiso'])) {
							$carreras = '(1, 4)';
						}
						if (in_array(6, $_SESSION['permiso'])) {
							$carreras = '(3)';
						}
					}
					
					$where = "1 = 1 AND c.id IN $carreras ";
					foreach ($filtro as $key => $value) {
						if ($value != "") {
							$where .= "AND $key = '$value' ";
						}
					}
					
					
					$query = "SELECT m.cuatrimestre, m.conjunto, m.nombre, m.plan, c.nombre AS carrera,
									SUM(IF (a.estado IN $tipos[pendientes] AND a.cuatrimestre = 2 AND a.anio = 2015 AND a.activo = 1, 1, 0)) AS pendiente,
									SUM(IF (a.estado IN $tipos[aceptados] AND a.cuatrimestre = 2 AND a.anio = 2015 AND a.activo = 1, 1, 0)) AS aceptado,
									SUM(IF (a.estado IN $tipos[rechazados] AND a.cuatrimestre = 2 AND a.anio = 2015 AND a.activo = 1, 1, 0)) AS rechazado,
									SUM(IF (a.estado IN $tipos[propuestos] AND a.cuatrimestre = 2 AND a.anio = 2015 AND a.activo = 1, 1, 0)) AS propuesto,
									SUM(IF (a.estado IN $tipos[designados] AND a.cuatrimestre = 2 AND a.anio = 2015 AND a.activo = 1, 1, 0)) AS designado
								FROM materia AS m
								LEFT JOIN carrera AS c ON m.carrera = c.id
								LEFT JOIN afectacion AS a ON a.materia = m.cod
								WHERE $where
								GROUP BY conjunto
								ORDER BY m.carrera, m.cuatrimestre";
								
								//echo $query;
					
					$result = $mysqli->query($query);
					//echo $mysqli->error;
					$materias = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						//$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['turnos'][] = $row['turno'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['pendiente'] = $row['pendiente'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['propuesto'] = $row['propuesto'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['aceptado'] = $row['aceptado'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['rechazado'] = $row['rechazado'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['designado'] = $row['designado'];
					}
					echo "<table class='materias'>";
						foreach ($materias AS $carrera => $materia) {
							echo "<tr class='subtitulo'>
									<th colspan='6' style='text-align:center;font-size:1.2em;'>Carrera: $carrera</th>
								</tr>";
							foreach ($materia AS $cuatrimestre => $datos) {
								echo "<tr class='subtitulo'>
									<th class='materias'>Cuatrimestre $cuatrimestre</th>
									<th class='materias'>Pendientes</th>
									<th class='materias'>Propuestos</th>
									<th class='materias'>Aceptados</th>
									<th class='materias'>Rechazados</th>
									<th class='materias'>Designados</th>
								</tr>";
								foreach ($datos AS $nombre => $turno) {
									
									echo "<tr class='info'>
											<td class='info masInfo'>$nombre</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[pendiente]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[propuesto]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[aceptado]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[rechazado]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[designado]</td>";
											//<td class='info'>". implode(', ', $turno['turnos']) . "</td>";
									echo "</tr>";
								}
							}
						}
					echo "</table>";
					break;
					
					case "buscarConjunto":
					require "./conexion.php";
					
					$query = "SELECT conjunto FROM materia WHERE cod = '$_POST[cod]' LIMIT 1";
					$result = $mysqli->query($query);
					
					if ($result->num_rows == 1) {
						$conjunto = $result->fetch_array(MYSQL_ASSOC);
						$result->free();
						
						$query = "SELECT cod, nombre FROM materia WHERE conjunto = '$conjunto[conjunto]'";
						//echo $query;
						$result = $mysqli->query($query);
						
						$asociadas = array();
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							$asociadas[] = $row;
						}
						
						$asociadas = json_encode($asociadas);
						echo $asociadas;
						$result->free();
						
					} else {
						$error['error'] = "No se encontró la materia";
						echo json_encode($error);
					}
					
					$mysqli->close();
					break;
					
				case "agregarConjunto":
					require 'conexion.php';
					//print_r($_REQUEST);
					$query = "SELECT conjunto FROM materia WHERE cod = '$_REQUEST[cod]'";
					
					$result = $mysqli->query($query);
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$conjunto[0] = $row['conjunto'];
					}
					//echo $conjunto[0];
					$result->free();
					echo $mysqli->error;
					
					$query = "SELECT conjunto FROM materia WHERE cod = '$_REQUEST[agregar]'";
					$result = $mysqli->query($query);
					
					if ($result->num_rows) {
					
						if (!preg_match('/[\(\s]' . $_REQUEST['agregar'] . '[,\)]/', $conjunto[0])) {
							$conjunto[1] = preg_replace('/\(|\)/', '', $conjunto[0]);
							
							$conjunto[1] = explode(', ', $conjunto[1]);
							
							$conjunto[1][] = $_REQUEST['agregar'];
							sort($conjunto[1]);
							$conjunto[1] = "(" . implode(', ', $conjunto[1]) . ")";
						
							//echo $conjunto[1];
							$query = "UPDATE materia SET conjunto = '$conjunto[1]' WHERE conjunto = '$conjunto[0]' OR cod = '$_REQUEST[agregar]'";
							$mysqli->query($query);
							echo $mysqli->error;
						}
					} else {
						$error['error'] = "No se encontró la materia a agregar";
						echo json_encode($error);
					}
					
							
					$mysqli->close();
								
					break;
					
				case "eliminarConjunto":
					require "./conexion.php";
					
					$retirar = $_REQUEST['cod'];
					
					$query = "SELECT conjunto FROM materia WHERE cod = '$_REQUEST[cod]'";
					$result = $mysqli->query($query);
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$conjuntoOriginal = $row['conjunto'];
					}
					
					$conjunto = preg_replace('/([\(\s])' . $retirar . '([,\)])/', '$1$2', $conjuntoOriginal);
					$conjunto = str_replace(', )', ')', $conjunto);
					$conjunto = str_replace('(, ', '(', $conjunto);
					$conjunto = str_replace(', ,', ',', $conjunto); 
					//print_r ($_REQUEST);
					$query = "UPDATE materia SET conjunto = '$conjunto' WHERE conjunto = '$conjuntoOriginal'";
					$mysqli->query($query);
					echo $mysqli->error;
					
					$query = "UPDATE materia SET conjunto = '($retirar)' WHERE cod = '$retirar'";
					$mysqli->query($query);
					echo $mysqli->error;
					$mysqli->close();
					break;
					
				case "agregarAsignacionComision":
					require "./conexion.php";
					
					$ANIO = 2017;
					$CUATRIMESTRE = 1;
					
					$materia = new clases\Materia($_SESSION['materia']);
					$conjunto = $materia->datosMateria['conjunto'];
					$id = $_REQUEST['comision'];
					$query = "SELECT turno, horario, nombre_comision, dependencia
						FROM comisiones_abiertas 
						WHERE id = {$id};";
					
					$result = $mysqli->query($query);
					$datos = $result->fetch_array(MYSQLI_ASSOC);
					
					
					
					$result->free();
					
					
					
					//Prevención de duplicados
					$query = "SELECT COUNT(*) AS cantidad FROM asignacion_comisiones
								WHERE turno = '{$datos['turno']}'
									AND materia = '{$conjunto}'
									AND comision = '{$datos['nombre_comision']}'
									AND dependencia = '{$datos['dependencia']}'
									AND anio = {$ANIO}
									AND cuatrimestre = {$CUATRIMESTRE};";
					$result = $mysqli->query($query);
					//echo '{error:MYSQL-> ' . $mysqli->error . '}'; 
					$cantidad = $result->fetch_array(MYSQL_ASSOC)['cantidad'];
					$result->free();
					
					if (!$cantidad) {
						$query = "INSERT INTO asignacion_comisiones (docente, materia, turno, comision, dependencia, anio, cuatrimestre)
									VALUES ({$_REQUEST['docente']}, '{$conjunto}', '{$datos['turno']}', '{$datos['nombre_comision']}',
												'{$datos['dependencia']}', {$ANIO}, {$CUATRIMESTRE})";
						$mysqli->query($query);
						$mensajes['exito'] = 'true';
					} else {
						//NO DUPLICAR
						/*$mensajes['error'] = "Solo puede agregar un docente por comisión en esta etapa";*/
						
						//DUPLICAR HABILITADO
						$query = "INSERT INTO asignacion_comisiones (docente, materia, turno, comision, dependencia, anio, cuatrimestre)
									VALUES ({$_REQUEST['docente']}, '{$conjunto}', '{$datos['turno']}', '{$datos['nombre_comision']}',
												'{$datos['dependencia']}', {$ANIO}, {$CUATRIMESTRE})";
						$mysqli->query($query);
						$mensajes['exito'] = 'true';
						
					}
					echo json_encode($mensajes);			
					$mysqli->close();
					break;
					
				case "eliminarComisionAsignada":
					require "./conexion.php";
					
					$id = $_REQUEST['id'];
					$query = "DELETE FROM asignacion_comisiones WHERE id = $id";
					echo $query;
					$mysqli->query($query);
					
					$mysqli->close();
					
					break;
				case "tablaAsignacionComisiones":
					$materia = new clases\Materia($_SESSION['materia']);
					require 'conexion.php';
					
					$ANIO = 2017;
					$CUATRIMESTRE = 1;
					
					$query = "SELECT ac.id, CONCAT_WS(', ', d.apellido, d.nombres) AS docente, ac.materia, ac.comision, ac.dependencia
								FROM asignacion_comisiones AS ac
								LEFT JOIN docente AS d ON d.id = ac.docente
								WHERE anio = $ANIO AND cuatrimestre = $CUATRIMESTRE AND ac.materia = '{$materia->datosMateria["conjunto"]}'
								ORDER BY ac.turno, ac.comision";
					$result = $mysqli->query($query);
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						
						echo "<tr class='formularioLateral correlatividadesTable'>
								<td class='formularioLateral correlatividadesTable'>$row[docente]</td>
								<td class='formularioLateral correlatividadesTable'>$row[dependencia]</td>
								<td class='formularioLateral correlatividadesTable'>$row[materia]</td>
								<td class='formularioLateral correlatividadesTable'>$row[comision]</td>
								<td class='formularioLateral correlatividadesTable'><button type='button' class='botonEliminar' data-id='$row[id]' >X</button></td>
							</tr>";
						
					}
					break;
					
				case "tablaEquipoDocente":
				
					$ANIO = 2017;
					$CUATRIMESTRE = 1;
					
					$materia = new clases\Materia($_SESSION['materia']);
					$equipoDocente = $materia->mostrarEquipoDocente('*', $ANIO, $CUATRIMESTRE);
					
					if (empty($equipoDocente)) {
						echo "<tr><td colspan='2'>No hay docentes cargados</td></tr>";
					} else {
					
						foreach ($equipoDocente as $row) {
							echo "<tr class='formularioLateral correlatividadesTable'>
									<td class='formularioLateral correlatividadesTable'>$row[docente]</td>
									<td class='formularioLateral correlatividadesTable'>$row[tipoafectacion]</td>
									<td class='formularioLateral correlatividadesTable'><button type='button' class='botonEliminar' data-id='$row[id]' >X</button></td>
								</tr>";
						}
					}
					
					break;
				
				case "tablaUnidadesTematicas":
					$materia = new clases\Materia($_SESSION['materia']);
					$unidadesTematicas = $materia->mostrarUnidadesTematicas('*', $ANIO, $CUATRIMESTRE);
					
					if (empty($unidadesTematicas)) {
						echo "<tr><td colspan='2'>No hay unidades cargadas</td></tr>";
					} else {
					
						foreach ($unidadesTematicas as $key => $value) {
							echo "<tr class='formularioLateral correlatividadesTable'>
									<td class='formularioLateral correlatividadesTable'>{$key}</td>
									<td class='formularioLateral correlatividadesTable'>{$value}</td>
									<td class='formularioLateral correlatividadesTable'><button type='button' class='botonEliminar' data-unidadtematica='{$key}' >X</button></td>
								</tr>";
						}
					}
					
					break;
				
					
				case "tablaControlComisiones":
					require 'conexion.php';
					
					$filtro['m.nombre'] = $_REQUEST['materia'];
					$filtro['m.cuatrimestre'] = $_REQUEST['cuatrimestre'];
					$filtro['m.carrera'] = $_REQUEST['carrera'];
					
					$tipos = array();
					$carreras = '()';
					
					$where = "1 = 1 ";
					foreach ($filtro as $key => $value) {
						if ($value != "") {
							if ($key == 'm.nombre') {
								$where .= "AND $key LIKE '%$value%' ";
							} else {
								$where .= "AND $key = '$value' ";
							}
						}
					}
					
					
					$query = "SELECT m.cuatrimestre, m.conjunto, m.nombre, m.plan, c.nombre AS carrera, 
								GROUP_CONCAT(DISTINCT CONCAT(p.apellido, ', ', p.nombres) SEPARATOR ' / ') AS responsable, 
								GROUP_CONCAT(DISTINCT CONCAT(cc.turno, ':', cc.cantidad) SEPARATOR '--') AS cantidad, 
								COUNT(DISTINCT ac.comision) AS comisiones_pobladas, 
								COUNT(DISTINCT ac.docente) AS docentes_asignados, 
								COUNT(DISTINCT a.docente) AS docentes_equipo 
							FROM materia AS m 
							LEFT JOIN carrera AS c ON m.carrera = c.id 
							LEFT JOIN cantidad_comisiones AS cc ON cc.materia = m.conjunto AND cc.anio = $ANIO AND cc.cuatrimestre = $CUATRIMESTRE
							LEFT JOIN asignacion_comisiones AS ac ON ac.materia = m.conjunto AND ac.anio = $ANIO AND ac.cuatrimestre = $CUATRIMESTRE
							LEFT JOIN materia AS om ON om.conjunto = m.conjunto
							LEFT JOIN responsable AS r ON r.materia = om.cod 
							LEFT JOIN personal AS p ON r.usuario = p.id 
							LEFT JOIN afectacion AS a ON a.materia = m.cod AND a.anio = $ANIO AND a.cuatrimestre = $CUATRIMESTRE AND a.activo = 1
							WHERE $where
							GROUP BY conjunto 
							ORDER BY m.carrera, m.cuatrimestre;";
					//echo $query;
					$result = $mysqli->query($query);
					echo $mysqli->error;
					$materias = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						$preCantidad = explode('--', $row['cantidad']);
						$preCantidad = array_map(function($item) {
							return explode(':', $item);
						}, $preCantidad);
						
						$cantidad = 0;
						//print_r($preCantidad);
						foreach ($preCantidad as $key => $value) {
							if (isset($value[1])) {
								$cantidad += $value[1];
							}
						}
						
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['cantidad'] = $cantidad;
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['comisiones_pobladas'] = $row['comisiones_pobladas'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['docentes_asignados'] = $row['docentes_asignados'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['responsable'] = $row['responsable'];
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . $row['nombre']]['docentes_equipo'] = $row['docentes_equipo'];
					}
					echo "<table class='materias'>";
						foreach ($materias AS $carrera => $materia) {
							echo "<tr class='subtitulo'>
									<th colspan='8' style='text-align:center;font-size:1.2em;'>Carrera: $carrera</th>
								</tr>";
							foreach ($materia AS $cuatrimestre => $datos) {
								echo "<tr class='subtitulo'>
									<th class='materias'>Cuatrimestre $cuatrimestre</th>
									<th class='materias'>Responsable</th>
									<th class='materias'>Comisiones</th>
									<th class='materias'>Ocupadas</th>
									<th class='materias'>Libres</th>
									<th class='materias'>Docentes</th>
									<th class='materias'>Asignados</th>
									<th class='materias'>Libres</th>
								</tr>";
								foreach ($datos AS $nombre => $turno) {
									
									echo "<tr class='info'>
											<td class='info masInfo'>$nombre</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[responsable]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[cantidad]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[comisiones_pobladas]</td>";
									echo "<td class='materia comisionesLibres' style='text-align:center;'>" . ($turno['cantidad'] - $turno['comisiones_pobladas']) ."</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[docentes_equipo]</td>";
									echo "<td class='materia' style='text-align:center;'>$turno[docentes_asignados]</td>";
									echo "<td class='materia' style='text-align:center;'>" . ($turno['docentes_equipo'] - $turno['docentes_asignados']) ."</td>";
											//<td class='info'>". implode(', ', $turno['turnos']) . "</td>";
									echo "</tr>";
								}
							}
						}
					echo "</table>";
					break;
					
				case "tablaConsultaComisiones":
					require 'conexion.php';
					
					$filtro['m.nombre'] = $_REQUEST['materia'];
					$filtro['m.cuatrimestre'] = $_REQUEST['cuatrimestre'];
					$filtro['m.carrera'] = $_REQUEST['carrera'];
					$filtro['m.plan'] = $_REQUEST['plan'];
					
					$tipos = array();
					$carreras = '()';
					
					$where = "1 = 1 ";
					foreach ($filtro as $key => $value) {
						if ($value != "") {
							if ($key == 'm.nombre') {
								$where .= "AND $key LIKE '%$value%' ";
							} else {
								$where .= "AND $key = '$value' ";
							}
						}
					}
					
					$query = "SELECT m.cuatrimestre, m.conjunto, 
								GROUP_CONCAT(DISTINCT m.nombre ORDER BY m.cod + 0
									SEPARATOR ' / ') AS nombre, m.plan, 
								c.nombre AS carrera, 
								GROUP_CONCAT(DISTINCT CONCAT(p.apellido, ', ', p.nombres) 
									SEPARATOR ' / ') AS responsable
							FROM materia AS m 
							LEFT JOIN carrera AS c ON m.carrera = c.id 
							LEFT JOIN materia AS om ON om.conjunto = m.conjunto
							LEFT JOIN responsable AS r ON r.materia = om.cod AND r.activo = 1
							LEFT JOIN personal AS p ON r.usuario = p.id 
							WHERE $where
							GROUP BY m.conjunto 
							ORDER BY FIELD (m.carrera, 6, 4, 1 , 2 , 3, 5), m.cuatrimestre";
								
					$result = $mysqli->query($query);
					echo $mysqli->error;
					$materias = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						$materias[$row['carrera']][$row['cuatrimestre']][$row['conjunto'] . " " . $row['nombre']]['responsable'] = $row['responsable'];
					}
					echo "<table class='materias'>";
						foreach ($materias AS $carrera => $materia) {
							echo "<tr class='subtitulo'>
									<th colspan='2' style='text-align:center;font-size:1.2em;'>Carrera: $carrera</th>
								</tr>";
							foreach ($materia AS $cuatrimestre => $datos) {
								echo "<tr class='subtitulo'>
									<th class='materias'>Cuatrimestre $cuatrimestre</th>
									<th class='materias'>Responsable</th>
								</tr>";
								foreach ($datos AS $nombre => $turno) {
									
									echo "<tr class='info {$carrera}'>
											<td class='info masInfo'>$nombre</td>
											<td class='materia' style='text-align:left;'>$turno[responsable]</td>
										</tr>";
								}
							}
						}
					echo "</table>";
					break;
					
				case "buscarDatosContacto":
					
					$docente = new clases\Docente($_POST['id']);
					$datosDocente = $docente->mostrarDatosContacto();
					
					if (empty($datosDocentes)) {
						$datosDocentes['vacio'] = true;
					}
					echo json_encode($datosDocente);
					break;
				
				case "agregarDatosContacto":
					$docente = new clases\Docente($_POST['dni']);
					//print_r($_POST);
					foreach ($_POST as $tipo => $valor) {
						if ($tipo != 'dni') {
							$docente->agregarDatoContacto($tipo, $valor);
						}
					}
					
					
					break;
					
				case "tablaContactosDocentes":
					require 'conexion.php';
					
					$filtro = '';
					if (isset($_GET['filtro'])) {
						$filtro = $_GET['filtro'];
					}
					
					$campos = ["d.dni", "dc1.valor", "dc2.valor", 
						"d.apellido", "d.nombres", 
						"CONCAT_WS(', ', d.apellido, d.nombres)"];
					
					$where = "1 = 0 ";
					if ($filtro != '') {
						foreach ($campos as $campo) {
							$where .= " OR $campo LIKE '%$filtro%' ";
						}
					}
					
					
					$query = "SELECT d.id, CONCAT_WS(', ', d.apellido, 
							d.nombres) AS docente, dc1.valor AS telefono, 
							dc2.valor AS mail
						FROM docente AS d
						LEFT JOIN datos_docentes AS dc1 ON dc1.docente = d.id
							AND dc1.tipo = 'telefono'
						LEFT JOIN datos_docentes AS dc2 ON dc2.docente = d.id
							AND dc2.tipo = 'mail'
						WHERE $where
						ORDER BY d.apellido, d.nombres
						LIMIT 25;";
					//echo $query;
					$result = $mysqli->query($query);
					echo $mysqli->error;
					$docentes = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						$docentes[] = $row;
					}
					
					if (sizeof($docentes)) {
						
						echo "<table class='docentes' style='width:98%;'>";
						echo "<tr class='subtitulo'>
								<th class='subtitulo'style='width:40%;'>Docente</th>
								<th class='subtitulo' style='width:20%;'>Telefono</th>
								<th class='subtitulo'style='width:40%;'>Mail</th>
							</tr>";
						foreach ($docentes AS $valores) {
							echo "<tr class='info'>
									<td class='info masInfo' data-id='$valores[id]'>$valores[docente]</td>";
							echo "<td class='materia' style='text-align:left;'>$valores[telefono]</td>";
							echo "<td class='materia' style='text-align:left;'><a class='mail' href='mailto:$valores[mail]'>$valores[mail]</a></td>";
							echo "</tr>";
						}
						echo "</table>";
					} else {
						echo "<p>No se encontraron docentes</p>";
					}
					break;
					
				case "tablaDocentesAsignados":
					require 'conexion.php';
					
					$filtro = '';
					if (isset($_GET['filtro'])) {
						$filtro = $_GET['filtro'];
					}
					
					$campos = ["m.nombre", "m.cod",
						"d.apellido", "d.nombres", 
						"CONCAT_WS(', ', d.apellido, d.nombres)"];
					
					$where = " (ac.anio = $ANIO AND ac.cuatrimestre = $CUATRIMESTRE) AND 1 = 0 ";
					if ($filtro != '') {
						foreach ($campos as $campo) {
							$where .= " OR $campo LIKE '%$filtro%' ";
						}
					}
					
					
					$query = "SELECT ac.docente AS id, ac.materia, 
								GROUP_CONCAT(DISTINCT m.nombre ORDER BY m.cod SEPARATOR '/ ') AS nombre,
								CONCAT_WS(', ', d.apellido, d.nombres) AS docente,
								GROUP_CONCAT(DISTINCT ac.comision ORDER BY ac.comision SEPARATOR ', ') AS comisiones
							FROM asignacion_comisiones as ac
							LEFT JOIN materia AS m ON m.conjunto = ac.materia
							LEFT JOIN docente AS d ON d.id = ac.docente
							WHERE $where
								
							GROUP BY ac.materia, ac.docente
							ORDER BY ac.materia
							LIMIT 50";
					//echo $query;
					$result = $mysqli->query($query);
					echo $mysqli->error;
					$docentes = array();
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
						$docentes[] = $row;
					}
					
					if (sizeof($docentes)) {
						
						echo "<table class='docentes' style='width:98%;'>";
						echo "<tr class='subtitulo'>
								<th class='subtitulo'style='width:40%;'>Docente</th>
								<th class='subtitulo' style='width:40%;'>Materia</th>
								<th class='subtitulo'style='width:40%;'>Comisiones</th>
							</tr>";
						foreach ($docentes AS $valores) {
							echo "<tr class='info'>
									<td class='info masInfo' data-id='$valores[id]'>$valores[docente]</td>";
							echo "<td class='materia' style='text-align:left;'>$valores[materia] $valores[nombre]</td>";
							echo "<td class='materia' style='text-align:left;'>$valores[comisiones]</td>";
							echo "</tr>";
						}
						echo "</table>";
					} else {
						echo "<p>No se encontraron docentes</p>";
					}
					break;
					
				default:
					echo "No se realizó la búsqueda";
					
			}
		
	}
	
	
?>

<?php
	echo header('Content-Type: application/json');
//Consultas vía AJAX
	//Autoload de la clase.
	session_start();
	function __autoload($class) {
		$classPathAndFileName = "../clases/" . $class . ".class.php";
		require_once($classPathAndFileName);
	}
	require './constantes.php';
	require './conexion.php';

	
	if (isset($_GET['act'])) {
		
			switch($_GET['act']) {
				
				case "mostrarAulas":
					$query = "SELECT id, cod, capacidad, IF(ISNULL(mas_info), '', mas_info) AS mas_info, IF(abierta = 1, 'SÍ', 'NO') AS abierta
								FROM aulas
								WHERE activo = 1;";
					$result = $mysqli->query($query);

				$aulas = array();
					if (isset($mysqli->error_no)) {
						$aulas['error'] = $mysqli->error;
					}
					
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						$aulas[] = $row;
					}
					
					echo json_encode($aulas);
					
					break;
					
				default:
					echo "No se realizó la búsqueda";
					
			}
		
	}
	
	$mysqli->close();
	
?>

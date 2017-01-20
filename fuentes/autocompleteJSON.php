<?php
header('Content-type: application/json');

if (isset($_GET['act'])) {
	switch($_GET['act']) {
		case "materias":
			require "./conexion.php";

				$term = trim(strip_tags($_GET['term']));

				$query = "SELECT cod as value, CONCAT_WS(' - ', cod, nombre) as label FROM materia WHERE activo = 1 and CONCAT_WS(' - ', cod, nombre) like '%$term%' ORDER BY cod";
				$result = $mysqli->query($query);

				$materias = [];
				while ($row = $result->fetch_array(MYSQL_ASSOC)) {
					$materias[] = $row;
				}

				echo json_encode($materias);

				$result->free();
				$mysqli->close();
		break;
		
		default:
	}
}

?>

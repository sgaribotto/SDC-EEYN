<?php
		// The function header by sending raw excel
	header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	 
	// Defines the name of the export file "codelution-export.xls"
	header("Content-Disposition: attachment; filename=estado_de_carga_comisiones.xlsx");
	
	require "../fuentes/conexion.php";
	
	$query = "SELECT cc.materia, GROUP_CONCAT(DISTINCT m.nombre SEPARATOR ' / ') AS nombre_materia,
					cc.turno, cc.dependencia, cc.cantidad AS total_comisiones_turno, IFNULL(aas.comision, cc.turno) AS comision,  
					GROUP_CONCAT(DISTINCT CONCAT_WS(', ', d.apellido, d.nombres) SEPARATOR ' / ') AS nombre_docente
				FROM cantidad_comisiones AS cc
				LEFT JOIN materia as m ON cc.materia = m.conjunto
				LEFT JOIN asignacion_comisiones AS aas
					ON aas.anio = cc. anio AND aas.cuatrimestre = aas.cuatrimestre
						AND aas.materia = cc.materia AND aas.turno = cc.turno
				LEFT JOIN docente AS d ON aas.docente = d.id
				WHERE cc.anio = 2016 AND cc.cuatrimestre = 1
				GROUP BY cc.materia, cc.turno, cc.dependencia, aas.comision
				ORDER BY m.conjunto";
	$result = $mysqli->query($query);
	

?>
	<table>
		<tr>
			<th>Materia</th>
			<th>Nombre</th>
			<th>turno</th>
			<th>Dependencia</th>
			<th>comisiones del turno</th>
			<th>comision</th>
			<th>docentes</th>
		</tr>
		<?php
			while ($row = $result->fetch_array(MYSQL_ASSOC)) {
				echo "<tr>";
				foreach ($row as $value) {
					echo "<td>$value</td>";
				}
				echo "</tr>";
			}
		?>
	</table>

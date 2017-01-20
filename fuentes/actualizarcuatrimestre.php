<?php
	ACTUALIZAR LA BASE DE PROGRAMAS EN UN AÑO
	COMENTAR TODO ESTO PARA ARRANCAR
	HACER SIEMPRE EL BACKUP DE LA BASE ANTES DE EJECUTAR
	HAY QUE CAMBIAR MANUALMENTE LAS CONSTANTES
	
	
	
	
	$tablas = array(
		'afectacion' => true,
		'agregados_cronograma' => true, 
		'bibliografia' => true,
		'cronograma' => true, 
		'programa' => true, 
		'unidad_tematica' => true
	);
	
	/**
	 * Actualiza para el cuatrimestre siguiente
	 * @param (array) tablas para actualizar seteada en true
	 * @param (int) anio Actual
	 * @param (int) cuatrimestre actual
	 */
	function actualizarCuatrimestre($tablas = array(), $anio, $cuatrimestre) { 
		
		
		$queries = array();
		$cuatrimestreDestino = ($cuatrimestre % 2) + 1;
		$anioDestino = $cuatrimestre == 1 ? $anio : $anio + 1;
		
		$queries['afectacion'] = "INSERT INTO afectacion
			SELECT null, docente, materia, {$anioDestino}, 
				{$cuatrimestreDestino}, 1, tipoafectacion, 'pendiente' 
			FROM afectacion
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre} 
				AND activo = 1;";
				
		$queries['agregados_cronograma'] = "INSERT INTO agregados_cronograma
			SELECT null, tipo, valor, 1, materia, clase, 
				{$anioDestino}, {$cuatrimestreDestino}
			FROM agregados_cronograma
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre} 
				AND activo = 1;";
				
		$queries['bibliografia'] = "INSERT INTO bibliografia
			SELECT null, materia, titulo, autor, editorial, paginas,
			 1, {$anioDestino}, {$cuatrimestreDestino}
			FROM bibliografia
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre} 
				AND activo = 1;";
				
		$queries['cronograma'] = "INSERT INTO cronograma
			SELECT null, materia, clase, fecha, unidadtematica, descripcion,
				metodo, bibliografia, paginas, 1, docente,
				{$anioDestino}, {$cuatrimestreDestino}
			FROM cronograma
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre}";
				
		$queries['programa'] = "INSERT INTO programa
			SELECT null, materia, usuario, campo, valor, fecha, 
				{$anioDestino}, {$cuatrimestreDestino}
			FROM programa
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre};";
			
		$queries['unidad_tematica'] = "INSERT INTO unidad_tematica
			SELECT null, materia, unidad, descripcion, fecha, 1, 
				{$anioDestino}, {$cuatrimestreDestino}
			FROM unidad_tematica
			WHERE anio = {$anio} AND cuatrimestre = {$cuatrimestre} 
				AND activo = 1;";
				
		$host = "10.1.71.121";
		$usuario = "programas";
		$clave = "TMtrj9rS5di";
		$db = "programas";
		
		$mysqli = new MySQLi($host, $usuario, $clave, $db);
				
				
		foreach ($tablas as $tabla => $actualizar) {
			if ($actualizar) {
				$mysqli->query($queries[$tabla]);
				if ($mysqli->errno) {
					echo "ERROR MYSQL: " . $mysqli->error;
				} else {
					echo "Afectadas: " . $mysqli->affected_rows;
					echo "<br />";
					echo "WARNINGS: " . $mysqli->warning_count;
				}
				echo "<hr />";
			}
		}
		$mysqli->close();
	}
	
	actualizarCuatrimestre($tablas, 2016, 1);
?>

<?php
namespace clases {
	
	/**
	 * Clase de programas
	 * 
	 * @author Santiago
	 * @version 1.1
	 */
	class Programa {
		//PROPIEDES
		
		protected $materia;
		
		protected $conjunto;
		
		protected $usuario;
		
		protected $programaCampoValor = array();
		
		protected $activo;
		
		
		//MÉTODOS
		
		//constructor
		public function __construct($materia, $usuario) {
			//Conecto a la base de datos
			require('./conexion.php');
			
			//Escribo la materia y el usuario
			$this->materia = $materia;
			$this->usuario = $usuario;
			
			$query = "SELECT conjunto FROM materia WHERE cod = {$materia}";
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQL_ASSOC);
			$this->conjunto = $row['conjunto'];
			
			$result->free();
			
			
			//Consulto los campos y valores en la BD
			$query = "SELECT id, campo, valor FROM programa WHERE usuario = '$usuario' AND materia = '$materia' ";
			$result = $mysqli->query($query);
			
			//Armar el array con los campos y guardarlo en el array campos (Asociativo)
			while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
				foreach ($row as $key => $value) {
					$this->ProgramaCampoValor[$key] = $value;
				}
			}
			
			//Cierro la conexión a la base de datos
			$result->free();
			$mysqli->close();
			
			return true;
		}
		
		//escribir campo
		public function ingresarCampo($CampoValor, $anio = 2016, $cuatrimestre = 1) { //Campo valor es un array con nombre del campo y valor
			//Consexión a la BD
			require('./conexion.php');
			
			//Consulta de inserción para bind_param()
			$query = "REPLACE INTO  programa SET id=NULL, usuario = '{$this->usuario}', materia = '{$this->materia}', anio = $anio, cuatrimestre = $cuatrimestre, campo = ?, valor = ?";
			echo $query;
			//Creo y preparo el statement con el bind para campo (varchar) y valor (text)
			$stmt = $mysqli->stmt_init();
			$stmt->prepare($query);
			$stmt->bind_param('ss', $campo, $valor);
			
			//Escribir campo y valor en la base de datos y 
			foreach ($CampoValor as $campo => $valor) {
				
				$stmt->execute();
			}
			
			//Cierro el statement y la conexión a la base
			$stmt->close();
			$mysqli->close();
			
			return true;
		}
		
		//get
		public function mostrarCampo($anio, $cuatrimestre) { //Hay que ingresar todos los campos ADICIONALES que se quieren averiguar. Por defecto trae campo y valor
			// Conexión a la BD
			require('./conexion.php');
			
			//Tomo los argumentos de la función que son mayores que 1
			$cantidadDeArgumentos = func_num_args();
			$campos = "campo, valor";
			
			for ($i = 2; $i < $cantidadDeArgumentos; $i++) {
				$campos .= ", " . func_get_arg($i);
			}
			
			
			//Consulta Select usando los campos del array
			$query = "SELECT $campos FROM programa WHERE materia IN {$this->conjunto} AND anio = $anio AND cuatrimestre = $cuatrimestre ";
			$result = $mysqli->query($query);
			
					
			//Devolver un array asociativo campo valor
			$campoValor = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
			
					$campoValor[$row['campo']] = $row['valor'];
				
			}
			
			
			//Liberar los resultados y cerrar la base de datos
			$result->free();
			$mysqli->close();
			
			return $campoValor;
			
		}
	}
}
		

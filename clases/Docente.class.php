<?php
namespace clases {

	/**
	 * Clase Docente
	 * 
	 * @author Santiago
	 * @version 1.1
	 */
	class Docente {
		
		//PROPIEDADES
		//datos personales
		public $dni;
		public $id;
		public $datosDocente = array();
		
		//afectaciones
		public $afectacion = array();
		
		//designaciones
		public $designacion = array();
		
		//mensajes
		public $errorNoEncuentro = "El docente buscado no está en la tabla";
		
		/**
		 * Datos de contacto
		 * @readwrite
		 */
		protected $datosContacto = array();
		
		//MÉTODOS
		
		public function __construct($cod) {
			
			require('./conexion.php');
			
			//busc por id
			$query = "SELECT id FROM docente WHERE id = $cod OR dni = $cod";
			$result = $mysqli->query($query);
			
			if ($result->num_rows == 1) {
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$this->id = $row['id'];
				$this->cargarMasDatosDocente();
				
			} else {
				echo $this->errorNoEncuentro;
			}
			
			$result->free();
			$mysqli->close();
			
		}
		
		/**
		 * Carga todos los datos de un docente en datosDocente
		 * @return (void)
		 */		
		public function cargarMasDatosDocente() {
			require("./conexion.php");
			
			$query = "SELECT * FROM docente WHERE id = {$this->id} ";
			
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			foreach ($row as $key => $value) {
				$this->datosDocente[$key] = $value;
			}
			
			$result->free();
			$mysqli->close();
		}
		
		/**
		 * Devuelve los datos del docente
		 * @return (array) dato => valor
		 */
		public function mostrarDatosDocente() {
			if (empty($this->datosDocente)) {
				$this->cargarMasDatosDocente();
			}
			return $this->datosDocente;
		}
		
		/**
		 * Agrega una afectación a un docente incluyéndolo en el equipo
		 * docente de la materia
		 * @param (str) cod de materia
		 * @param (str) tipo de afectacion (adjunto, titular, etc.)
		 * @param (int) anio
		 * @param (int) cuatrimestre
		 * @return (str) error
		 */		
		public function agregarAfectacion($materia, $tipo, $anio, $cuatrimestre) {
			require("./conexion.php");
			
			
			$query = "REPLACE INTO afectacion (docente, materia, anio, cuatrimestre, tipoafectacion, estado)
							VALUES ('$this->id', '$materia', '$anio', '$cuatrimestre', '$tipo', 'Pendiente')";
			
			$error = 'success';
			if (!$mysqli->query($query)) {
				$error = $mysqli->error;
			}
			return $error;
		}
		
		/**
		 * Muestra las afectaciones del docente
		 * @param (int) anio
		 * @param (int) cuatrimestre
		 * @return (array) afectaciones
		 */
		public function mostrarAfectaciones($anio, $cuatrimestre) {
			require 'conexion.php';
			
			$query = "SELECT a.id, m.conjunto, a.tipoafectacion, 
					GROUP_CONCAT(DISTINCT m.nombre ORDER BY m.cod SEPARATOR '/')
						AS materia, 
					IFNULL(GROUP_CONCAT(DISTINCT ac.comision ORDER BY ac.comision SEPARATOR ' - '), 
						'No tiene comisiones asignadas') AS comision
				FROM afectacion AS a
				LEFT JOIN materia AS m ON m.cod = a.materia
				LEFT JOIN asignacion_comisiones AS ac 
					ON ac.materia = m.conjunto AND ac.docente = {$this->id}
						AND ac.anio = $anio AND ac.cuatrimestre = $cuatrimestre
				WHERE a.docente = {$this->id}
					AND a.anio = $anio 
					AND a.cuatrimestre = $cuatrimestre
				GROUP BY m.conjunto
				ORDER BY m.cod";
			$result = $mysqli->query($query);
			if ($mysqli->error) {
				echo $mysqli->error;
			}
			
			$afectaciones = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$afectaciones[] = $row;
			}
			
			$result->free();
			$mysqli->close();
			
			return $afectaciones;
		}
		
		/**
		 * Obtiene los datos de contacto
		 * @return (array) tipo => dato
		 */
		 public function mostrarDatosContacto() {
			 require 'conexion.php';
			 
			 $query = "SELECT id, docente, tipo, valor
				FROM datos_docentes
				WHERE activo = 1 AND docente = {$this->id}";
			 $result = $mysqli->query($query);
			 $error = $mysqli->error;
			 echo $error;
			 
			 $datos = array();
			 while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				 $datos[$row['tipo']] = $row;
			 }
			 
			 $result->free();
			 $mysqli->close();
			 return $datos;
		 }
		 
		/**
		 * Agrega datos de contacto en la base de datos
		 * @param (array) Los datos tipo => valor
		 * @return void
		 */
		 public function agregarDatosContactos($datos) {
			 require 'conexion.php';
			 foreach ($datos as $tipo => $valor) {
				 $query = "REPLACE INTO datos_docentes 
					(docente, tipo, valor) VALUES
					({$this->id}, '$tipo', '$valor')";
				$mysqli->query($query);
					
			 }
			 $mysqli->close();
		 }
		 
		 /**
		  * Agrega un dato de contacto en la base de datos
		  * @param (str) tipo
		  * @param (str) valor
		  * @return void
		  */
		  public function agregarDatoContacto($tipo, $valor) {
		     require 'conexion.php';
			 $query = "REPLACE INTO datos_docentes 
				(docente, tipo, valor) VALUES
				({$this->id}, '$tipo', '$valor')";
			 $mysqli->query($query);
					
			 $mysqli->close();
		  }
		  
		  /**
		   * Muestra las designaciones del docente
		   * @param (int) anio
		   * @param (int) cuatrimestre
		   * @return (array) designaciones
		   */
		  public function mostrarDesignaciones($anio, $cuatrimestre) {
			  require 'conexion.php';
			  
			  $query = "SELECT id, tipo, categoria, caracter, dedicacion
						FROM designacion
						WHERE anio = $anio AND cuatrimestre = $cuatrimestre
							AND docente = {$this->id};";
			  $result = $mysqli->query($query);
			  if ($mysqli->errno) {
				  echo $mysql->error;
			  }
			  
			  $designaciones = array();
			  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				  $designaciones[] = $row;
			  }
			  $result->free();
			  $mysqli->close();
			  return $designaciones;
		  }
			  
	}		
}

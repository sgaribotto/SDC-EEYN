<?php
namespace clases {
	/** 
	 * CLASE Validar_log_in_mysql
	 *  
	 *	Valida el ingreso de un usuario a través de un formulario personalizado y una base de datos Mysql
	 * 
	 * @author Santiago
	 * @version 1.1
	 */
	
	class Validar_log_in_mysql {
		//PROPIEDADES
		
		public $usuario;
		public $password;
		public $loginPage = "./portada.php";
		
		//host
		/*public $host = "localhost";
		//base de datos
		public $db = "programas";
		//usuario bd
		public $dbuser = "root";
		//pswd bd
		public $dbuserpsswd = "";
		//mensaje de error en la bd
		public $dbErrorMsg = "No se ha logrado la conexión con la base de datos";*/
		public $pathConexion = '../fuentes/conexion.php';
		
				
		public $tabla = "personal";
		//columna usuario
		public $colUsuario = "usuario";
		//columna psswd
		public $colPassword = "password";
		//mensaje de error de usuario o contraseña
		public $msjCancel = "Usuario o contraseña incorrectos";
		
		//otros datos del usuario
		public $datosUsuario = array();
		
		//Método de encriptación
		public $metodoEncriptacion = "md5";
		
		public $conexion;
		
		//Redirigir a:
		public $redirigirA = "./seleccionarmateria.php";
		
		
		
		
		
		//MÉTODOS
		
		// construir con conexión a la base de datos
		public function __construct($pathConexion = './fuentes/conexion.php', $tabla = "personal", $colUsuario = "usuario", $colPassword = "password", $metodoEncriptacion = "md5") {
			
			
			$this->pathConexion = $pathConexion;
			$this->tabla = $tabla;
			$this->colUsuario = $colUsuario;
			$this->colPassword = $colPassword;
			$this->metodoEncriptacion = $metodoEncriptacion;
			
			require $this->pathConexion;
			$this->conexion = $mysqli;
		}
		
		// mostrar mensaje de error
		public function redirigirConError($tipoError) {
			header("location:{$this->loginPage}?Error=" . $this->{$tipoError});
		}
		
		// validar contra la tabla
		public function validarIngreso($usuario, $password) {
			
			$this->usuario = $this->conexion->real_escape_string($usuario);
			
			$this->password = $this->conexion->real_escape_string($password);
			
			$this->encriptarPassword();
			
			$query = "SELECT * FROM {$this->tabla} WHERE {$this->colUsuario} = '$this->usuario' AND {$this->colPassword} = '$this->password'";
			
			$result = $this->conexion->query($query);
			
			if ($result->num_rows != 1) {
				
				$this->redirigirConError("msjCancel");
			} else {
				
				$row = $result->fetch_array(MYSQL_ASSOC);
				foreach ($row as $key => $value) {
					$this->datosUsuario[$key] = $value;
					
				}
				
				$this->iniciarSesion();
				Header("location:$this->redirigirA");
				
			}
			
		}
		
		// agregar un usuario
		//Encriptar la password
		public function encriptarPassword() {
			
			switch ($this->metodoEncriptacion) {
				case "md5":
					$this->password = md5($this->password);
					break;
					
				case "SHA1":
					$this->password = sha1($this->password);
					break;
					
				default:
					echo "no se encriptó<br />";
					
			}
		}
		//Iniciar SESSSION
		public function iniciarSesion() {
			session_start();
			$_SESSION['usuario'] = $this->usuario;
			$_SESSION['id'] = $this->datosUsuario['id'];
			
			$_SESSION['permiso'] = $this->verPermisos();
			
			
		}
		
		//Cambiar Contraseá
		public function cambiarClave($claveNueva) {
			//sanear el ingreso
			$this->validarPassword($claveNueva);
			$this->password = $this->conexion->real_escape_string($claveNueva);
			
			//encriptar
			$this->encriptarPassword();
			//escribir en la base de datos.
			
			$query = "UPDATE personal SET password = '{$this->password}' WHERE id = '$_SESSION[id]' ";
			$this->conexion->query($query);
			
		}
		
		//Validar la password nueva
		public function validarPassword($password) {
			
			$pattern = '/[A-Za-z0-9]{4,}/';
			if (!preg_match($pattern, $password)) {
				exit('La password ingresada no cumple los requisitos de seguridad.');
			}
		}
		
		public function verPermisos() {
			
			$query = "SELECT id, usuario, tipo_de_permiso 
						FROM permiso
						WHERE usuario = {$this->datosUsuario['id']} ";
			echo $query;
			$result = $this->conexion->query($query);
			
			$permisos = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$permisos[] = $row['tipo_de_permiso'];
			}
			
			return $permisos;
		}
		
	}
	
	
	//TESTS
	
	/*$validar = new Validar_log_in_mysql();
	
	$validar->redirigirA = "";
	$validar->validarIngreso("sgaribotto", "gari");
	
	
	print_r( $_SESSION);*/
}

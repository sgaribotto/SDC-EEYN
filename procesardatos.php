<?php
	session_start();
	/*var_dump($_REQUEST);
	var_dump($_POST);
	var_dump($_SESSION);*/
	
	//Autoload de la clase.
	require 'programas.autoloader.php';
	require "./fuentes/constantes.php";
	
	if (isset($_GET['formulario'])) {
		switch ($_GET['formulario']) {
			
			case "seleccionarMateria":
				$materia = explode(' - ',$_POST['materia']);
				$_SESSION['materia'] = $materia[0];
				$_SESSION['nombreMateria'] = $materia[1];
				//header('location:asignarcomisiones.php');
				header('location:datosgenerales.php');
				break;
				
			case "contenidos":
				header('location:equipodocente.php');
				break;
			
			case "equipoDocente":
				header('location:fundamentacion.php');
				break;
				
			case "datosGenerales":
				header('location:contenidos.php');
				break;
			
			case "fundamentacion":
				$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
				$programa->ingresarCampo(["fundamentacion" => $_POST['fundamentacion']], $ANIO, $CUATRIMESTRE);
				header('location:unidadestematicas.php');
				break;
				
			case "objetivos":
				
				$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
				$programa->ingresarCampo(["objetivos" => $_POST['objetivos']], $ANIO, $CUATRIMESTRE);
				header('location:fundamentacion.php');
				break;
				
			case "unidadestematicas":
				header('location:evaluacion.php');
				break;
				
			case "evaluacion":
				$programa = new clases\Programa($_SESSION['materia'], $_SESSION['id']);
				$programa->ingresarCampo(["evaluacion" => $_POST['evaluacion']], $ANIO, $CUATRIMESTRE);
				header('location:bibliografia.php');
				break;
			
			case "bibliografia":
				header('location:portada.php');
				break;
				
			case "cambiarClave":
				$validar = new clases\Validar_log_in_mysql();
				$validar->cambiarClave($_POST['clavenueva']);
				header('location:cerrarsesion.php');
				break;
		}
	}
?>

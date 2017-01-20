<meta charset="utf-8">
<?php
	//COmentar para ejecutar
	//phpinfo();
	require '../libs/PHPMailer/PHPMailerAutoload.php';
	

	function mailAvisoMasCampus($docente, $direccion, $asunto, $mensaje) {
		
		
		try {
			$mail = new PHPMailer;
			$mail->CharSet = 'utf-8';
			//$mail-­>Encoding = "quoted­printable"; 
			$mail->Mailer = 'SMTP';
			$mail->SMTPDebug = 0;
			$mail->Host = 'smtp.unsam.edu.ar';
			
			$mail->Username = "planes.eeyn@unsam.edu.ar";
			$mail->Password = "Pl787238";
			$mail->SMTPSecure = "ssl";
			$mail->Port = '465';
			$mail->SMTPAuth = true;
			$mail->AddReplyTo('webmaster.eeyn@unsam.edu.ar', 'Planes EEYN');
			$mail->setFrom('planes.eeyn@unsam.edu.ar', 'Planes EEYN');
			$mail->isHTML(true);
			
			$mail->AddAttachment('/var/www/CV - Santiago Lazzati - General 2016.doc');
			
			$mail->addAddress($direccion, $docente);
			$mail->Subject = $asunto;
			$mail->Body = $mensaje;
			
			//print_r($mail);
			//print_r($mail->send());
			if (!$mail->send()) {
				echo 'Mail not sent';
				echo "error: " . $mail->ErrorInfo;
			} else {
				echo "Message Sent <br>";
				
				//echo "error: " . $mail->ErrorInfo;
			}
		
		} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer

		} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
		}
	
	}
	
	
	
	//require 'conexion.php';
	
	$host = "10.1.71.121";
	$usuario = "programas";
	$clave = "TMtrj9rS5di";
	$db = "programas";
	
	$mysqli = new MySQLi($host, $usuario, $clave, $db);
	
	
	//CONSULTA DOCENTES NO RESPONSABLES DE MATERIA
	$query = "SELECT DISTINCT CONCAT_WS(', ', d.apellido, d.nombres) AS nombre_docente,
					d.id AS docente, dd.valor AS mail
				FROM asignacion_comisiones AS ac
				LEFT JOIN docente AS d ON d.id = ac.docente
				LEFT JOIN personal AS p ON p.dni = d.dni
				LEFT JOIN responsable AS r ON r.usuario = p.id
				LEFT JOIN datos_docentes AS dd ON dd.docente = d.id AND tipo = 'mail'
				WHERE ISNull(r.id) AND NOT isNULL(dd.valor);";
	
	//CONSULTA PARA LOS RESPONSABLES ADMIN, ECO y CPU
	/*$query = "SELECT DISTINCT d.id AS docente, 
		CONCAT(p.apellido, ', ', p.nombres) AS nombre_docente,
		dd.valor AS mail
   
		FROM responsable AS r
		LEFT JOIN personal AS p ON p.id = r.usuario
		LEFT JOIN materia AS m ON m.cod = r.materia
		LEFT JOIN docente AS d ON d.dni = p.dni
		LEFT JOIN datos_docentes AS dd ON dd.docente = d.id AND dd.tipo = 'mail'
		WHERE r.activo = 1 AND m.carrera IN (1, 2, 4, 5, 6)";*/
	
	//CONSULTA DOCENTES CON MATERIA DE PERTENENCIA Y COMISIONES
	/*$query = "SELECT CONCAT_WS(', ', d.apellido, d.nombres) AS nombre_docente, ac.materia, dd.valor AS mail, ac.docente,
				GROUP_CONCAT(DISTINCT m.nombre ORDER BY m.cod SEPARATOR ' | ') AS nombre_materia, 
				GROUP_CONCAT(DISTINCT ac.comision ORDER BY ac.comision SEPARATOR ' | ') AS comision
			FROM asignacion_comisiones AS ac
			LEFT JOIN materia AS m ON m.conjunto = ac.materia
			LEFT JOIN docente AS d ON d.id = ac.docente
			LEFT JOIN datos_docentes AS dd ON dd.docente = d.id AND dd.tipo = 'mail'
			WHERE ac.anio = 2016 AND ac.cuatrimestre = 1
			GROUP BY ac.materia, ac.docente
			HAVING NOT ISNULL(comision) AND comision != ''
			ORDER BY docente
			LIMIT 1000;";*/
	
	//TEMPLATE Y ASUNTO
	$template = "<p 'style=text-align:justify;>Estimado Docente <b>%s</b>
	<br />
	<br />
	Es de nuestro agrado invitarlo/a a participar del <b>taller de “Diseño Didáctico” que será llevado a cabo por el Cdor. Santiago Lazzati</b>; Director de la carrera de Administración y Gestión Empresarial de la EEyN (se adjunta su CV). <b>El mismo está dirigido a TODOS los docentes de la Escuela</b>.
<br />
<br />
	Esta es la cuarta edición de éste taller que se vuelve a ofrecer debido a la excelente evaluación que ha obtenido de nuestros colegas. La instrucción del Decano y del Secretario Académico es que cada uno de ustedes participe del plan de capacitación docente.
<br />
<br />
	Los objetivos principales del taller son los siguientes:
<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ø Desarrollar conceptos y herramientas fundamentales que favorecen el diseño didáctico y, en consecuencia, la eficacia y eficiencia de las actividades educativas. En este orden, promover la aplicación de métodos de enseñanza – aprendizaje que implican la participación activa de los alumnos.
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ø Desarrollar habilidades, o sea la capacidad de aplicar dichos conceptos y herramientas a la situación específica del participante como docente.
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ø Aplicar efectivamente el aprendizaje en las actividades educativas reales del docente.
	<br /> 
	<br />
	Se empleará  una metodología altamente participativa, en línea con los propios métodos de aprendizaje propuestos en el temario. Las tareas que se desarrollarán durante el taller cumplen un doble propósito:
<br />

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ø Aprendizaje del tema respectivo.
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ø Demostración de los distintos tipos de aplicaciones que comprenden el diseño didáctico.
<br />
<br />
	El taller se realizará a lo largo de tres encuentros, los días <b>martes 16, 23 y 30 de agosto de 18 a 21 hs., en la sala auditorio Horacio Val de la EEyN</b>. Se entregarán certificados de asistencia.
<br />
<br />
	La inscripción se realiza mediante correo electrónico a <a href='mailto:diseeyn@unsam.edu.ar'>diseeyn@unsam.edu.ar</a>  <b>hasta el 9 de agosto</b>. Se deberá enviar <i>nombre y apellido, documento de identidad, materia y cargo</i>. En esta misma dirección de correo podrán hacer las consultas que consideren pertinentes.
<br />
<br />
	Esperando contar con su presencia, los saluda cordialmente
<br />
<br />
<br />
	 
	 
	 
	Lic. Clara López<br />
	Secretaría Académica<br />
	EEyN-UNSAM<br />
	Caseros 2241 - San Martín - Buenos Aires<br />
	Tel: 4580-7250 int. 139<br />
	clalopez@unsam.edu.ar</p>";
	
	$asunto = "Invitación Taller Diseño";
	
	// ARMADO DE LA BASE DE DATOS PARA EL ENVÏO DE MAILS
	
	/*$result = $mysqli->query($query);
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		//$materia = $row['nombre_materia'];
		$message = sprintf($template, $row['nombre_docente']);
		
		
		
		$docente = $row['docente'];
		$tipo = 'responsable';
		$nombre_docente = $mysqli->real_escape_string($row['nombre_docente']);
		$mail = $mysqli->real_escape_string($row['mail']);
		$asunto = $mysqli->real_escape_string($asunto);
		$message = $mysqli->real_escape_string($message);
		
		$insertQuery = "INSERT INTO envios_por_mail 
			(id_destinatario, tipo_destinatario, destinatario, mail, asunto, mensaje) VALUES
			($docente, '$tipo', '$nombre_docente', '$mail', '$asunto', '$message');";
		$mysqli->query($insertQuery);
		echo $mysqli->error;
		echo "$row[docente] --> $row[mail]";
		
		echo "<hr />";
		
		
		
		
	}*/
	
	//TEST MAIL A planes.eeyn@unsam.edu.ar
	/*$mensaje = sprintf($template, 'Santiago Garibotto');
	mailAvisoMasCampus('Santiago Garibotto', 'planes.eeyn@unsam.edu.ar', $asunto, $mensaje);*/
	
	
	//ENVIO DE MAILS GUARDADOS EN LA BASE
	$query = "SELECT id, id_destinatario, tipo_destinatario, destinatario, mail, asunto, mensaje 
			FROM envios_por_mail
			WHERE NOT ISNULL(mail) AND mail != '' AND enviado < 1
			LIMIT 30;";
	
	$result = $mysqli->query($query);
	echo $mysqli->error;
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		mailAvisoMasCampus($row['destinatario'], $row['mail'], $row['asunto'], $row['mensaje']);
		echo $row['mail'];
		echo $row['mensaje'] . "<hr>";
		$updateQuery = "UPDATE envios_por_mail 
							SET enviado = 1 
							WHERE id = $row[id];";
		$mysqli->query($updateQuery);
	}
	
	$result->free();
	$mysqli->close();
	
	
	
	
?>

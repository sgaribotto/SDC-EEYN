<meta charset="utf-8">
<?php
	COmentar para ejecutar
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
			LIMIT 1000;";
	$result = $mysqli->query($query);
	
	$template = "Estimado/a <b>%s:</b>\n
		<br />
		Ya está disponible su aula virtual en <a href='http://www.mascampus.unsamdigital.edu.ar/'>+Campus</a>. Los 
		datos de acceso son los mismos que utiliza en el SIU.\n
		<br />
		\t<b>Materia:</b> %s
		<br />
		\t<b>Comisión:</b> %s
		<br />
		A la brevedad le informaremos acerca de las capacitaciones sobre
		el uso de +Campus.\n
		<br />
		Ante cualquier inconveniente, solicite asistencia en  
		<a href='http://ticket.unsam.edu.ar/'>http://ticket.unsam.edu.ar/</a>.\n
		<br />
		<br />
		Aprovechamos la ocasión para saludarlo atentamente.\n
		<br />
		<br />
		Secretaría Académica\n
		<br />
		EEYN - UNSAM";
	
	$asunto = "Más Campus";	
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$materia = $row['nombre_materia'];
		$message = sprintf($template, $row['nombre_docente'], $materia, 
			$row['comision']);
		
		
		
		$docente = $row['docente'];
		$tipo = 'docente';
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

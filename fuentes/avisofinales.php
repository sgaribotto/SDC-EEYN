<meta charset="utf-8">
<?php
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
	
	/*$query = "SELECT CONCAT_WS(', ' , p.apellido, p.nombres) AS responsable,
				d.id AS docente, dd.valor AS mail
				FROM responsable AS r
				LEFT JOIN personal AS p ON p.id = r.usuario
				LEFT JOIN materia AS m on m.cod = r.materia
				LEFT JOIN docente AS d on d.dni = p.dni
				LEFT JOIN datos_docentes AS dd ON d.id = dd.docente AND dd.tipo = 'mail'
				WHERE r.activo = 1 
				GROUP BY responsable";
	$result = $mysqli->query($query);
	echo $mysqli->error;
	
	$template = "Estimado Profesor <b>%s:</b>\n
		<br />
		Le recordamos que la semana entrante (del 09 al 14 de mayo) se 
		llevarán a cabo los examenes finales del turno mayo.
		<br />
		Ante cualquier consulta, rogamos se comunique con la Dirección
		de Asuntos Académicos. (4580-7250 INT 124/125).
		<br />
		<br />
		Saludos Cordiales.\n
		<br />
		<br />
		Secretaría Académica\n
		<br />
		EEYN - UNSAM";
	
	$asunto = "Examenes finales. Turno: Mayo.";	
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$message = sprintf($template, $row['responsable']);
		
		$docente = $row['docente'];
		$tipo = 'docente';
		$nombre_docente = $mysqli->real_escape_string($row['responsable']);
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

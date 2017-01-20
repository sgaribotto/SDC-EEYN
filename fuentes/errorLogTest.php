<?php 
	//errorlog test
	
	 if ($errorLog = fopen('errorLog.txt', 'a+')) {
		 echo "OK";
	 } else {
		 echo "Error de fopen";
	 }
					
	$error = "error";
	$date = "2015-14-09";
	$sesion = json_encode($_SERVER);
	
	$log = $date . "\t" . $error . "\t" . $sesion . "\n";
	
	fwrite($errorLog, $log);
	fclose($errorLog);
	
	echo "Test";
	
	include 'errorLog.txt';
?>

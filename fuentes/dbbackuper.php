<?php
	$max = 10;
	
	$sale = rand(1, $max);
	
	echo $sale;
	
	if ($sale == 1) {
	
		$path = "backups/";
		$nombre = date('Ymdhis');
		
		$action = "mysqldump --user=programas --password=TMtrj9rS5di programas > $path$nombre.sql 2>&1";
		
		exec($action, $output, $return_var);
		
		print_r($output);
		print_r($return_var);
		
		$dir = $path;
		
		$files = scandir($dir);
		
		if (count($files) > 12 ) {
			//borrar el archivo
			for ($i = 2; $i < count($files) - 10; $i++) {
			unlink($path . $files[$i]);
			}
		}
		
		echo "backed";
	}
?>

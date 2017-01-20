<?php
	try {
		$dbh = new PDO('mysqlpdo', 'root', '');
	} catch (PDOException $exception) {
		echo "Conn error: " . $exception->getMessage();
	}
	
	$query = "SELECT * FROM usuario";
					
	$stmt = $dbh->query($query);
	$rows = $stmt->fetchColumn();
	
	while ($row = $rows) {
		print_r($row);
		echo "<hr>";
	}
	
	var_dump($dbh->errorInfo());

	
	
	
	
?>
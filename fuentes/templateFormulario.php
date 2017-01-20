<!DOCTYPE html>
<html>
	<head>
		
		<title></title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once('/fuentes/botonera.php');
		?>
		
	</head>
	
	<body>
		
		
		<div class="ayudaLateral">
			<h2 class="ayudaLateral">Ayuda</h2>
			<h3 calss="ayudaLateral">Datos generals</h3>
			
		</div>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Datos generales</h2>
			
			
		</div>
		
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			
			var hTextoLateral = $("div.textoLateral").css("height");
			$("div.ingreso").css("height", hTextoLateral);
			
			
			
		});
	</script>
</html>
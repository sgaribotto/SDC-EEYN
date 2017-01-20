<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Datos Generales</title>
		<?php 
		
			require_once './fuentes/meta.html';
			
			require 'programas.autoloader.php';
			
			$materia = new clases\Materia($_SESSION['materia']);
			
		?>
		
	</head>
	
	<body>
		
		<?php
			require_once './fuentes/botonera.php';
			require_once "./fuentes/panelNav.php";
			
		?>
		
		<div class="formularioLateral">
			<h2 class="formularioLateral">Datos generales <img src="./images/icons/info.png" alt="Info" title="Si encuentra errores en está información, por favor informe a weeyn@unsam.edu.ar" height="20px" style="cursor:help;margin-left:10px;"></h2>
			
			<div id="formulario">
				<fieldset class="formularioLateral">
				
					<form method="post" class="formularioLateral" action="procesardatos.php?formulario=datosGenerales">
					
						<label for="nombre" class="formularioLateral">Nombre: </label>
						<p class="formularioLateral infoFija"><?php echo $materia->datosMateria['nombre']; ?></p>
						<br />	
						<label class="formularioLateral" for="carreras">Carreras: </label>
							<p class="formularioLateral infoFija"><?php echo $materia->datosMateria['carrera']; ?></p>
							<!--<input type="checkbox" name="EYN-3" value="1" /><span class="checks">Lic. Administración</span>
							<input type="checkbox" value="1" name="EYN-4" /><span class="checks">Lic. Economía</span>
							<input type="checkbox" value="1" name="LITUR" /><span class="checks">Lic. Turismo</span>-->
						<br />
						<label class="formularioLateral" for="carreras">Plan: </label>
							<p class="formularioLateral infoFija"><?php echo $materia->datosMateria['plan']; ?></p>
						<br />
						<label class="formularioLateral" for="anio">Año: </label>
							<p class="formularioLateral infoFija"><?php echo round($materia->datosMateria['cuatrimestre'] / 2); ?></p>
							<!--<input class="formularioLateral" name="anio" type="number" min="1" max="6"  required="required"/>-->
						<br />
						<label class="formularioLateral" for="cuatrimestre">Cuatrimestre: </label>
							<p class="formularioLateral infoFija"><?php echo (($materia->datosMateria['cuatrimestre'] % 2) == 1) ? 1 : 2; ?></p>
							<!--<input class="formularioLateral" name="cuatrimestre" type="number" min="1" max="2" required="required" />-->
						<br />
						<!--<label class="formularioLateral" for="duracion">Duración: </label>
							<input class="formularioLateral" name="duracion" type="number" min="1" max="128" required="required">
							<span class="formularioLateral">Horas</span>
						<br />-->
						<br />
						<h3 class="formularioLateral" style="text-decoration:underline;">Correlatividades</h3>
							<table id="correlatividades" class="formularioLateral correlatividadesTable" style="width:100%;">
								<tr class="formularioLateral correlatividadesTable" style="border-bottom:1px solid black;">
									<th class="formularioLateral correlatividadesTable" style="width:170px;border-bottom:1px solid #CCC;">Código</th>
									<th class="formularioLateral correlatividadesTable" style="border-bottom:1px solid #CCC;">Materia</th>
								</tr>
								<?php
									require('./fuentes/conexion.php');
									
									
									$query = "SELECT m.cod, m.nombre FROM correlatividad AS c
													INNER JOIN materia AS m
													ON m.cod = c.requisito
													WHERE c.materia = '{$_SESSION['materia']}' ";
																	
									$result = $mysqli->query($query);
									
									if ($result->num_rows == 0) {
										echo "<tr><td colspan='2'>No hay correlatividades cargadas</td></tr>";
									} else {
									
										while ($row = $result->fetch_array(MYSQLI_ASSOC) ) {
											echo "<tr class='formularioLateral correlatividadesTable'>
														<td class='formularioLateral correlatividadesTable'>$row[cod]</td>
														<td class='formularioLateral correlatividadesTable'>$row[nombre]</td>
														</tr>";
										}
									}
									
									$result->free();
									$mysqli->close();
								?>
							</table>
							
							
							<br />
						
						<button tpye="submit" class="formularioLateral iconContinuar">Continuar</button>
					</form>
				</fieldset>
			</div>
			
		</div>
		
	</body>
</html>

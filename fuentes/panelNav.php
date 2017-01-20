
<div class="navLateral">
			
	<?php 
	if (in_array(1, $_SESSION['permiso'])) { ?>
	<div class="navAdmin">
		<h2 class="navLateral hidder">Navegación Administrador</h2>
		
		<ol class="navLateral navAdm" >
			<li class="navLateral">Carga y edición</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="docentes.php" class="navLateral">Docentes</a></li>
				<li class="navLateral"><a href="materias.php" class="navLateral">Materias</a></li>
				<li class="navLateral"><a href="responsables.php" class="navLateral">Responsables</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>
			</ul>
			
			<li class="navLateral">Programas Cargados</li>
			
			<ul class="navLateral">
				
				
				<li class="navLateral"><a href="verprogramas.php" class="navLateral">Ver programas</a></li>
				<li class="navLateral"><a href="verplandeclases.php" class="navLateral">Ver los planes de clases</a></li>
				<li class="navLateral"><a href="infodocentes.php" class="navLateral">Información docentes afectados</a></li>
				
				<!--<li class="navLateral"><a href="unidadestematicas.php" class="navLateral">Unidades temáticas</a></li>
				<li class="navLateral"><a href="evaluacion.php" class="navLateral">Evaluación y criterios de aprobación</a></li>
				<li class="navLateral"><a href="bibliografia.php" class="navLateral">Bibliografía</a></li>-->
			</ul>
			<!--
			<li class="navLateral">Plan de clases</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="cronograma.php" class="navLateral">Plan de clases</a></li>
			</ul>
			
			<li class="navLateral">Vista previa</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="programacompleto.php" class="navLateral">Vista previa</a> <img src="./images/icons/info.png" alt="Info" title="Haciendo click aquí, verá toda la información cargada para la materia" height="12px" style="cursor:help;margin-left:10px;"></li>
			</ul>-->
			
		</ol>
	</div>
<?php } elseif (in_array(2, $_SESSION['permiso'])) {  //SA ?> 
	<div class="navDirAdmin">
		<h2 class="navLateral hidder">Secretaría Académica</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Cargos y Designaciones</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="aceptardesignacion.php" class="navLateral">Aceptar Designaciones/Cargos</a></li>
				<li class="navLateral"><a href="listadomaterias.php" class="navLateral">Cargos por materia</a></li>
				<li class="navLateral"><a href="infoafectaciones.php" class="navLateral">Información de Designaciones/Cargos</a></li>
				<li class="navLateral"><a href="estadocargacomisiones.php" class="navLateral">Estado de la carga de comisiones</a></li>
				<li class="navLateral"><a href="consultacomisiones.php" class="navLateral">Ver comisiones</a></li>
				<li class="navLateral"><a href="consultadocentesasignados.php" class="navLateral">Consulta por docente</a></li>
				<!--<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
			
			
			
		</ol>
	</div>
<?php } 

	if (in_array(3, $_SESSION['permiso'])) {  //SA ?> 
	<div class="navSA">
		<h2 class="navLateral">Director Administración</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Cargos y Designaciones</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="aceptardesignacion.php" class="navLateral">Aceptar Designaciones/Cargos</a></li>
				<li class="navLateral"><a href="consultacomisiones.php" class="navLateral">Ver comisiones</a></li>
				<li class="navLateral"><a href="consultadocentesasignados.php" class="navLateral">Consulta por docente</a></li>
				<!--<li class="navLateral"><a href="materias.php" class="navLateral">Materias</a></li>
				<li class="navLateral"><a href="responsables.php" class="navLateral">Responsables</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
		</ol>
	</div>
<?php } 
	if (in_array(4, $_SESSION['permiso']) or in_array(5, $_SESSION['permiso']) or in_array(6, $_SESSION['permiso'])) {  //SA ?> 
		<div class="navDir">
		<h2 class="navLateral">Director de carrera</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Cargos y Designaciones</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="aceptardesignacion.php" class="navLateral">Aceptar Cargos</a></li>
				<li class="navLateral"><a href="listadomaterias.php" class="navLateral">Cargos por materia</a></li>
				<!--<li class="navLateral"><a href="responsables.php" class="navLateral">Responsables</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
		</ol>
	</div>
<?php } ?>

<?php 
	if (in_array(7, $_SESSION['permiso']) or in_array(5, $_SESSION['permiso']) or in_array(6, $_SESSION['permiso']) or in_array(2, $_SESSION['permiso'])) {  //EQUIVALENCIAS ?> 
		<div class="navDir">
		<h2 class="navLateral">Equivalencias</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Programas</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="verprogramas.php" class="navLateral">Ver programas</a></li>
				<li class="navLateral"><a href="verplandeclases.php" class="navLateral">Ver plan de clases</a></li>
				<!--<li class="navLateral"><a href="responsables.php" class="navLateral">Responsables</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
		</ol>
	</div>
<?php } ?>

<?php 
	if (in_array(8, $_SESSION['permiso']) or in_array(5, $_SESSION['permiso']) or in_array(6, $_SESSION['permiso'])) {  //DAA GENERAL ?> 
		<div class="navDir">
		<h2 class="navLateral">Dirección de Asuntos Académicos</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Comisiones</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="consultacomisiones.php" class="navLateral">Ver comisiones</a></li>
				<li class="navLateral"><a href="consultadocentesasignados.php" class="navLateral">Consulta por docente</a></li>
				<!--<li class="navLateral"><a href="responsables.php" class="navLateral">Responsables</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
		</ol>
	</div>
<?php } ?>

<?php 
	if (in_array(2, $_SESSION['permiso']) or in_array(9, $_SESSION['permiso'])) {  //Contactos Y SA ?> 
		<div class="navDir">
		<h2 class="navLateral">Contactos</h2>
		
		<ol class="navLateral" >
			<li class="navLateral">Contactos</li>
			
			<ul class="navLateral">
				<li class="navLateral"><a href="contactos.php" class="navLateral">Contactos</a></li>
				<!--<li class="navLateral"><a href="listadomaterias.php" class="navLateral">Cargos por materia</a></li>
				<li class="navLateral"><a href="infoafectaciones.php" class="navLateral">Información de Designaciones/Cargos</a></li>
				<li class="navLateral"><a href="estadocargacomisiones.php" class="navLateral">Estado de la carga de comisiones</a></li>
				<li class="navLateral"><a href="carreras.php" class="navLateral">Carreras</a></li>
				<li class="navLateral"><a href="personal.php" class="navLateral">Personal</a></li>-->
			</ul>
			
		</ol>
	</div>
<?php } ?>
	
	<?php if ((in_array(7, $_SESSION['permiso']) or in_array(8, $_SESSION['permiso']))
			and !in_array(4, $_SESSION['permiso']) and !in_array(5, $_SESSION['permiso']) 
			and !in_array(6, $_SESSION['permiso'])){  ?>
	
	
	<?php } elseif ( !isset($_SESSION['materia'])) {?>
	<div class="seleccionarMateria">
		<h2 class="navLateral"><a href="seleccionarmateria.php" class="navLateral">Seleccione la materia</a></h2>
		<p class="navLateral">
			Haciendo click en continuar accederá a la edición del programa de la materia seleccionada.
		</p>
	</div>
	
	<?php } else { ?>
	<div class="navUsuario">
			<h2 class="navLateral hidder">Docente - Carga de programas</h2>
			
			<?php echo (isset($_SESSION['nombreMateria'])) ? "<h3 class='nvaLateral'>" . $_SESSION['nombreMateria'] . "</h3>" : ""; ?>
			
			<ol class="navLateral navUsuario" >
				<li class="navLateral">Contenidos mínimos</li>
				
				<ul class="navLateral">
					<li class="navLateral">
						<a href="seleccionarmateria.php" class="navLateral">
						
							<?php
							
								if (isset($_SESSION['materia'])) {
									echo "Cambiar materia";
								} else {
									echo "Seleccionar materia";
								}
							?>
							
						</a><img src="./images/icons/info.png" alt="Info" title="Haciendo click aquí, podrá elegir otra materia para modificar" height="12px" style="cursor:help;margin-left:10px;">
					</li>
					<li class="navLateral"><a href="datosgenerales.php" class="navLateral">Información General</a></li>
					<li class="navLateral"><a href="contenidos.php" class="navLateral">Contenidos mínimos</a></li>
					<li class="navLateral"><a href="datosadicionales.php" class="navLateral">Resultado cursadas</a></li>
					
				</ul>
				<li class="navLateral">Programa vigente - Estructura</li>
				<ul class="navLateral">
					
					<li class="navLateral"><a href="equipodocente.php" class="navLateral">Equipo docente</a></li>
					<li class="navLateral"><a href="asignarcomisiones.php" class="navLateral">Asignar comisiones</a></li>
					<li class="navLateral"><a href="objetivos.php" class="navLateral">Objetivos</a></li>
					<li class="navLateral"><a href="fundamentacion.php" class="navLateral">Enfoque metodológico</a></li>
					<li class="navLateral"><a href="unidadestematicas.php" class="navLateral">Unidades temáticas</a></li>
					<li class="navLateral"><a href="evaluacion.php" class="navLateral">Evaluación y criterios de aprobación</a></li>
					<li class="navLateral"><a href="bibliografia.php" class="navLateral">Bibliografía</a></li>
				</ul>
				
				<li class="navLateral">Plan de clases</li>
				
				<ul class="navLateral">
					<li class="navLateral"><a href="cronograma.php" class="navLateral">Plan de clases</a></li>
				</ul>
				
				<li class="navLateral">Vista previa</li>
				
				<ul class="navLateral">
					<li class="navLateral"><a href="programacompleto.php" class="navLateral">Vista previa</a> <img src="./images/icons/info.png" alt="Info" title="Haciendo click aquí, verá toda la información cargada para la materia" height="12px" style="cursor:help;margin-left:10px;"></li>
				</ul>
				
			</ol>
		</div>
	<?php } ?>
		
</div>

<script>
	$(document).ready( function() {
		
		$('h2.navLateral').siblings().addClass('hidden');
		$('h2.navLateral').click(function() {
			$clicked = $(this);
			$('h2.navLateral').siblings().addClass('hidden');
			$clicked.siblings().slideToggle('fast').removeClass('hidden');
		});
		
		
		if ( $('h2.navLateral').length == 1 ) {
			$('h2.navLateral').click();
		};
		
	});
</script>

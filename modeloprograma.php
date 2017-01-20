<!DOCTYPE html>
<html>
	<head>
		
		
		<?php require_once('./fuentes/meta.html'); ?>
		<?php
			
			include 'fuentes/constantes.php';
			require_once 'programas.autoloader.php';
			
			if (!isset($_SESSION['materiaTemporal'])) {
				$_SESSION['materiaTemporal'] = '1005';
			}
			if (!isset($_SESSION['cuatrimestreTemporal'])) {
				$_SESSION['cuatrimestreTemporal'] = $ANIO . ' - ' . $CUATRIMESTRE;
			}
			
			$periodo = explode(' - ', $_SESSION['cuatrimestreTemporal']);
			$ANIO = $periodo[0];
			$CUATRIMESTRE = $periodo[1];
			
			$materia = new clases\Materia($_SESSION['materiaTemporal']);
			$codMateria = $materia->mostrarCod();
			$datosMateria = $materia->mostrarDatos();
			$equipoDocente = $materia->mostrarEquipoDocente('*', $ANIO, $CUATRIMESTRE);
			$correlativas = $materia->mostrarCorrelativas();
			$unidadesTematicas = $materia->mostrarUnidadesTematicas("*", $ANIO, $CUATRIMESTRE);
			$bibliografia = $materia->mostrarBibliografia($ANIO, $CUATRIMESTRE);

			$programa = new clases\Programa($_SESSION['materiaTemporal'], $_SESSION['id']);
			$camposPrograma = $programa->mostrarCampo($ANIO, $CUATRIMESTRE);
			
			
		?>
		<title><?php echo $materia->mostrarDato('nombre'); ?></title>
		
		
		<style>
			h1.caratula, h2.caratula  {
				text-align:center;
			}
			
			h3.contenido, h4.contenido {
				margin-top:3em;
				
			}
			
			div.recuadro-caratula {
				border:1px solid black;
				border-radius:10px;
				padding: 10px;
				margin: 10px;
			}
			
			p.contenido, li.contenido, div.contenido {
				text-align: justify;
			}
			
			img.titulo {
				width:97%;
				margin: 30px 10px;
			}
			
			
			
			
			
			@media print {
				div.caratula {
					page-break-after: always;
				}
				
				div.bloque {
					page-break-inside: avoid;
				}
				
				/*@page { 
					margin: 0;
					size:auto;
				}
				
				body {
					margin: 2em;
				}*/
			}
			
			
		</style>
	</head>
	<body onload="window.print()">
		
		
		<!--LOGO-->

		<div class="caratula">
			
			<!--<h1 class="universidad caratula">UNIVERSIDAD NACIONAL DE GENERAL SAN MARTÍN</h1>

			<h2 class="unidad-academica caratula">ESCUELA DE ECONOMÍA Y NEGOCIOS (EEYN)</h2>-->
			
			<img class="titulo caratula" src="./images/LogoEscuela.JPG" />
			
			<br />
			<br />
			
			<div class="recuadro-caratula">
				<h3 class="carrera caratula">CARRERA:<span class="carrera"> <?php echo $datosMateria['carrera']; ?></span></h2> 

				<h3 class="catedra caratula">CÁTEDRA:<span class="catedra"> <?php echo "(" . $codMateria . ") " . $datosMateria['nombre']; ?></span></h2> 

				<h3 class="cuatrimestre caratula">CUATRIMESTRE:<span class="cuatrimestre"> <?php echo $datosMateria['cuatrimestre']; ?>º cuatrimestre</span></h3> 

				<h3 class="vigencia caratula">VIGENCIA:<span class="vigencia"> <?php echo $CUATRIMESTRE . "º cuatrimestre de " . $ANIO; ?></span></h3>
				
				<h3 class="equipo-docente contenido">EQUIPO DOCENTE</h3>
				<div class="equipo-docente">
					<!--(nombre de los integrantes del equipo docente con sus respectivos cargos.)-->
					<ul class="docentes contenido">
					<?php 
						foreach ($equipoDocente as $docente) {
							echo "<li class='docentes contenido'>
									<span class='tipoafectacion'>" . $docente['tipoafectacion'] . "</span>: ". $docente['docente'] . "</li>";
						}
					
					 ?>
					 </ul>
				</div>
			</div>
		</div>
		
		
		<div class="contenido">

			
			<div class="bloque">
				<h3 class="ubicacion contenido">UBICACIÓN DE LA ASIGNATURA EN EL PLAN DE ESTUDIO</h3>
				 <div class="ubicacion">
					 <!--(Indicar cuáles son las asignaturas y conocimientos previos 
						que se requiere. Se espera así evitar superposición o 
						ausencia de contenidos y/o de bibliografía y 
						lograr una articulación vertical con asignaturas 
						precedentes y correlativas- y horizontal con asignaturas 
						que deberían cursarse en el mismo año)-->
						<h4 class="cuatrimestre contenido">CUATRIMESTRE:
							<span class="cuatrimestre contenido"> 
								<?php echo $datosMateria['cuatrimestre']; ?>º cuatrimestre
							</span>
						</h4>
						<h4 class="correlativas contenido">CORRELATIVAS:
							<ul class="correlativas contenido">
								<?php
									if (!is_array($correlativas)) {
										echo $correlativas;
									} else {
										
										foreach ($correlativas as $cod => $nombreMateria) {
											echo "<li class='correlativas contenido'>
													(". $cod . ") " . $nombreMateria .
													"</li>";
										}
									}
								?>
							</ul>
						</h4>
				 </div>
			</div>
			
			<div class="bloque">
				<h3 class="contenidos-minimos contenido">CONTENIDOS MÍNIMOS</h3>
				<div class="contenidos-minimos">
					<!--(Especificar los contenidos mínimos que se consignan en 
						el Plan de Estudios de la carrera.)-->
					<span class="contenidos-minimos contenido">
						<?php echo $materia->mostrarDato('contenidosminimos'); ?>
					</span>
				</div>
			</div>
			
			<div class="bloque">
				<h3 class="enfoque contenido">ENFOQUE METODOLÓGICO</h3>
				<div class="enfoque">
					<!--(Se consignarán las actividades y estrategias didácticas 
						que prevé desarrollar el equipo docente)-->
					<span class="contenido">
						<?php 
							$detalle = (isset($camposPrograma['fundamentacion'])) ? $camposPrograma['fundamentacion'] : ""; 
							echo $detalle; 
						?>
					</span>
				</div>
			</div>
			
			<div class="bloque">
				<h3 class="objetivos contenido">OBJETIVOS</h3>
				<div class="objetivos">
					<!--(Se enunciarán en términos de logros que se espera que 
						alcancen los estudiantes)-->
					<span class="contenido">
						<?php 
							$detalle = (isset($camposPrograma['objetivos'])) ? $camposPrograma['objetivos'] : ""; 
							echo $detalle; 
						?>
					</span>
				</div>
			</div>
			
			
				<h3 class="unidades-tematicas contenido">UNIDADES TEMÁTICAS</h3>
				<div class="unidad-tematicas">
					<!--(Deben representar una profundización y ampliación de los 
						contenidos mínimos del Plan de Estudios. 
						Se presentan desagregados y organizados por unidades o 
						módulos)-->
					<ul class="contenido unidades-tematicas">
					<?php
						$unidadesTematicas = $materia->mostrarUnidadesTematicas("*", $ANIO, $CUATRIMESTRE);
						
						if (empty($unidadesTematicas)) {
							
							echo "<li class='contenido unidades-tematicas'>No hay unidades cargadas.</li>";
						} else {
							
							foreach ($unidadesTematicas as $key => $value ) {
								echo "<div class='bloque'>";
								echo "<li class='contenido unidades-tematicas'>";
								echo "<h4 class='contenido unidades-tematicas'>
										UNIDAD $key
									</h4>";
								echo "<p class='contenido unidades-tematicas unidad'>
										$value
									</p>";
								echo "</li></div>";
							}
						}
					?>
					</ul>
				</div>
			
			
			
			<div class="bloque">
				<h3 class="evaluacion contenido">EVALUACIÓN Y CRITERIOS DE APROBACIÓN</h3>
				<div class="evaluacion">
					
					<!--(Especificar los instrumentos y/o procedimientos para la 
						evaluación, los requisitos para que los estudiantes 
						conserven la regularidad (entre ellos, el régimen de 
						asistencia y/o participación en actividades) y 
						las condiciones académicas para aprobar la asignatura 
						(trabajos prácticos, exposiciones orales, exámenes 
						parciales y finales, etc.))-->
					<span class="contenido">
						<?php 
							$detalle = (isset($camposPrograma['evaluacion'])) ? $camposPrograma['evaluacion'] : ""; 
							echo $detalle; 
						?>
					</span>
				</div>
			</div>
			
			<div class="bloque">
				<h3 class="bibliografia contenido">BIBLIOGRAFÍA</h3>
				<div class="bibliografia">
					<!--(Consignar su distribución en las unidades o módulos, 
						distinguiendo entre bibliografía obligatoria y optativa o 
						complementaria. La bibliografía deberá incluir la 
						referencia bibliográfica completa: autor, título, 
						editorial, lugar y fecha de edición y los capítulos y/o 
						apartados correspondientes.)-->
					<ul class="contenido bibliografia">
					<?php
						if (empty($bibliografia)) {
							echo "<li class='contenido bibliografia>No hay bibliografía cargada.</li>";
						} else {
						
							foreach ($bibliografia as $key => $value ) {
								echo "<li class='contenido bibliografia>
										$value[titulo] - $value[autor] - $value[editorial] ($value[paginas] páginas)
									</li>";
							}
						}
							

					?>
					</ul>
				</div>
			</div>
		</div>
		
	</body>
</html>


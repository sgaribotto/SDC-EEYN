<!DOCTYPE html>
<html>
	<head>
		
		<title>Reporte asignación de aulas</title>
		<?php 
			require_once('./fuentes/meta.html');
			require_once('fuentes/constantes.php');
			$ANIO = 2017;
			$CUATRIMESTRE = 1;
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
		<?php require "./fuentes/jqueryScripts.html"; ?>
		<script src="./fuentes/funciones.js"></script>
		<link rel="stylesheet" type="text/css" href="css/aulificator2015.css">
		
		<style media="print">
			#botonera { display:none; }
			
			table {
				border-collapse:collapse;
				font-size: .7em;
			}
			
			a.turnoTitulo {
				display:none;
			}
			
			a.turnoTitulo.selected {
				display:initial;
			}
			
			td.cantidad {
				background-color:red;
			}
		</style>
	</head>
	
	<body>

		<?php
			require_once('./fuentes/botonera.php');
			//require("./fuentes/panelNav.php");
			//require 'fuentes/conexion.php';
		?>
		
		<script>
			$(document).ready(function() {
				
				
				
				var dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
				var turnos = {"M":"Mañana", "N":"Noche", "T":"Tarde"};
				var aulas;
				
				$.get('fuentes/aulificatorAJAX.php', {"act": "listadoAulas"}, function(dataAulas) {
					aulas = dataAulas;
				
					$.get('fuentes/aulificatorAJAX.php', {"act": "report"}, function(data) {
						//console.log(data);
						$('body').append('<h2 class="tituloTurno">Requerimientos de aulas y materias del turno </h2>');
						$.each(turnos, function(key, turno) {
							
							
							$tabla = $('<table class="reporte center-text" border="1">\
										<thead class="reporte">\
											<tr class="reporte headers">\
												<th class="reporte headers chico">Aula</th>\
											</tr>\
										</thead>\
										<tbody class="reporte"></tbody>\
									</table>');
							$tabla.addClass('tabla-' + key);
							$('h2.tituloTurno').append('<a href="#" class="turnoTitulo" data-turno="' + key + '">' + turno  + '</a>');
							
							$('body').append($tabla);
							$('a.turnoTitulo:first-child').addClass('selected');
							
						});
						
						$.each(aulas, function(cod, aula) {
							$('tbody.reporte').append('<tr class="reporte aula aula-' + aula.cod + '" data-capacidad="' + aula.capacidad + '">\
															<td class="reporte aula center-text">' + aula.cod + '<br />\n\
															' + aula.capacidad + 'A</td>\
														</tr>');
						});
						
						$.each(dias, function(num, dia) { //AGREGA LOS HEADERS EN CADA TABLA
							$('tr.reporte.headers').append('<th class="reporte headers grande center-text">' + dia + '</th>');
							$('tr.reporte.aula').append('<td class="reporte aula ' + dia + '"></td>');
						});
						
						$.each(data, function(key, val) {
							
							
							turno = val.turno;
							letraTurno = turno.substr(0, 1);
							dia = val.dia;
							aula = val.aula;
							nombre = val.nombre;
							cantidad = val.cantidad_alumnos;
							materia = val.materia;
							comision = val.comision;
							
							if (dia != 'sábado') {
								
								horario = "";
								switch (turno) {
									case 'M':
										horario = "8:30 a 12:30";
										break;
									case 'M1':
										horario = "8:30 a 10:30";
										break;
									case 'M2':
										horario = "10:30 a 12:30";
										break;
									case 'T':
										horario = "14:00 a 18:00";
										break;
									case 'T1':
										horario = "14:00 a 16:00";
										break;
									case 'T2':
										horario = "16:00 a 18:00";
										break;
									case 'N':
										
										horario = "18:30 a 22:30";
										break;
									case 'N1':
										horario = "18:30 a 20:30";
										break;
									case 'N2':
										horario = "20:30 a 22:30";
										break;
									case 'S':
										horario = "8:30 a 12:30";
										break;
									case 'S1':
										horario = "8:30 a 10:30";
										break;
									case 'S2':
										horario = "10:30 a 12:30";
										break;
								}
							
								$subtabla = $('<table class="subTabla turno-' + turno + '"></table>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + nombre + '\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + horario + '\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla cantidad" data-cantidad="' + cantidad + '"><td class="center-text subTabla cantidad" data-cantidad="' + cantidad + '">' + cantidad + ' Alumnos\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + materia + comision + '\n</td></tr>');
								
								
								
								$celda = $('table.tabla-' + letraTurno + ' tr.aula-' + aula + ' td.' + dia);
								
								
								if (!$celda.is(':empty')) {
									$celda.append('<hr />');
								} else if (turno.substr(1,1) == 2) {
									$celda.append('<br /><table><tr><td><br /></td><tr /><tr><td><br /></td><tr /><tr><td><br /></td><tr /><tr><td><br /></td><tr /></table><hr />');
								}
									
								$celda.append($subtabla);
							} else {
								
								horario = "";
								switch (turno) {
									case 'M':
										horario = "8:30 a 12:30";
										break;
									case 'M1':
										horario = "8:30 a 10:30";
										break;
									case 'M2':
										horario = "10:30 a 12:30";
										break;
									case 'T':
										horario = "14:00 a 18:00";
										break;
									case 'T1':
										horario = "14:00 a 16:00";
										break;
									case 'T2':
										horario = "16:00 a 18:00";
										break;
									case 'N':
										
										horario = "8:30 a 12:30";
										break;
									case 'N1':
										horario = "8:30 a 10:30";
										break;
									case 'N2':
										horario = "10:30 a 12:30";
										break;
									case 'S':
										horario = "8:30 a 12:30";
										break;
									case 'S1':
										horario = "8:30 a 10:30";
										break;
									case 'S2':
										horario = "10:30 a 12:30";
										break;
								}
								$subtabla = $('<table class="subTabla turno-' + turno + '"></table>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + nombre + '\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + horario + ' (' + turno + ')\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla cantidad" data-cantidad="' + cantidad + '"><td class="center-text subTabla cantidad" data-cantidad="' + cantidad + '">' + cantidad + ' Alumnos\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + materia + comision + '\n</td></tr>');
								
								
								
								$celda = $('table.tabla-' + 'M' + ' tr.aula-' + aula + ' td.' + dia);
								//console.log($celda);
								
								if (!$celda.is(':empty')) {
									$celda.append('<hr />');
								} else if (turno.substr(1,1) == 2) {
									$celda.append('<br /><table><tr><td><br /></td><tr /><tr><td><br /></td><tr /><tr><td><br /></td><tr /><tr><td><br /></td><tr /></table><hr />');
								}
								
								$celda.append($subtabla);
								
								$subtabla = $('<table class="subTabla turno-' + turno + '" border="1"></table>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + nombre + '\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + horario + ' (' + turno + ') \n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla cantidad" data-cantidad="' + cantidad + '"><td class="center-text subTabla cantidad" data-cantidad="' + cantidad + '">' + cantidad + ' Alumnos\n</td></tr>');
								$subtabla.append('<tr class="center-text subTabla"><td class="center-text subTabla">' + materia + comision + '\n</td></tr>');
								
								
								
								$celda = $('table.tabla-' + 'N' + ' tr.aula-' + aula + ' td.' + dia);
								//console.log($celda);
								
								if (!$celda.is(':empty')) {
									$celda.append('<hr />');
								} else if (turno.substr(1,1) == 2) {
									$celda.append('<br /><table><tr><td><br />><br /></td><tr /><tr><td><br /></td><tr /><tr><td><br /></td><tr /></table><hr />');
								}
								
								$celda.append($subtabla);
							}
							
						
							
							
						});
						
						var $tables = {"M": $('table.tabla-M'), "N": $('table.tabla-N'), "T": $('table.tabla-T')};
						
						$tables.N.hide();
						$tables.T.hide();
						
						$('a.turnoTitulo').click(function(event) {
							$clicked = $(this);
							turno = $clicked.data('turno');
							event.preventDefault();
							$.each($tables, function(index) {
								$(this).hide();
							});
							$tables[turno].show();
							$('a.turnoTitulo').removeClass('selected');
							$clicked.addClass('selected');
						});
						
						$('td.subTabla.cantidad').each(function(key, val) {
							$this = $(this);
							
							capacidad = $this.closest('tr.aula').data('capacidad');
							
							
							if ($this.data('cantidad') > capacidad) {
								$this.addClass('destacar');
							}
						});
						
						
						
						
					}, 'json');
				}, 'json');
				
				var printingTable = $('table.tabla-M').html();
				console.log($('table.tabla-M').html());
				
				$("#btnExport").click(function (e) {
					window.open('data:text/txt, <table>' + $('table.tabla-M').html() + '</table>' + '<table>' + $('table.tabla-N').html() + '</table>' + '<table>' + $('table.tabla-T').html() + '</table>', 'Download');
					e.preventDefault();
				});
				
			});
		</script>
		<button id="btnExport">Excel</button>
		<div class="reporte">
			
		</div> 
	</body>
</html>

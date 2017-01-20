<div id="Botonera">
	
	<img src="./images/logo_eeyn.png" class="NavBar" Alt="EEYN - UNSAM"/>
	<div class="NavBar">
	<?php if ( isset($_SESSION['usuario']) ) {
		?>
			
			
			<?php
				
				if (isset($_SESSION['permiso']) and in_array(1, $_SESSION['permiso'])) {
					
					?>
			
			<a class="navBar toggleMenu" >Cargar o editar</a>
					
					<ul class="menuAdministrador" style="display:none;">
						<li><a class="menuAdministrador" href="docentes.php">Docentes</a></li>
						<li><a class="menuAdministrador" href="materias.php">Materias</a></li>
						<li><a class="menuAdministrador" href="responsables.php">Responsables</a></li>
						<li><a class="menuAdministrador" href="carreras.php">Carreras</a></li>
						<li><a class="menuAdministrador" href="personal.php">Personal</a></li>
					</ul>
					
			
				<?php } else if (isset($_SESSION['permiso']) 
						and (in_array(4, $_SESSION['permiso']) or in_array(5, $_SESSION['permiso']) or in_array(6, $_SESSION['permiso']))  ){?>
					
					
				<?php } ?>
				<a href="cerrarsesion.php" class="navBar">Cerrar Sesión (
					<?php echo $_SESSION['usuario']; ?>
				)</a>
				<a href="cambiarclave.php" class="navBar">Cambiar Clave</a>
			<?php }?>
		</div>
	
</div>

<style>
	#Botonera
		{
		width:99%;
		position:fixed;
		top:2px;
		color:black;
		font-size:1.1em;
		text-align:left;
		margin:2px auto;
		background-color:white;
		box-shadow:0 5px 5px 0 gray;
		z-index:1000;
		}
	
	a.navBar
		{
		text-decoration:none;
		display:inline;
		color:black;
		padding:5px;
		height:40px;
		margin:5px;
		cursor:pointer;
		border-radius:5px;
		}
	
	a.menuAdministrador {
		text-decoration:none;
		font-size:.8em;
		
	}
	
	a.navBar:Hover
		{
		background-color:#D6EBFF;
		}
	
	img.NavBar
		{
		padding:4px;
		height:28px;
		float:right;
		border-top:solid 2px black;
		border-bottom:solid 2px black;
		}
	
	div.NavBar
		{
		text-align:left;
		width:95%;
		height:16px;
		margin-left:2px;
		padding:10px;
		border-top:solid 2px black;
		border-bottom:solid 2px black;
		}
	
	ul.menuAdministrador {
		position:absolute;
	}
	
			li.elementosMenu {
			
			
		}
		
		 .ui-menu { 
			width: 150px; 
			
		}
		
</style>
	

	<script>
		$(document).ready(function() {
			
			
			$("ul.menuAdministrador").menu();
			
			$('a.toggleMenu').click( function(event){
				event.stopPropagation();
				$('ul.menuAdministrador').toggle();
			});
			
			$(document).click( function(){
				$('ul.menuAdministrador').hide();
			});
			
			
		});
		
		
	</script>

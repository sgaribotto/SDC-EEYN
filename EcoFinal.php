<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=5,IE=9" ><![endif]-->
<!DOCTYPE html>
<html>
<head>
<title>EcoFinal(Matias)</title>
<?php 
			require_once('./fuentes/meta.html');
			
			function __autoload($class) {
				$classPathAndFileName = "./clases/" . $class . ".class.php";
				require_once($classPathAndFileName);
			}
		?>
<style>
	foreignObject > div > div:hover {
		font-style: italic;
		cursor: pointer;
	}
</style>
</head>
<body style="background-color:#ffffff;">
	
			<?php
			require_once('./fuentes/botonera.php');
		?>
		
<div class="mxgraph" style="max-width:100%;border:1px solid transparent;background-color:#ffffff;" data-mxgraph="{&quot;highlight&quot;:&quot;#0000ff&quot;,&quot;lightbox&quot;:false,&quot;nav&quot;:false,&quot;zoom&quot;:0.8,&quot;resize&quot;:true,&quot;toolbar&quot;:&quot;zoom lightbox&quot;,&quot;xml&quot;:&quot;&lt;mxfile userAgent=\&quot;Mozilla/5.0 (X11; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0 Iceweasel/38.8.0\&quot; version=\&quot;5.5.0.1\&quot; editor=\&quot;www.draw.io\&quot; type=\&quot;google\&quot;&gt;&lt;diagram&gt;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&lt;/diagram&gt;&lt;/mxfile&gt;&quot;}"></div>
<div class="dialog resumenMateria" id="dialogResumenMateria"></div>

<div class="dialog resumenMateria" id="dialogResumenMateria"></div>
<script type="text/javascript" src="https://www.draw.io/js/viewer.min.js"></script>
<script>
	$(document).ready(function() {
		
		var dialogOptions = {
				autoOpen: false,
				width: 1000,
				height: 600,
				modal: true,
				appendTo: "#Botonera",
				close: function() {
					$('#mostrarFormulario').off('click');
					$('#mostrarFormulario').click(function() {
						$('div #formulario').slideToggle();
					
					});
				},
					
			};
			
			$('div.dialog').dialog(dialogOptions);
			
		$('g').click(function(event) {
			event.stopPropagation();
			$this = $(this);
			var cod = $this.find('div > div').text();
			if (cod == '') {
				
				cod = $this.next('g').text();
			}
			
			if (cod != parseInt(cod)) {
				cod = $this.prev('g').text();
				
				if (cod != parseInt(cod)) {
					cod = $this.parent('g').prev('g').prev('g').text();
				}
			}
			if (cod = parseInt(cod)) {
				$('#dialogResumenMateria').empty();
					
				$('#dialogResumenMateria').load('resumenmateria.php', {"materia":cod});
				
				$('#dialogResumenMateria').dialog(dialogOptions).dialog('open');
			}
			
		});
		
		$('div.mxgraph').click(function(event) {
			event.preventDefault();
		});
		
		$('td').each(function() {
			if ($(this).text() == '-') {
				for (i = 0; i < 4; i++) {
					$(this).click();
					
				}
				
				
			}
		});
		
	});
	</script>
</body>
</html>

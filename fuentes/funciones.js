
function agregarOtro( clase, tipo ) { //Asignado a un botón agrega otro campo para completar según la clase y el tipo (input, select)
	//REQUIERE JQUERY CARGADO PREVIAMENTE
	
	tipo = tipo || "text";
	var t ="";
	var len = 0;
	var btnX = "";
	
	$("button." + clase).click(function() {
			len = $("div.lista" + clase + " input").length;
			
			switch (tipo) {
			
				case "text":
					t = "<input name='" + clase + len + "' class='formularioLateral icon" + clase + "' type='text' />";
					break;
				
				case "number":
					t = "<input name='" + clase + len + "' class='formularioLateral icon" + clase + "' type='number' />";
					break;
					
			}
			
			btnX = "<button type='button' class='iconEliminar eliminar" + clase + "' id='" + clase + len + "'>Eliminar</button>"
			$("div.lista" + clase).append("<div class='" + clase + len + "'>"  +  t + btnX + "</div>");
			
			$("button.eliminar" + clase ).click(function() {
				$("div." + $(this).attr('id')).remove();
			});
		

		});
	
	
}

	/*function ajustarAlturaLaterales($L1, $L2) {
		var hLateral = Math.max( $L1.css("height").substr(0, 4), $L2.css("height").substr(0, 4));
		$L1.css("height", hLateral);
		$L2.css("height", hLateral);
		
	}*/
		

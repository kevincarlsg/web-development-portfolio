<?php 

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

require_once "../controladores/soporte.controlador.php";
require_once "../modelos/soporte.modelo.php";

class TablaSoporte{

	public function mostrarTabla(){

		if(isset($_GET["id_usuario"]) && isset($_GET["tipo"])){

			if($_GET["tipo"] == "recibidos"){

				$tickets = ControladorSoporte::ctrMostrarTickets("receptor", $_GET["id_usuario"]);

			}

			if($_GET["tipo"] == "enviados"){

				$tickets = ControladorSoporte::ctrMostrarTickets("remitente", $_GET["id_usuario"]);

			}

			if($_GET["tipo"] == "papelera"){

				$tickets1 = ControladorSoporte::ctrMostrarTickets("remitente", $_GET["id_usuario"]);
				

				$tickets2 = ControladorSoporte::ctrMostrarTickets("receptor", $_GET["id_usuario"]);

				$tickets = array_merge($tickets1, $tickets2);

			}

			if(count($tickets)== 0){

	 			$datosJson = '{"data": []}';

				echo $datosJson;

				return;

	 		}

	 		$datosJson = '{

		 	"data": [ ';

		 	foreach ($tickets as $key => $value) {

		 		$seleccionar = "<input type='checkbox' class='ticketCheckbox' idTicket='".$value["id_soporte"]."'>";

		 		$papelera = json_decode($value["papelera"], true);
		 	 	
		 		if($_GET["tipo"] == "recibidos" && $value["tipo"] == "enviado" || $value["tipo"] == "papelera"){

			 		$usuario = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["remitente"]);

			 	}

			 	if($_GET["tipo"] == "enviados" && $value["tipo"] == "enviado" || $value["tipo"] == "papelera"){

			 		$usuario = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["receptor"]);

			 	}

			 	
				if($_GET["tipo"] == "papelera" && $value["tipo"] == "papelera" && count($papelera) != 0){

					if(count($papelera) == 1){

			 			if($papelera[0] == $_GET["id_usuario"]){

					 		$remitente = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["remitente"]);

					 		$receptor = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["receptor"]);
					 	}

					}

					if(count($papelera) == 2){

			 			if($papelera[0] == $_GET["id_usuario"] || $papelera[1] == $_GET["id_usuario"]){

					 		$remitente = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["remitente"]);

					 		$receptor = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $value["receptor"]);
					 	}

					}

				}

				
			 	$asunto = "<a href='index.php?pagina=soporte&soporte=lectura-ticket&tipo=".$_GET["tipo"]."&id_ticket=".$value["id_soporte"]."'>".substr($value["asunto"], 0, 30)."...</a>";

			 	if($value["adjuntos"] != "[]"){

			 		$adjuntos = "<i class='fas fa-paperclip'></i>";

			 	}else{

			 		$adjuntos = "";
			 	}

			 	if($_GET["tipo"] == "papelera" && $value["tipo"] == "papelera" && count($papelera) != 0){

			 		if(count($papelera) == 1){

			 			if($papelera[0] == $_GET["id_usuario"]){

						 	$datosJson	 .= '[

								"'.$seleccionar.'",
								"'.$remitente["nombre"].'",
								"'.$receptor["nombre"].'",
								"'.substr($value["asunto"], 0, 30).'",
								"'.$adjuntos.'",
								"'.$value["fecha_soporte"].'"

					 		],';

					 	}

					}

					if(count($papelera) == 2){

			 			if($papelera[0] == $_GET["id_usuario"] || $papelera[1] == $_GET["id_usuario"]){

						 	$datosJson	 .= '[

								"'.$seleccionar.'",
								"'.$remitente["nombre"].'",
								"'.$receptor["nombre"].'",
								"'.$asunto.'",
								"'.$adjuntos.'",
								"'.$value["fecha_soporte"].'"

					 		],';

					 	}

					}

			 	}

			 	if($_GET["tipo"] == "recibidos" &&
			 	 $value["tipo"] == "enviado" || $value["tipo"] == "papelera" && count($papelera) == 0){

			 		$datosJson	 .= '[

						"'.$seleccionar.'",
						"'.$usuario["nombre"].'",
						"'.$asunto.'",
						"'.$adjuntos.'",
						"'.$value["fecha_soporte"].'"

			 		],';

			 	} 	

			 	if($_GET["tipo"] == "enviados" &&
			 	 $value["tipo"] == "enviado" || $value["tipo"] == "papelera" && count($papelera) == 0){

			 		$datosJson	 .= '[

						"'.$seleccionar.'",
						"'.$usuario["nombre"].'",
						"'.$asunto.'",
						"'.$adjuntos.'",
						"'.$value["fecha_soporte"].'"

			 		],';

			 	} 		

		 	}

		 	$datosJson = substr($datosJson, 0, -1);

		 	$datosJson.=  ']

			}';

			echo $datosJson;	

		}

	}

}

$activar = new TablaSoporte();
$activar -> mostrarTabla();
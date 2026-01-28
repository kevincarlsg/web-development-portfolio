<?php

class ControladorMultinivel{

	/*=============================================
	REGISTRO UNINIVEL
	=============================================*/
	
	static public function ctrRegistroUninivel($datos){

		$tabla = "red_uninivel";

		$respuesta = ModeloMultinivel::mdlRegistroUninivel($tabla, $datos);

		//notificaciones = "tipo:red", "id_user_not:$datos["patrocinador_red"]"

		return $respuesta;

	}

	/*=============================================
	MOSTRAR RED CON INNER JOIN
	=============================================*/

	static public function ctrMostrarRed($tabla1, $tabla2, $item, $valor){

		$respuesta = ModeloMultinivel::mdlMostrarRed($tabla1, $tabla2, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR USUARIO RED
	=============================================*/

	static public function ctrMostrarUsuarioRed($tabla, $item, $valor){

		$respuesta = ModeloMultinivel::mdlMostrarUsuarioRed($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	REGISTRO BINARIA
	=============================================*/
	
	static public function ctrRegistroBinaria($datos){
		
		/*=============================================
		VARIABLES
		=============================================*/	

		$ordenBinaria = null;
		$derrameBinaria = null;	

		/*=============================================
		ASIGNAR EL ORDEN EN LA RED
		=============================================*/	

		$red = ModeloMultinivel::mdlMostrarUsuarioRed("red_binaria", null, null);

		foreach ($red as $key => $value) {

			$ordenBinaria = $value["orden_binaria"] + 1;
			
									
		}

		/*=============================================
		TRAEMOS EL ID DEL PATROCINADOR Y ASIGNAMOS POSICIÓN Y PATROCINADOR
		=============================================*/	

		$patrocinador = ControladorUsuarios::ctrMostrarUsuarios("enlace_afiliado", $datos["patrocinador_red"]);

		$idPatrocinador	= $patrocinador["id_usuario"];

		$derrame = ModeloMultinivel::mdlMostrarUsuarioRed("red_binaria", "usuario_red", $idPatrocinador);

		foreach ($derrame as $key => $value) {
				
			$derrameBinaria = $value["orden_binaria"];	
			
		}

		/*=============================================
		EJECUTAMOS FUNCIÓN PARA DAR POSICIÓN EN LA RED
		=============================================*/	

		$respuesta = ControladorMultinivel::derrameBinaria($derrameBinaria, $datos["patrocinador_red"]);
		
		/*=============================================
		GENERAR LA POSICIÓN CORRESPONDIENTE
		=============================================*/	

		if ($respuesta["posicionLetra"] == "" || $respuesta["posicionLetra"] == "B"){

			$posicionLetra = "A";

		}

		if ($respuesta["posicionLetra"] == "A"){				

			$posicionLetra = "B";
			
		}

		/*=============================================
		GUARDAMOS NUEVO USUARIO EN LA RED
		=============================================*/

		$datosBinaria = array("usuario_red" => $datos["usuario_red"],
							  "orden_binaria" => $ordenBinaria,
							  "derrame_binaria" => $respuesta["derrameBinaria"],				   
							  "posicion_binaria" => $posicionLetra,
							  "patrocinador_red" => $datos["patrocinador_red"]);

		$tabla = "red_binaria";

		$respuesta = ModeloMultinivel::mdlRegistroBinaria($tabla, $datosBinaria);

		return $respuesta;

	}

	/*=============================================
	DERRAME BINARIA
	=============================================*/	

	static public function derrameBinaria($derrameBinaria, $patrocinadorRed){

		$lineaDescendiente = ModeloMultinivel::mdlMostrarUsuarioRed("red_binaria","derrame_binaria", $derrameBinaria);

		/*=============================================
		CUANDO NO HAY LÍNEA DESCENDIENTE
		=============================================*/

		if(!$lineaDescendiente){

			$datos = array("posicionLetra"=>"",
				       	   "derrameBinaria"=>$derrameBinaria);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY UNA LÍNEA DESCENDIENTE
		=============================================*/

		else if(count($lineaDescendiente) == 1){

			$datos = array("posicionLetra"=>"A",
				       	   "derrameBinaria"=>$derrameBinaria);

			return $datos;			

		}else{

			/*=============================================
			CUANDO EL DERRAME VIENE DIRECTAMENTE DE LA EMPRESA
			=============================================*/

			$patrocinador = ControladorGeneral::ctrPatrocinador();

			if($patrocinadorRed == $patrocinador){	

				$datos = ControladorMultinivel::derrameBinaria($derrameBinaria+1, $patrocinadorRed);

				return $datos;

			}else{

				$datos = ControladorMultinivel::derrameBinariaPatrocinador($lineaDescendiente[0]["orden_binaria"]);

				return $datos;

			}

		}

	}

	/*=============================================
	DERRAME BINARIA PATROCINADOR
	=============================================*/	

	static public function derrameBinariaPatrocinador($derrameBinaria){

		$lineaDescendiente = ModeloMultinivel::mdlMostrarUsuarioRed("red_binaria","derrame_binaria", $derrameBinaria);

		/*=============================================
		CUANDO NO HAY LÍNEA DESCENDIENTE
		=============================================*/

		if(!$lineaDescendiente){

			$datos = array("posicionLetra"=>"",
				       	   "derrameBinaria"=>$derrameBinaria);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY UNA LÍNEA DESCENDIENTE
		=============================================*/

		else if(count($lineaDescendiente) == 1){

			$datos = array("posicionLetra"=>"A",
				       	   "derrameBinaria"=>$derrameBinaria);

			return $datos;		

		}else{

			$datos = ControladorMultinivel::derrameBinariaPatrocinador($derrameBinaria+1);

			return $datos;
			
		}

	}

	/*=============================================
	ACTUALIZAR BINARIA
	=============================================*/
	
	static public function ctrActualizarBinaria($datos){

		$tabla = "red_binaria";

		$respuesta = ModeloMultinivel::mdlActualizarVentasComisiones($tabla, $datos);

		return $respuesta;

	}

	/*=============================================
	REGISTRO MATRIZ
	=============================================*/
	
	static public function ctrRegistroMatriz($datos){
		
		/*=============================================
		VARIABLES
		=============================================*/	

		$ordenMatriz = null;
		$derrameMatriz = null;	

		/*=============================================
		ASIGNAR EL ORDEN EN LA RED
		=============================================*/	

		$red = ModeloMultinivel::mdlMostrarUsuarioRed("red_matriz", null, null);

		foreach ($red as $key => $value) {

			$ordenMatriz = $value["orden_matriz"] + 1;
			
									
		}

		/*=============================================
		TRAEMOS EL ID DEL PATROCINADOR Y ASIGNAMOS POSICIÓN Y PATROCINADOR
		=============================================*/	

		$patrocinador = ControladorUsuarios::ctrMostrarUsuarios("enlace_afiliado", $datos["patrocinador_red"]);

		$idPatrocinador	= $patrocinador["id_usuario"];

		$derrame = ModeloMultinivel::mdlMostrarUsuarioRed("red_matriz", "usuario_red", $idPatrocinador);

		foreach ($derrame as $key => $value) {
				
			$derrameMatriz = $value["orden_matriz"];	
			
		}

		/*=============================================
		EJECUTAMOS FUNCIÓN PARA DAR POSICIÓN EN LA RED
		=============================================*/	

		$respuesta = ControladorMultinivel::derrameMatriz($derrameMatriz, $datos["patrocinador_red"]);
		
		/*=============================================
		GENERAR LA POSICIÓN CORRESPONDIENTE
		=============================================*/	

		if ($respuesta["posicionLetra"] == "" || $respuesta["posicionLetra"] == "D"){

			$posicionLetra = "A";

		}

		if ($respuesta["posicionLetra"] == "A"){				

			$posicionLetra = "B";
			
		}

		if ($respuesta["posicionLetra"] == "B"){				

			$posicionLetra = "C";
			
		}

		if ($respuesta["posicionLetra"] == "C"){				

			$posicionLetra = "D";
			
		}

		/*=============================================
		GUARDAMOS NUEVO USUARIO EN LA RED
		=============================================*/

		$datosMatriz = array("usuario_red" => $datos["usuario_red"],
							  "orden_matriz" => $ordenMatriz,
							  "derrame_matriz" => $respuesta["derrameMatriz"],				   
							  "posicion_matriz" => $posicionLetra,
							  "patrocinador_red" => $datos["patrocinador_red"]);

		$tabla = "red_matriz";

		$respuesta = ModeloMultinivel::mdlRegistroMatriz($tabla, $datosMatriz);

		return $respuesta;

	}

	/*=============================================
	DERRAME MATRIZ
	=============================================*/	

	static public function derrameMatriz($derrameMatriz, $patrocinadorRed){

		$lineaDescendiente = ModeloMultinivel::mdlMostrarUsuarioRed("red_matriz","derrame_matriz", $derrameMatriz);

		/*=============================================
		CUANDO NO HAY LÍNEA DESCENDIENTE
		=============================================*/

		if(!$lineaDescendiente){

			$datos = array("posicionLetra"=>"",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY UNA LÍNEA DESCENDIENTE
		=============================================*/

		else if(count($lineaDescendiente) == 1){

			$datos = array("posicionLetra"=>"A",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY DOS LÍNEAS DESCENDIENTES
		=============================================*/

		else if(count($lineaDescendiente) == 2){

			$datos = array("posicionLetra"=>"B",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;		

		}

		/*=============================================
		CUANDO SOLO HAY DOS LÍNEAS DESCENDIENTES
		=============================================*/

		else if(count($lineaDescendiente) == 3){

			$datos = array("posicionLetra"=>"C",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;		

		}else{

			/*=============================================
			CUANDO EL DERRAME VIENE DIRECTAMENTE DE LA EMPRESA
			=============================================*/

			$patrocinador = ControladorGeneral::ctrPatrocinador();

			if($patrocinadorRed == $patrocinador){	

				$datos = ControladorMultinivel::derrameMatriz($derrameMatriz+1, $patrocinadorRed);

				return $datos;

			}else{

				$datos = ControladorMultinivel::derrameMatrizPatrocinador($lineaDescendiente[0]["orden_matriz"]);

				return $datos;

			}

		}

	}

	/*=============================================
	DERRAME MATRIZ PATROCINADOR
	=============================================*/	

	static public function derrameMatrizPatrocinador($derrameMatriz){

		$lineaDescendiente = ModeloMultinivel::mdlMostrarUsuarioRed("red_matriz","derrame_matriz", $derrameMatriz);

		/*=============================================
		CUANDO NO HAY LÍNEA DESCENDIENTE
		=============================================*/

		if(!$lineaDescendiente){

			$datos = array("posicionLetra"=>"",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY UNA LÍNEA DESCENDIENTE
		=============================================*/

		else if(count($lineaDescendiente) == 1){

			$datos = array("posicionLetra"=>"A",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;			

		}

		/*=============================================
		CUANDO SOLO HAY DOS LÍNEAS DESCENDIENTES
		=============================================*/

		else if(count($lineaDescendiente) == 2){

			$datos = array("posicionLetra"=>"B",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;		

		}

		/*=============================================
		CUANDO SOLO HAY DOS LÍNEAS DESCENDIENTES
		=============================================*/

		else if(count($lineaDescendiente) == 3){

			$datos = array("posicionLetra"=>"C",
				       	   "derrameMatriz"=>$derrameMatriz);

			return $datos;		

		}else{

			$datos = ControladorMultinivel::derrameMatrizPatrocinador($derrameMatriz+1);

			return $datos;
			
		}

	}

	/*=============================================
	ACTUALIZAR MATRIZ
	=============================================*/
	
	static public function ctrActualizarMatriz($datos){

		$tabla = "red_matriz";

		$respuesta = ModeloMultinivel::mdlActualizarVentasComisiones($tabla, $datos);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR PAGOS RED
	=============================================*/

	static public function ctrMostrarPagosRed($tabla, $item, $valor){

		$respuesta = ModeloMultinivel::mdlMostrarPagosRed($tabla, $item, $valor);

		return $respuesta;

	}

}
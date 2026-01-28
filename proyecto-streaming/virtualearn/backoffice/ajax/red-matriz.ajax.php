<?php

require_once "../controladores/multinivel.controlador.php";
require_once "../modelos/multinivel.modelo.php";

require_once "../controladores/general.controlador.php";

class AjaxMatriz{

	/*=============================================
	COMISIONES MATRIZ 4X4
	=============================================*/	

	public $idUsuario;

	public function ajaxComisionesMatriz(){

		$valorSuscripcion = ControladorGeneral::ctrValorSuscripcion();

		$comisionNivel1 = 0;
		$ventaNivel1 = 0;

		$comisionNivel2 = 0;
		$ventaNivel2 = 0;

		$respuesta = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "usuario_red", $this->idUsuario);
		
		/*=============================================
		Hacer recorrido por la primera línea para comisiones nivel 1
		=============================================*/	

		$linea1 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $respuesta[0]["orden_matriz"]);
		
		if(count($linea1) == 4){

			$comisionNivel1 = $valorSuscripcion;
			$ventaNivel1 = $valorSuscripcion*4;
		
		}else{

			$comisionNivel1 = 0;
			$ventaNivel1 = count($linea1)*$valorSuscripcion;
		}

		/*=============================================
		Hacer recorrido por la segunda y tercera línea para comisiones nivel 2
		=============================================*/	

		$arrayNivel2 = array();
		$arrayNivel3 = array();
		$arrayNivel4 = array();

		foreach ($linea1 as $key1 => $value1) {
			
			$linea2 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value1["orden_matriz"]);
			
			foreach ($linea2 as $key2 => $value2) {

				$linea3 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value2["orden_matriz"]);

				array_push($arrayNivel2, count($linea3));

				foreach ($linea3 as $key3 => $value3) {

					$linea4 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value3["orden_matriz"]);

					foreach ($linea4 as $key4 => $value4) {

						$linea5 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value4["orden_matriz"]);

						foreach ($linea5 as $key5 => $value5) {

							$linea6 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value5["orden_matriz"]);

							array_push($arrayNivel3, count($linea6));

							foreach ($linea6 as $key6 => $value6) {

								$linea7 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value6["orden_matriz"]);

								foreach ($linea7 as $key7 => $value7) {

									$linea8 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value7["orden_matriz"]);

									foreach ($linea8 as $key8 => $value8) {

										$linea9 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value8["orden_matriz"]);

										foreach ($linea9 as $key9 => $value9) {

											$linea10 = ControladorMultinivel::ctrMostrarUsuarioRed("red_matriz", "derrame_matriz", $value9["orden_matriz"]);

											array_push($arrayNivel4, count($linea10));

										}

									}

								}

							}

						}


					}


				}

			}
		

		}

		/*=============================================
		COMISIONES NIVEL 2
		=============================================*/	

		// echo '<pre>'; print_r($arrayNivel2); echo '</pre>';

		$cantidadNivel2 = 0;

		for($i = 0; $i < count($arrayNivel2); $i++){

			$cantidadNivel2 += $arrayNivel2[$i];	
			
		}

		$bloque = floor($cantidadNivel2/4);

		if($cantidadNivel2 <= 32){

			$comisionNivel2 = 0;
			$ventaNivel2 = $bloque*$valorSuscripcion*3;

		}else{

			$ventaNivel2 = $bloque*$valorSuscripcion*3;
			$comisionNivel2 = $ventaNivel2 - $valorSuscripcion*24;

		}

		/*=============================================
		COMISIONES NIVEL 3
		=============================================*/	

		$cantidadNivel3 = 0;

		for($i = 0; $i < count($arrayNivel3); $i++){

			$cantidadNivel3 += $arrayNivel3[$i];	
			
		}

		$bloque = floor($cantidadNivel3/64);

		if($cantidadNivel3 <= 1024){

			$comisionNivel3 = 0;
			$ventaNivel3 = $bloque*$valorSuscripcion*24;

		}else{

			$ventaNivel3 = $bloque*$valorSuscripcion*24;
			$comisionNivel3 = $ventaNivel3 - $valorSuscripcion*384;

		}

		/*=============================================
		COMISIONES NIVEL 4
		=============================================*/	

		$cantidadNivel4 = 0;

		for($i = 0; $i < count($arrayNivel4); $i++){
			
			$cantidadNivel4 += $arrayNivel4[$i];				
			
		}	

		$bloque = floor($cantidadNivel4/4096);	

		$ventaNivel4 = $bloque*$valorSuscripcion*384;
		$comisionNivel4 = $bloque*$valorSuscripcion*384;		

		$datos = array( "comisionNivel1" => $comisionNivel1,
						"ventaNivel1" => $ventaNivel1,
						"comisionNivel2" => $comisionNivel2,
						"ventaNivel2" => $ventaNivel2,
						"comisionNivel3" => $comisionNivel3,
						"ventaNivel3" => $ventaNivel3,
						"comisionNivel4" => $comisionNivel4,
						"ventaNivel4" => $ventaNivel4,
						"totalComisionMatriz" => $comisionNivel1+$comisionNivel2+$comisionNivel3+$comisionNivel4,
						"totalVentaMatriz" => $ventaNivel1+$ventaNivel2+$ventaNivel3+$ventaNivel4);	

		echo json_encode($datos);
	
	}

	/*=============================================
	ACTUALIZAR COMISIONES Y VENTAS EN LA BD
	=============================================*/	

	public $periodoComision;
	public $periodoVenta;
	public $usuarioRed;

	public function ajaxActualizarMatriz(){

		$datos = array( "usuario_red" => $this->usuarioRed,
						"periodo_comision" => $this->periodoComision,
						"periodo_venta" => $this->periodoVenta);

		$respuesta = ControladorMultinivel::ctrActualizarMatriz($datos);

		echo $respuesta;

	}


}

/*=============================================
COMISIONES MATRIZ 4X4
=============================================*/	

if(isset($_POST["idUsuario"])){

	$comisiones = new AjaxMatriz();
	$comisiones -> idUsuario = $_POST["idUsuario"];
	$comisiones -> ajaxComisionesMatriz();

}

/*=============================================
ACTUALIZAR COMISIONES Y VENTAS EN LA BD
=============================================*/	

if(isset($_POST["periodoComision"])){

	$actualizar = new AjaxMatriz();
	$actualizar -> periodoComision = $_POST["periodoComision"];
	$actualizar -> periodoVenta = $_POST["periodoVenta"];
	$actualizar -> usuarioRed = $_POST["usuarioRed"];
	$actualizar -> ajaxActualizarMatriz();

}


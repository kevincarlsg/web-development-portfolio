<?php

require_once "../controladores/multinivel.controlador.php";
require_once "../modelos/multinivel.modelo.php";

class AjaxBinaria{

	/*=============================================
	ACTUALIZAR COMISIONES Y VENTAS EN LA BD
	=============================================*/	

	public $periodoComision;
	public $periodoVenta;
	public $idUsuario;

	public function ajaxActualizarBinaria(){

		$datos = array( "usuario_red" => $this->idUsuario,
						"periodo_comision" => $this->periodoComision,
						"periodo_venta" => $this->periodoVenta);

		$respuesta = ControladorMultinivel::ctrActualizarBinaria($datos);

		echo $respuesta;

	}

}

/*=============================================
ACTUALIZAR COMISIONES Y VENTAS EN LA BD
=============================================*/	

if(isset($_POST["periodoComision"])){

	$actualizar = new AjaxBinaria();
	$actualizar -> periodoComision = $_POST["periodoComision"];
	$actualizar -> periodoVenta = $_POST["periodoVenta"];
	$actualizar -> idUsuario = $_POST["idUsuario"];
	$actualizar -> ajaxActualizarBinaria();

}

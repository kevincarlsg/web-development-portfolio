<?php

Class ControladorAcademia{

	/*=============================================
	MOSTRAR CATEGORIAS
	=============================================*/

	static public function ctrMostrarCategorias($item, $valor){

		$tabla = "categorias";

		$respuesta = ModeloAcademia::mdlMostrarCategorias($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR CATEGORIAS-VIDEOS CON INNER JOIN
	=============================================*/

	static public function ctrMostrarAcademia($item, $valor){

		$tabla1 = "categorias";
		$tabla2 = "videos";

		$respuesta = ModeloAcademia::mdlMostrarAcademia($tabla1, $tabla2, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR VIDEOS
	=============================================*/

	static public function ctrMostrarVideos($item, $valor){

		$tabla = "videos";

		$respuesta = ModeloAcademia::mdlMostrarVideos($tabla, $item, $valor);

		return $respuesta;

	}



}
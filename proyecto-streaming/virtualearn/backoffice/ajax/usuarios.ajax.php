<?php

require_once "../controladores/general.controlador.php";
require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

class AjaxUsuarios{

	/*=============================================
	Validar email existente
	=============================================*/

	public $validarEmail;
	
	public function ajaxValidarEmail(){

		$item = "email";
		$valor = $this->validarEmail;

		$respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

		echo json_encode($respuesta);

	}

	/*=============================================
	Suscripción con Paypal
	=============================================*/	
	public $suscripcion;
	public $nombre;
	public $email;

	public function ajaxSuscripcion(){

		$ruta = ControladorGeneral::ctrRuta();
		$valorSuscripcion = ControladorGeneral::ctrValorSuscripcion();
		$fecha = substr(date("c"), 0, -6)."Z";
		

		/*=============================================
		CREAR EL ACCESS TOKEN CON LA API DE PAYPAL
		=============================================*/
			
		$curl1 = curl_init();

		curl_setopt_array($curl1, array(
			CURLOPT_URL => "https://api.sandbox.paypal.com/v1/oauth2/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 300,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "grant_type=client_credentials",
			CURLOPT_HTTPHEADER => array(
				"authorization: Basic QVRHd21HZGVxZ2xmS0R4VFpaNkNwUjZFV1VjNWRZcElaUndiX25jcjNvZXRHLVFSWnRQRU1YemY4MlZZZVdpSzg2a1luMjdiVmFOSGtBbFI6RU1sdXZsbVZfQ056QzhXT1p1LTRFSlNfQjBTVkFhWktiNnhjLVgwR2Fob3gtNlFEVE1IS2hJMVEyNnlXaUFuY3pEOG92Q012bFpmMks1TG8=",
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded"
				),
			));

		$response = curl_exec($curl1);
		$err = curl_error($curl1);

		curl_close($curl1);

		if ($err) {

			echo "cURL Error #:" . $err;

		} else {

			$respuesta1 = json_decode($response, true);

			$token = $respuesta1["access_token"];

			/*=============================================
			CREAR EL PRODUCTO CON LA API DE PAYPAL
			=============================================*/
			
			$curl2 = curl_init();

			curl_setopt_array($curl2, array(
				CURLOPT_URL => "https://api.sandbox.paypal.com/v1/catalogs/products",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 300,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\r\n  \"name\": \"Academy of life\",\r\n  \"description\": \"Educación en línea\",\r\n  \"type\": \"DIGITAL\",\r\n  \"category\": \"EDUCATIONAL_AND_TEXTBOOKS\",\r\n  \"image_url\": \"".$ruta."backoffice/vistas/img/plantilla/icono.png\",\r\n  \"home_url\": \"".$ruta."\"\r\n}",
				CURLOPT_HTTPHEADER => array(
					"authorization: Bearer ".$token,
					"cache-control: no-cache",
					"content-type: application/json"
					),
				));

			$response = curl_exec($curl2);
			$err = curl_error($curl2);

			curl_close($curl2);

			if ($err) {

				echo "cURL Error #:" . $err;

			} else {

				$respuesta2 = json_decode($response, true);

				$idProducto = $respuesta2["id"];

				/*=============================================
				CREAR EL PLAN DE PAGOS CON LA API DE PAYPAL
				=============================================*/

				$curl3 = curl_init();

				curl_setopt_array($curl3, array(
					CURLOPT_URL => "https://api.sandbox.paypal.com/v1/billing/plans",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 300,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => "{\r\n  \"product_id\": \"".$idProducto."\",\r\n  \"name\": \"Suscripción mensual a Academy of Life\",\r\n  \"description\": \"Plan de pago mensual a Academy of Life\",\r\n  \"status\": \"ACTIVE\",\r\n  \"billing_cycles\": [\r\n    {\r\n      \"frequency\": {\r\n        \"interval_unit\": \"MONTH\",\r\n        \"interval_count\": 1\r\n      },\r\n      \"tenure_type\": \"REGULAR\",\r\n      \"sequence\": 1,\r\n      \"total_cycles\": 99,\r\n      \"pricing_scheme\": {\r\n        \"fixed_price\": {\r\n          \"value\": \"".$valorSuscripcion."\",\r\n          \"currency_code\": \"USD\"\r\n        }\r\n      }\r\n    }\r\n  ],\r\n  \"payment_preferences\": {\r\n    \"auto_bill_outstanding\": true,\r\n    \"setup_fee_failure_action\": \"CONTINUE\",\r\n    \"payment_failure_threshold\": 3\r\n  },\r\n  \"taxes\": {\r\n    \"percentage\": \"0\",\r\n    \"inclusive\": false\r\n  }\r\n}",
					CURLOPT_HTTPHEADER => array(
						"authorization: Bearer ".$token,
						"cache-control: no-cache",
						"content-type: application/json"
						),
					));

				$response = curl_exec($curl3);
				$err = curl_error($curl3);

				curl_close($curl3);

				if ($err) {

					echo "cURL Error #:" . $err;

				} else {

					$respuesta3 = json_decode($response, true);				

					$idPlan = $respuesta3["id"];

					$curl4 = curl_init();

					curl_setopt_array($curl4, array(
						CURLOPT_URL => "https://api.sandbox.paypal.com/v1/billing/subscriptions",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 300,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => "{\r\n  \"plan_id\": \"".$idPlan."\",\r\n  \"start_time\": \"".$fecha."\",\r\n  \"subscriber\": {\r\n    \"name\": {\r\n      \"given_name\": \"".$this->nombre."\"\r\n    },\r\n    \"email_address\": \"".$this->email."\"\r\n  },\r\n  \"auto_renewal\": true,\r\n  \"application_context\": {\r\n    \"brand_name\": \"Academy of life\",\r\n    \"locale\": \"en-US\",\r\n    \"shipping_preference\": \"SET_PROVIDED_ADDRESS\",\r\n    \"user_action\": \"SUBSCRIBE_NOW\",\r\n    \"payment_method\": {\r\n      \"payer_selected\": \"PAYPAL\",\r\n      \"payee_preferred\": \"IMMEDIATE_PAYMENT_REQUIRED\"\r\n    },\r\n    \"return_url\": \"".$ruta."backoffice/index.php?pagina=perfil\",\r\n    \"cancel_url\": \"".$ruta."backoffice/index.php?pagina=perfil\"\r\n  }\r\n}",
						CURLOPT_HTTPHEADER => array(
							"authorization: Bearer ".$token,
							"cache-control: no-cache",
							"content-type: application/json"
							),
						));

					$response = curl_exec($curl4);
					$err = curl_error($curl4);

					curl_close($curl4);

					if ($err) {

						echo "cURL Error #:" . $err;

					} else {

						$respuesta4 = json_decode($response, true);

						$urlPaypal = $respuesta4["links"][0]["href"];

						echo $urlPaypal;
					}

				}

			}

	
		}

	}

	/*=============================================
	Cancelar Suscrpción
	=============================================*/	
	public $idUsuario;

	public function ajaxCancelarSuscripcion(){

		$valor = $this->idUsuario;

		$respuesta = ControladorUsuarios::ctrCancelarSuscripcion($valor);

		echo $respuesta;

	}

}

/*=============================================
Validar email existente
=============================================*/

if(isset($_POST["validarEmail"])){

	$valEmail = new AjaxUsuarios();
	$valEmail -> validarEmail = $_POST["validarEmail"];
	$valEmail -> ajaxValidarEmail();

}

/*=============================================
Suscripción con Paypal
=============================================*/	

if(isset($_POST["suscripcion"]) && $_POST["suscripcion"] == "ok"){

	$paypal = new AjaxUsuarios();
	$paypal -> nombre = $_POST["nombre"];
	$paypal -> email = $_POST["email"];
	$paypal -> ajaxSuscripcion();

}

/*=============================================
Cancelar Suscrpción
=============================================*/	

if(isset($_POST["idUsuario"])){

	$cancelarSuscripcion = new AjaxUsuarios();
	$cancelarSuscripcion -> idUsuario = $_POST["idUsuario"];
	$cancelarSuscripcion -> ajaxCancelarSuscripcion();

}



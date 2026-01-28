<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentPaypalController extends Controller
{
    private $clientId;
    private $secret;
    private $baseURL;

    public function __construct()
    {
        // Configuración de la URL base para la API de PayPal, dependiendo del entorno
        $this->baseURL = config('app.env') == 'local' 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
        $this->clientId = config('app.paypal_id');
        $this->secret = config('app.paypal_secret');
    }

    // Muestra la vista de pago
    public function paypal()
    {
        return view('paypal');
    }

    // Procesa la orden en PayPal
    public function paypalProcessOrder(string $order)
    {
        try {
            // Obtiene el token de acceso para autorizar la solicitud
            $accessToken = $this->getAccessToken();

            // Realiza la captura del pago utilizando la API de PayPal
            $response = Http::acceptJson()
                ->withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseURL}/v2/checkout/orders/{$order}/capture")
                ->json();

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error en la captura de la orden de PayPal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error procesando la orden de PayPal.'], 500);
        }
    }

    // Procesa el éxito del pago y actualiza el rol del usuario autenticado a "profesor"
    public function paymentSuccess(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cambia el rol a "profesor"
            $user->role = 'profesor';  
            $user->save(); // Guarda el cambio de rol en la base de datos

            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente a profesor.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
        }
    }

    // Obtiene el token de acceso de PayPal
    private function getAccessToken()
    {
        $response = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])
            ->withBasicAuth($this->clientId, $this->secret)
            ->post("{$this->baseURL}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ])->json();

        // Maneja errores si no se puede obtener el token
        if (!isset($response['access_token'])) {
            Log::error('No se pudo obtener el token de acceso de PayPal.');
            throw new \Exception('Error obteniendo el token de acceso de PayPal.');
        }

        return $response['access_token'];
    }
}

@extends('layouts.appStudent')

@section('title', 'Pago de Suscripción')

@section('content')
<div class="container py-5">
    <!-- Título de la página -->
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary"> ClassScan</h1>
        <p class="lead text-black">Elija su suscripción para continuar utilizando nuestros servicios.</p>
    </div>

    <!-- Contenedor de suscripciones -->
    <div class="row justify-content-center">
        <!-- Suscripción Mensual -->
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header text-center bg-primary text-white py-4">
                    <h3 class="mb-0 text-white">Suscripción Mensual</h3>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('images/logo_name.png') }}" alt="Producto Mensual" class="img-fluid my-3" style="width: 150px;">
                    <p class="display-6 text-dark">$90 MX/mes</p>
                    <div id="paypalButtonsMonthly"></div>
                </div>
            </div>
        </div>

        <!-- Suscripción Anual -->
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header text-center bg-primary text-white py-4">
                    <h3 class="mb-0 text-white">Suscripción Anual</h3>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('images/logo_name.png') }}" alt="Producto Anual" class="img-fluid my-3" style="width: 150px;">
                    <p class="display-6 text-dark">$950 MX/año</p>
                    <div id="paypalButtonsAnnual"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Función para manejar la respuesta de pago y enviar la información al backend
        function handlePayment(orderID, subscriptionType) {
            fetch('/payment-success', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de CSRF para Laravel
                },
                body: JSON.stringify({
                    orderID: orderID, 
                    subscriptionType: subscriptionType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(subscriptionType + " activada correctamente");
                    // Recargar la página automáticamente
                    location.reload();
                } else {
                    alert("Error al procesar el pago");
                }
            })
            .catch(error => console.error("Error:", error));
        }

        // Configuración para la suscripción mensual
        paypal.Buttons({
            style: {
                color: 'blue', // Cambia el color a azul
                shape: 'rect', // Forma del botón
                label: 'subscribe' // Etiqueta del botón
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: '4.24' } // Precio mensual en MXN
                    }]
                });
            },
            onApprove: function(data, actions) {
                alert("Pago mensual aprobado con el ID: " + data.orderID);
                handlePayment(data.orderID, "mensual");
            }
        }).render("#paypalButtonsMonthly");

        // Configuración para la suscripción anual
        paypal.Buttons({
            style: {
                color: 'blue', // Cambia el color a azul
                shape: 'rect', // Forma del botón
                label: 'subscribe' // Etiqueta del botón
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: '44.76' } // Precio anual en MXN
                    }]
                });
            },
            onApprove: function(data, actions) {
                alert("Pago anual aprobado con el ID: " + data.orderID);
                handlePayment(data.orderID, "anual");
            }
        }).render("#paypalButtonsAnnual");
    });
</script>
@endsection

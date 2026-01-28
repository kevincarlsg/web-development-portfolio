<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pago de Suscripción</title>
    <!-- Script de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('app')['paypal_id'] }}"></script>
    <style>
        /* Estilos del contenedor y las cajas de pago */
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; }
        h1 { text-align: center; color: #333; margin-top: 20px; font-size: 36px; }
        .payment-container { display: flex; justify-content: center; align-items: center; margin-top: 30px; flex-wrap: wrap; }
        .payment-box { background-color: #e0f2f1; border-radius: 15px; box-shadow: 10px 10px 20px #bebebe, -10px -10px 20px #ffffff; margin: 20px; padding: 30px; width: 300px; text-align: center; transition: transform 0.3s; }
        .payment-box:hover { transform: translateY(-10px); box-shadow: 15px 15px 30px #bebebe, -15px -15px 30px #ffffff; }
        .payment-box h3 { margin-bottom: 20px; color: #333; }
        .product-image { width: 100px; height: auto; margin-bottom: 20px; }
        .payment-box p { font-size: 24px; color: #555; margin-bottom: 30px; }
    </style>
</head>
<body>
    <h1>Control de Asistencias Suscripciones</h1>
    <div class="payment-container">
        <!-- Suscripción Mensual -->
        <div class="payment-box">
            <h3>Suscripción Mensual</h3>
            <img src="{{ asset('logohash.png') }}" alt="Producto Mensual" class="product-image">
            <p>$1USD/mes</p>
            <div id="paypalButtonsMonthly"></div>
        </div>

        <!-- Suscripción Anual -->
        <div class="payment-box">
            <h3>Suscripción Anual</h3>
            <img src="{{ asset('logohash.png') }}" alt="Producto Anual" class="product-image">
            <p>$5/año</p>
            <div id="paypalButtonsAnnual"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Función para manejar la respuesta de pago y enviar la información al backend
            function handlePayment(orderID, subscriptionType) {
                fetch('/process-subscription', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de CSRF para Laravel
                    },
                    body: JSON.stringify({ orderID: orderID, subscriptionType: subscriptionType })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(subscriptionType + " activada correctamente");
                    } else {
                        alert("Error al procesar el pago");
                    }
                })
                .catch(error => console.error("Error:", error));
            }

            // Configuración para la suscripción mensual
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: '1.00' } // Precio mensual
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
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: '5.00' } // Precio anual
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
</body>
</html>

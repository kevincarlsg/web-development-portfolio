
<!--
/**
*Vista Alumnos
*HASH
*Autor: Gadiel Palma Ramos
* Abdiel Salgado Salinas
*Kevin Carlos Sánchez González
*Ricardo Flores Garduño
*Axel Itech Apolonio Flores
*Roberto Héctor Díaz Coyoli 
*Daniel Pérez Muñoz
*Juan Carlos Dzib Gochy
*Fecha de creación: 13/11/2024
*/
-->
<form action="{{ route('payment.process') }}" method="POST" id="payment-form">
    @csrf
    <label for="amount">Cantidad a pagar:</label>
    <input type="number" name="amount" min="1" required>

    <!-- Stripe Elements para recolectar la información de la tarjeta -->
    <div id="card-element"></div>
    <button type="submit" style="margin-top: 10px;">Pagar</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();

    var style = {
        base: {
            color: "#32325d",
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#aab7c4"
            }
        },
        invalid: {
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                alert(result.error.message);
            } else {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
</script>

<!-- Mostrar mensajes de éxito o errores -->
@if (session('status'))
    <div style="color: green;">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Page</title>
</head>
<body>
    <form action="http://localhost:8000/success" method="post" id="payment-form">
        @csrf
        name: <input type='text' name='name'>
        number: <input type='text' name='nb'>
        tid: <input type='text' name='travel_id'>
        classe: <input type='text' name='classe'>
        boarding: <input type='text' name='boarding_station'>
        landing: <input type='text' name='landing_station'>
        <div class="form-row">
          <label for="card-element">
            Credit or debit card
          </label>
          <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
          </div>

          <!-- Used to display Element errors. -->
          <div id="card-errors" role="alert"></div>
        </div>

        <button type="submit">Submit Payment</button>
      </form>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        var stripe = Stripe('pk_test_51KupL6KDYt6sHypuR852V52KGJPEzgAEIt0VhxFxxHihu06LA8mjN5RNecQGQ8455RzVkLJLXVq73tnCxj8lUfpR00FJ7vVsz2');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
            // Inform the customer that there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            } else {
            // Send the token to your server.

            stripeTokenHandler(result.token);
            }
        });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
</body>
</html>

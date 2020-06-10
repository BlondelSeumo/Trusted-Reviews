$( function() {
	
	// stripe form
	var $form = $('#payment-form');

	$form.submit(function(event) {
	    // Disable the submit button to prevent repeated clicks:
	    $form.find('.submit').prop('disabled', true);

	    // Request a token from Stripe:
	    Stripe.createToken({
	        number: $('.card-number').val(),
	        cvc: $('.card-cvc').val(),
	        exp_month: $('.card-expiry-month').val(),
	        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);

	    // Prevent the form from being submitted..:
	    return false;
	});

	function stripeResponseHandler(status, response) {
	  var $form = $('#payment-form');

	  if (response.error) {
	    // Show the errors on the form

	    //$form.find('.payment-errors').text(response.error.message);
	    $form.find('button').prop('disabled', false);

	    sweetAlert("Oops...", response.error.message, "error");

	  } else {

	    // response contains id and card, which contains additional card details
	    var token = response.id;

	    // append values we need!
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    $form.append($('<input type="hidden" name="last4" />').val(response.card.last4));
	    $form.append($('<input type="hidden" name="expDate" />').val(response.card.exp_month + '/' + response.card.exp_year));

	    // and submit
	    $form.get(0).submit();
	  }
	}
	
});
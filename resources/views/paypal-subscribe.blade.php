@extends( 'base' )

@section( 'content' )

<div class="container">
	<h4>{{ _('Redirecting to PayPal') }}</h4>

	<img src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" alt="loading spinner">
	
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalform" id="paypalform">
    <input type="hidden" name="business" value="{{ Options::get_option('paypal_email', 'crivion@gmail.com') }}"/>
	<input type="hidden" name="return" value="{{ route('checkout.success') }}" />
	<input type="hidden" name="cancel_return" value="{{ route('home') }}"/>
	<input type="hidden" name="notify_url" value="{{ route( 'checkout.paypal-complete') }}"/>
	<input type="hidden" name="item_name" value="Company Profile Subscription"/>
	<input type="hidden" name="a3" value="<?= $price ?>"/>
	<input type="hidden" name="p3" value="<?= $duration ?>"/>
	<input type="hidden" name="t3" value="<?= $duration_type ?>"/>
	<input type="hidden" name="src" value="1"/>
	<input type="hidden" name="currency_code" value="{{ Options::get_option('currency_code') }}"/>
	<input type="hidden" name="custom" value="<?= $plan.'_'.$site->id.'_'.$user ?>"/>
	<input type="hidden" name="cmd" value="_xclick-subscriptions"/>
	<input type="hidden" name="rm" value="2"/>
	</form>

	<script>
	window.onload = function(){
	  document.forms['paypalform'].submit();
	}
	</script>

</div><!-- /.container -->

@endsection
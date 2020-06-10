@extends( 'base' )

@section( 'content' )

<div class="container">
	<div class="row align-justify">
		<div class="col-md-8 offset-md-2 card">
			<h4>{{ _( sprintf('Select Payment Method' )) }}</h4>
			<h5>{{ _( sprintf( 'Payment Amount: %s/%s', $amount, ucfirst($plan) ) )}}
			<br>
			<hr>

			@if( 'Yes' == Options::get_option( 'stripeEnable' ) )
			<a href="/checkout/credit-card" class="btn btn-primary btn-lg">
				<i class="fa fa-credit-card payment-icons"></i> {{ _('Credit Card') }}</a>
			@endif
			@if( 'Yes' == Options::get_option( 'paypalEnable' ) )
			<a href="/checkout/paypal" class="btn btn-warning paypalSubmit btn-lg">
				<i class="fab fa-paypal"></i> PayPal</a>
			@endif
								
		</div><!-- /.col-md-6 -->
	</div><!-- /.row -->
</div><!-- /.container -->

@endsection
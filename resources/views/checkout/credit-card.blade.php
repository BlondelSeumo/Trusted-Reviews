@extends('base') 

@section('content')
<div class="container">
	<div class="container">
		<div class="col-8 mx-auto card">
					<h2 class="text-theme-checkout text-center"><i class="fa fa-credit-card"></i> {{ _('Credit Card Payment') }} - {{ Options::get_option( 'currency_symbol' ) . $price . '/' . $plan }} </h2>
					<div class="separator-3"></div>
			<hr />
			<form action="" method="POST" id="payment-form"> <span class="payment-errors"></span>
				@csrf
				<div class="row">
				<div class='col-12 form-group required'>
					<label class='control-label'>{{ _('Name on Card') }}</label>
					<input class='form-control name-on-card' size='4' type='text' required="required" value="{{ old('customer') }}">
				</div>
				<div class='col-12 form-group required'>
					<label class='control-label'>{{ _('Card Number') }}</label>
					<input autocomplete='off' class='form-control card-number' size='20' type='text' required="required">
				</div>
				<div class='col form-group cvc required'>
					<label class='control-label'>{{ _('CVC') }}</label>
					<input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' required="required">
				</div>
				<div class='col form-group expiration required'>
					<label class='control-label'>{{ _('Expiration') }}</label>
					<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' required="required">
				</div>
				<div class='col form-group expiration required'>
					<label class='control-label'> </label>
					<input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' required="required">
				</div>
			
			<div class='col-12 form-group'>
				<input type="submit" class="btn btn-primary form-control btn-block" value="{{ _('Submit Payment') }}">
			</div>
			</div><!-- /.row -->
			</form>
			<hr />
			</div>
		</div>
		<!-- .col-* -->
	</div>
	<!-- ./container add-paddings -->
</div>
<!-- ./container-fluid & white -->@endsection
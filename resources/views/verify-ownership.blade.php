@extends( 'base' )

@section( 'content' )

<div class="container card">
	<h4>{{ _( 'Verify ownership of' ) }} {{ $company->business_name }}</h4>
	
	{{ _( 'In order to validate ownership of this company, we will send an email to your selected email') }}
	{{ '****@' . $company->url }}

	<div class="row">
		<div class="col-md-6">
		<form method="POST" action="{{ route('verifyOwnershipForm', [ 'site' => $company->url ]) }}">
			@csrf
			<br>
			{{ _( 'Enter email for this domain: ' )}}
			<br>

			<input type="hidden" name="plan" value="{{ $plan }}">

			<div class="input-group mb-3">
			  <input type="text" class="form-control" placeholder="{{ _("Recipient's username") }}" aria-label="Recipient's username" aria-describedby="basic-addon2" required="required" name="username">
			  <div class="input-group-append">
			    <span class="input-group-text" id="basic-addon2">{{ '@'. $company->url }}</span>
			  </div>
			</div>

			<input type="submit" name="sb" value="{{ _('Send Verification Email') }}" class="btn btn-primary">
		</form>
	</div><!-- /.col-md-6 -->
	</div><!-- /.row -->
</div><!-- /.container -->

@endsection
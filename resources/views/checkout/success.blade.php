@extends('base') 

@section('content')
<div class="container card">
	<div class="col-6 mx-auto">
		<div class="jumbotron" style="font-size: 18px;">
		<div class="row">
			<div class="col-8">
				<h1><i class="far fa-check-circle"></i> {{ _('Thank you') }}</h1>
				<div class="separator-3"></div>
			</div>
		</div>

		{{ _('Your company ownership should appear in your account') }} <span class="badge badge-warning">{{ _('maximum 2 hours') }}</span> {{ _('from now') }}.
		<br><br>

		{{ sprintf(_('Go to %s My Account %s overview'), '<a href="'.route('myaccount').'" class="btn btn-primary btn-sm">', '</a>') }}
		</div><!-- /.well -->
	</div>
	<!-- .col-* -->
</div>
<!-- ./container add-paddings -->
@endsection
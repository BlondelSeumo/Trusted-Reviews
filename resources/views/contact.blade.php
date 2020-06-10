@extends('base') 
@section('content')
<div class="container-fluid container-white">
	<div class="container add-paddings">
		<div class="col-md-8 col-xs-12 mx-auto">
			<div class="row card">
				<div class="col-xs-12">
					<h2>{{ _('Get in touch') }}</h1>
					<div class="separator-3"></div>
					<hr />
					
					<form class="form-horizontal" method="post">
						@csrf
						<fieldset>
							@if(session()->has('message'))
							<div class="alert alert-warning">{{ session()->get('message') }}</div>
							@endif
							<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<input name="name" type="text" placeholder="Your name" class="form-control" value="{{ old('name') }}">
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<input id="email" name="email" type="text" placeholder="Your email" class="form-control" value="{{ old('email') }}">
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<input id="subject" name="subject" type="text" placeholder="Subject" class="form-control" value="{{ old('subject') }}">
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<input type="number" name="offer-answer" class="form-control" placeholder="How much is {{ $no1 . '+' . $no2 }} = ?">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<textarea rows="9" class="form-control" id="message" name="message" placeholder="Your message">{{ old('message') }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-12">
									<button type="submit" class="btn btn-lg btn-primary btn-block">Send Message</button>
								</div>
							</div>
						</fieldset>
				</div>
			</div>
			</form>
		</div>
		<!-- /.8 -->
	</div>
	<!-- /.row-->
</div>
</div>
<!-- /.container-->@endsection
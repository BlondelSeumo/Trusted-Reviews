@extends( 'base' )

@section( 'content' )

<div class="container card">
	<h4>{{ $title }}</h4>
	{!! $content !!}
</div><!-- /.container -->

@endsection
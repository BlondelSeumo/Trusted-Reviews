@extends( 'base' )

@section( 'content' )

	<div class="container">
		<div class="row">

		<div class="col-xs-12">
		 	{!! Options::get_option( 'catAd' ) !!}
		 	<hr>
		 </div>

		<div class="col-md-12 col-xs-12">
			<div class="card">
				<h5>{{ _( 'Categories' )}}</h5>
				<ul class="list-categories">
				@foreach( $categories as $c )
					<li>
						<a href="{{ route('browse-category', ['slug' => $c->slug]) }}">
							{{ $c->name }} ({{ $c->entries( \App\Sites::class )->wherePublish('yes')->count() }})
						</a>
					</li>
				@endforeach
				</ul>
			</div>
		</div><!-- /.col-md-4 col-xs-12 -->
		
		</div>
	</div><!-- /.container card -->
   
@endsection
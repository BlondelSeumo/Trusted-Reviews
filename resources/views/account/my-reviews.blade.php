@extends( 'base' )

@section( 'content' )

<div class="container">
<div class="row">
<div class="col-10 mx-auto">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="{{ route( 'myaccount' ) }}">{{ _('My Reviews') }}</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'myprofile' ) }}">{{ _('My Profile') }}</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'mycompany' ) }}">{{ _('My Company') }}</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'mybilling' ) }}">{{ _('My Billing') }}</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'logout' ) }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ _('Log Out') }}</a>
  </li>
</ul>
</div><!-- /.col-10 card -->

<div class="col-10 mx-auto">
<div class="card">
<h2>My Reviews</h2>

@if( $myreviews->count() )

<div class="table-responsive">
<table class="table table-alt">
<thead>
	<tr>
		<th>{{ __('Company') }}</th>
		<th>{{ __('Review Title') }}</th>
		<th>{{ __('Rating') }}</th>
		<th>{{ __('Review Date') }}</th>
		<th>{{ __('Edit') }}</th>
		<th>{{ __('Delete') }}</th>
	</tr>
</thead>
	@foreach( $myreviews as $p )
	<tr>
		<td>
			<a href="{{ route('reviewsForSite', $p->site->url) }}">
				{{ $p->site->business_name }}<br>
				<small>{{ $p->site->url }}</small>
			</a>
		</td>
		<td>
			{{ $p->review_title }}
		</td>
		<td>
			{!! str_repeat('<i class="fa fa-star"></i>', $p->rating) !!}
			<br>
			<small class="text-muted">
				{{ number_format($p->rating,2)  }}/5.00
			</small>
		</td>
		<td>
			{{ $p->created_at }}<br>
			@if( $p->publish == 'Yes' )
				<small class="text-success">{{ __( 'Published' ) }}</small>
			@else
				<small class="text-danger">{{ __( 'Pending Review' ) }}</small>
			@endif
		</td>
		<td>
			<a href="{{ route('updateReview', ['reviewId' => $p->id]) }}">
				<i class="fa fa-edit"></i>
			</a>
		</td>
		<td>
			<a href="{{ route('deleteReview', ['reviewId' => $p->id]) }}" onclick="return confirm('{{ __("Are you sure?") }}')">
				<i class="fa fa-trash"></i>
			</a>
		</td>
	</tr>
	@endforeach
</table>
</div><!-- /.table-responsive -->
@else
	<div class="jumbotron">
		<h4>You didn't post any reviews yet.</h4>
	</div>
@endif
</div><!-- /.card -->
</div><!-- /.col-10 -->


</div><!-- /.container -->

@endsection
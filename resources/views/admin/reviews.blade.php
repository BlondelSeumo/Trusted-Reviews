@extends('admin.base')

@section('section_title')
	<strong>Approved Reviews</strong>
@endsection

@section( 'extra_top' )

<div class="box">
<div class="box-header with-border"><strong>Pending Approval</strong></div>
<div class="box-body">


@if($pending_reviews)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Reviewed Item</th>
		<th>Reviewed By</th>
		<th>Title</th>
		<th>Content</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $pending_reviews as $r )
		<tr>
			<td>
				{!! $r->id !!}
			</td>
			<td>
				{{ $r->site->url }}<br>
				<a href="{{ route('reviewsForSite', ['site' => $r->site->url]) }}" target="_blank">View Listing</a>
			</td>
			<td>
				{{ $r->user->name }}<br>
				{{ $r->user->email }}
			</td>
			<td>
				{!! str_repeat('<i class="fa fa-star"></i>', $r->rating) !!}
				<br>
				{{ $r->review_title }}
			</td>
			<td>
				{{ $r->review_content }}
			</td>
			<td>
				{{ $r->created_at }}
			</td>
			<td>
				<a href="/admin/reviews/approve/{{ $r->id }}">Approve</a>
				<br>
				<a href="/admin/reviews/edit/{{ $r->id }}">Edit</a>
				<br>
				<a href="/admin/reviews/delete/{{ $r->id }}" onclick="return confirm('Are you sure?')">
					Delete
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No pending reviews in database.
@endif
	
</div>
</div>

@endsection

@section('section_body')

@if($reviews)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Reviewed Item</th>
		<th>Reviewed By</th>
		<th>Title</th>
		<th>Content</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $reviews as $r )
		<tr>
			<td>
				{!! $r->id !!}
			</td>
			<td>
				{{ @$r->site->url }}<br>
				<a href="{{ route('reviewsForSite', ['site' => @$r->site->url]) }}" target="_blank">View Listing</a>
			</td>
			<td>
				{{ $r->user->name }}<br>
				{{ $r->user->email }}
			</td>
			<td>
				{!! str_repeat('<i class="fa fa-star"></i>', $r->rating) !!}
				<br>
				{{ $r->review_title }}
			</td>
			<td>
				{{ $r->review_content }}
			</td>
			<td>
				{{ $r->created_at }}
			</td>
			<td>
				<br>
				<a href="/admin/reviews/edit/{{ $r->id }}">Edit</a>
				<br>
				<a href="/admin/reviews/delete/{{ $r->id }}" onclick="return confirm('Are you sure?')">
					Delete
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No pending reviews in database.
@endif


@endsection
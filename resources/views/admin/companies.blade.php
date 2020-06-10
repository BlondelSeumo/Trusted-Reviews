@extends('admin.base')

@section('section_title')
	<strong>Approved Companies</strong>
@endsection

@section( 'extra_top' )

<div class="box">
<div class="box-header with-border"><strong>Pending Approval</strong></div>
<div class="box-body">


@if($pending_companies)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>URL</th>
		<th>Name</th>
		<th>Submitted By</th>
		<th>Location</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $pending_companies as $c )
		<tr>
			<td>
				{!! $c->id !!}
			</td>
			<td>
				{{ $c->url }}<br>
				<a href="http://{{ $c->url }}" target="_blank">View Site</a> | 
				<a href="{{ route('reviewsForSite', ['site' => $c->url]) }}" target="_blank">View Listing</a>
			</td>
			<td>
				{{ $c->business_name }}<br>
				Category: {{ @$c->categories->first()->name }}
			</td>
			<td>
				{{ $c->submitter->name }}<br>
				{{ $c->submitter->email }}
			</td>
			<td>
				{{ $c->location }}
			</td>
			<td>
				{{ $c->created_at }}
			</td>
			<td>
				<a href="/admin/companies/approve/{{ $c->url }}">Approve</a>
				<br>
				<a href="/admin/companies/delete/{{ $c->url }}" onclick="return confirm('Are you sure?')">
					Delete
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No pending companies in database.
@endif
	
</div>
</div>

@endsection

@section('section_body')

@if($companies)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>URL</th>
		<th>Name</th>
		<th>Claimed</th>
		<th>Location</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $companies as $c )
		<tr>
			<td>
				{!! $c->id !!}
			</td>
			<td>
				{{ $c->url }}<br>
				<a href="http://{{ $c->url }}" target="_blank">View Site</a> | 
				<a href="{{ route('reviewsForSite', ['site' => $c->url]) }}" target="_blank">View Listing</a>
			</td>
			<td>
				{{ $c->business_name }}<br>
				Category: {{ @$c->categories->first()->name }}
			</td>
			<td>
				@if( $c->claimer()->exists() )
				{{ $c->claimer->name }}<br>
				{{ $c->claimer->email }}
				@else
				Not Claimed
				@endif
			</td>
			<td>
				{{ $c->location }}
			</td>
			<td>
				{{ $c->created_at }}
			</td>
			<td>
				<a href="/admin/companies/edit/{{ $c->url }}">Edit</a>
				<br>
				<a href="/admin/companies/delete/{{ $c->url }}" onclick="return confirm('Are you sure you want to remove this company and all its reviews?')">Delete</a>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No approved companies in database.
@endif

@endsection
@extends('admin.base')

@section('section_title')
	<strong>Users Overview</strong>
@endsection

@section('section_body')
@if($users)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Email</th>
		<th>Date Joined</th>
		<th>Owner Of</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $users as $u )
		<tr>
			<td>
				{!! $u->id !!}
			</td>
			<td>
				{{ $u->name }}
			</td>
			<td>
				{{ $u->email }}
			</td>
			<td>
				{{ $u->created_at }}
			</td>
			<td>
				@if( $u->company()->exists() )
					{{ $u->company->business_name }}<br>
					<a href="{{ route('reviewsForSite', ['site' => $u->company->url]) }}">
						{{ $u->company->url }}
					</a><br>
					<a href="{{ route('manuallyAssign.company', [ 'user' => $u ]) }}">
						Manage Assignment
					</a>
				@else
					No Company Claimed<br>
					<a href="{{ route('manuallyAssign.company', [ 'user' => $u ]) }}">
						Assign Company
					</a>
				@endif
			</td>
			<td>
				<a href="/admin/users/delete/{{ $u->id }}" onclick="return confirm('Are you sure you want to remove this user?')">Delete</a>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No users in database.
@endif

@endsection
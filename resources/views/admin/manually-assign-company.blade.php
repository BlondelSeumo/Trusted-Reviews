@extends('admin.base')

@section('section_title')
<strong>Manually Assign a Company to {{ $user->name }}</strong>
<br>
<a href="/admin/users">Users Overview</a>
@endsection

@section('section_body')

<div class="row">
	<div class="col-xs-12 col-lg-6">
		<form method="POST">
		@csrf
		<label>Select a company from the list</label>
		<hr>
		If you need to remove an assignment, just select "None" and hit save<br>
		Only unassigned companies appear in this list
		<hr>
		<select name="companyID" class="form-control select2">
			<option value="">None</option>
			@if( $user->company) )
			<option value="{{ $user->company->id }}" selected>Currently Assigned: {{ $user->company->business_name }} ({{ $user->company->url }})</option>
			@endif
			@foreach( $companies as $c )
			<option value="{{ $c->id }}">{{ $c->business_name }} ({{ $c->url }})</option>
			@endforeach
		</select>
		<br><br>
		<input type="submit" name="sb" value="Assign Company" class="btn btn-primary">
		</form>
	</div><!-- /.col-xs-12 col-lg-4 -->
</div><!-- /.row -->
@endsection
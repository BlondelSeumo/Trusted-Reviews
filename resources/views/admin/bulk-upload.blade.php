@extends('admin.base')

@section('section_title')
<strong>Bulk Domain Upload</strong>
@endsection

@section('section_body')

<div class="alert alert-warning">
	The <strong>CSV File</strong> format must be:<br />
	domain name, pricing (integer only no currency), registrar, registration date ( day-month-year ), short description, full description, logo image url ( optional )
	<br/>
	<strong>Example CSV File</strong><br/>
	<a href="/DOCUMENTATION/example.csv" target="_blank">Download</a>
</div><!-- /.alert alert-info -->

<form method="POST" enctype="multipart/form-data" class="form-horizontal">
{{ csrf_field() }}

<div class="col-xs-12 col-md-8">
<label>CSV File</label><br />
<input type="file" name="csv" class="form-control"><br />
<input type="submit" name="sb" value="Save" class="btn btn-primary btn-block">
</div>

</form>

@endsection

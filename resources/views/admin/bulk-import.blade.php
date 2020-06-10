@extends('admin.base')

@section('section_title')
<strong>Bulk Import</strong>
@endsection

@section('section_body')

<form method="POST" enctype="multipart/form-data" action="/admin/bulk-import">
{!! csrf_field() !!}

<pre>
CSV File Format:
url, business name, latitude, longitude, location, category
</pre>

<input type="file" name="csv_file" class="form-control" required="required">
<input type="submit" name="sb_csv" value="Start Bulk Import" class="btn btn-primary">

</form>


@endsection
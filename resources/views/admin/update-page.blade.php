@extends('admin.base')

@section('section_title')
	<strong>Pages Manager - Page Update</strong>
	<br/>
	<a href="{!! route('admin-cms') !!}">Pages Overview</a>
@endsection

@section('section_body')
	
	<form method="POST">
		{!! csrf_field() !!}

		<dl>
		<dt>Page Title</dt>
		<dd><input type="text" name="page_title" class="form-control" value="{!! $p->page_title !!}"></dd>
		<dt>Page Content</dt>
		<dd><textarea name="page_content" class="textarea form-control" rows="8">{!! $p->page_content !!}</textarea></dd>
		<dt>&nbsp;</dt>
		<dd><input type="submit" name="sb_page" class="btn btn-primary" value="Save"></dd>
		</dl>

	</form>

@endsection
@extends('admin.base')

@section('section_title')
<strong>Ads Setup</strong>
@endsection

@section('section_body')

<form method="POST">
{!! csrf_field() !!}
<div class="row">
	<div class="col-xs-12 col-md-8">
	<dl>
		<br>
		<dt>Sidebar Ads Code</dt>
		<dd>
			<textarea name="sideAd" rows="5" class="form-control">{!! Options::get_option( 'sideAd' ) !!}</textarea>
		</dd>
		<dt>Homepage Ads Code</dt>
		<dd>
			<textarea name="homeAd" rows="5" class="form-control">{!! Options::get_option( 'homeAd' ) !!}</textarea>
		</dd>
		<dt>Categories Ads Code</dt>
		<dd>
			<textarea name="catAd" rows="5" class="form-control">{!! Options::get_option( 'catAd' ) !!}</textarea>
		</dd>
		<dt>&nbsp;</dt>
		<dd>
			<input type="submit" name="sb_settings" value="Save Ads" class="btn btn-primary">	
		</dd>
	</dl>
	</div>
</div>

</form>

</div><!-- ./row -->
@endsection
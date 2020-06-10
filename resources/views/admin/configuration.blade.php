@extends('admin.base')

@section('section_title')
<strong>Payments Configuration</strong>
@endsection

@section('section_body')

<form method="POST" enctype="multipart/form-data">

<div class="row">
	<div class="col-xs-6">
	<dl>
		<dt>Monthly Plan Price</dt>
		<dd>
			<input type="number" name="monthlyPrice" value="{{ Options::get_option('monthlyPrice') }}" class="form-control">
		</dd>
		<dt>6 Months Plan Price</dt>
		<dd>
			<input type="number" name="6monthsPrice" value="{{ Options::get_option('6monthsPrice') }}" class="form-control">
		</dd>
		<dt>Yearly Plan Price</dt>
		<dd>
			<input type="number" name="yearlyPrice" value="{{ Options::get_option('yearlyPrice') }}" class="form-control">
		</dd>
	</dl>
	</div>
	<div class="col-xs-6">
	<dl>
		<dt>Currency Symbol</dt>
		<dd>
			<input type="text" name="currency_symbol" value="{{ Options::get_option('currency_symbol') }}" class="form-control">
		</dd>

		<dt>Currency ISO Code <small><a href="https://www.xe.com/iso4217.php" target="_blank">ISO List</a></small></dt>
		<dd>
			<input type="text" name="currency_code" value="{{ Options::get_option('currency_code') }}" class="form-control">
		</dd>
		<br>
		<dt>Enable PayPal?</dt>
		<dd>
			<input type="radio" name="paypalEnable" value="No" @if('No' == Options::get_option('paypalEnable')) checked @endif> No 
			<input type="radio" name="paypalEnable" value="Yes" @if('Yes' == Options::get_option('paypalEnable', 'Yes')) checked @endif> Yes 
		</dd>
		<dt>Enable Stripe?</dt>
		<dd>
			<input type="radio" name="stripeEnable" value="No" @if('No' == Options::get_option('stripeEnable')) checked @endif> No 
			<input type="radio" name="stripeEnable" value="Yes" @if('Yes' == Options::get_option('stripeEnable', 'Yes')) checked @endif> Yes 
		</dd>
	</dl>
	</div>
</div>

	
@endsection

@section('extra_bottom')

<div class="row">
	{!! csrf_field() !!}
	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>PayPal Configuration</strong></div>
			<div class="box-body">
			<dl>
			<dt>Paypal Email Address</dt>
			<dd><input type="text" name="paypal_email" value="{{ Options::get_option('paypal_email') }}" class="form-control"></dd>
			</dl>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>Stripe API Configuration</strong></div>
			<div class="box-body">
			<dl>
			<dt>STRIPE_PUBLISHABLE_KEY</dt>
			<dd><input type="text" name="STRIPE_PUBLISHABLE_KEY" value="{{ Options::get_option('STRIPE_PUBLISHABLE_KEY') }}" class="form-control"></dd>
			<dt>STRIPE_SECRET_KEY</dt>
			<dd><input type="text" name="STRIPE_SECRET_KEY" value="{{ Options::get_option('STRIPE_SECRET_KEY') }}" class="form-control"></dd>
			</dl>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-12">
		<div class="box">
			<div class="box-header with-border"><strong>Google Maps (Places API KEY)</strong></div>
			<div class="box-body">
			<dl>
			<dt>GMAPS Places API KEY</dt>
			<dd><input type="text" name="mapsApiKey" value="{{ Options::get_option('mapsApiKey') }}" class="form-control"></dd>
			</dl>
			</div>
		</div>
	</div>

</div>

<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>SEO Configuration</strong></div>
			<div class="box-body">
			<dl>
			<dt>SEO Title Tag</dt>
			<dd><input type="text" name="seo_title" value="{{ Options::get_option('seo_title') }}" class="form-control"></dd>
			<dt>SEO Description Tag</dt>
			<dd><input type="text" name="seo_desc" value="{{ Options::get_option('seo_desc') }}" class="form-control"></dd>
			<dt>SEO Keywords</dt>
			<dd><input type="text" name="seo_keys" value="{{ Options::get_option('seo_keys') }}" class="form-control"></dd>
			<dt>Extra Javascript (added before closing {{ '<head>' }} tag. Ie. Analytics,etc.)</dt>
			<dd><textarea name="extra_js" class="form-control" rows="5">{{ Options::get_option('extra_js') }}</textarea></dd>
			</dl>
			</div>
		</div>
	</div><!-- col-md<->xs -->

	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>Site Configuration</strong></div>
			<div class="box-body">
			<dl>
				<dt>Admin Email</dt>
				<dd>
					<input type="text" name="adminEmail" value="{{ Options::get_option('adminEmail') }}" class="form-control">
				</dd>
				<dt>Site Title (appears in navigation bar)</dt>
				<dd><input type="text" name="site_title" value="{{ Options::get_option('site_title') }}" class="form-control"></dd>
				<dt>Homepage Headline</dt>
				<dd><input type="text" name="site.description" value="{{ Options::get_option('site_description') }}" class="form-control"></dd>
				<dt>Homepage SubHeadline</dt>
				<dd><input type="text" name="site.belowDescription" value="{{ Options::get_option('site_belowDescription') }}" class="form-control"></dd>
				<dt>Homepage Header Image</dt>
				<dd><input type="file" name="homepage_header_image" class="form-control"></dd>
				<dt>Logo Image <small>(Recommended size: 45x45px, can be any size as long as height is 45px)</small></dt>
				<dd><input type="file" name="logo_image" class="form-control"></dd>
				<dt>
					Keep Site Title After Logo? 
					<small>(selecting No, will only show logo without text)</small>
				</dt>
				<dd>
					<select name="enableSiteTitle">
						<option value="Yes" @if(Options::get_option('enableSiteTitle') == 'Yes') selected @endif>Yes</option>
						<option value="No" @if(Options::get_option('enableSiteTitle') == 'No') selected @endif>No</option>
					</select>
				</dd>
			</dl>
			</div><!-- BODY FONT_COLOR -->
		</div>
	</div><!-- color setup -->

	<div class="col-xs-6">
		<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">	
	</div>

	</form>

</div><!-- ./row -->
@endsection
@extends( 'base' )

@section( 'content' )

	<div class="container">
		<div class="row">
		<div class="col-md-4 col-xs-12">
			<div class="card">
				<a href="{{ route('categories') }}">< {{ _('Back') }}</a>
				<hr>
				
				<h5>{{ _('Filters') }}</h5>
				<form method="GET">
				<hr>

				@if( is_null( $location ) )
				<strong>{{ _( 'Location' ) }}</strong>
				<input type="text" name="location" class="form-control" id="city_region">
				<input type="hidden" name="lati" id="lati">
				<input type="hidden" name="longi" id="longi">
				@else
				<span class="tag tag-primary"><i class="fa fa-globe"></i> {{ $location }} <a href="{{ $resetURL }}" class="text-primary">[{{ _('Reset Location') }}]</a></span>
				<br>
				@endif
				<br>
				
				<strong>{{ _( 'Sort By' ) }}</strong>
				<ul class="list-unstyled">
				<li><input type="radio" name="sortBy" value="default" @if(!request()->has('sortBy') OR request( 'sortBy' ) == 'default') checked="" @endif> {{ _('Default') }}</li>
				<li><input type="radio" name="sortBy" value="best" @if(request('sortBy') == 'best') checked @endif> {{ _('Best Rated') }}</li>
				<li><input type="radio" name="sortBy" value="worst" @if(request('sortBy') == 'worst') checked @endif> {{ _('Worst Rated') }}</li>
				<li><input type="radio" name="sortBy" value="most-reviews" @if(request('sortBy') == 'most-reviews') checked @endif> {{ _('Most Reviewed') }}</li>
				<li><input type="radio" name="sortBy" value="least-reviews" @if(request('sortBy') == 'least-reviews') checked @endif> {{ _('Least Reviewed') }}</li>
				</ul><!-- /.list-unstyled -->

				<strong>{{ _( 'No. of reviews' ) }}</strong>
				<ul class="list-unstyled">
				<li><input type="radio" name="reviewsCount" value="0" @if(!request()->has('reviewsCount') OR request('reviewsCount') == '0') checked @endif> {{ _('All') }}</li>
				<li><input type="radio" name="reviewsCount" value="25" @if(request('reviewsCount') == 25) checked @endif> 25+</li>
				<li><input type="radio" name="reviewsCount" value="50" @if(request('reviewsCount') == 50) checked @endif> 50+</li>
				<li><input type="radio" name="reviewsCount" value="100" @if(request('reviewsCount') == 100) checked @endif> 100+</li>
				<li><input type="radio" name="reviewsCount" value="250" @if(request('reviewsCount') == 250) checked @endif> 250+</li>
				<li><input type="radio" name="reviewsCount" value="500" @if(request('reviewsCount') == 500) checked @endif> 500+</li>
				<li><input type="radio" name="reviewsCount" value="1000" @if(request('reviewsCount') == 1000) checked @endif> 1k+</li>
				</ul><!-- /.list-unstyled -->

				<strong>{{ _( 'Company Status' ) }}</strong>
				<ul class="list-unstyled">
				<li><input type="radio" name="companyStatus" value="all" @if(!request()->has( 'companyStatus' ) OR request('companyStatus') == 'all') checked @endif> {{ _('All') }}</li>
				<li><input type="radio" name="companyStatus" value="claimed" @if(request('companyStatus') == 'claimed') checked @endif> {{ _('Claimed') }}</li>
				<li><input type="radio" name="companyStatus" value="unclaimed" @if(request('companyStatus') == 'unclaimed') checked @endif> {{ _('Unclaimed') }}</li>
				</ul><!-- /.list-unstyled -->

				<hr>
				<input type="submit" name="sbFilter" value="{{ _('Apply Filters') }}" class="btn btn-primary">
			</form>
			</div>
		</div><!-- /.col-md-4 col-xs-12 -->

		<div class="col-md-8 col-xs-12">
		<div class="card">
			 <h5>{{ _( 'Showing companies in' ) . ' ' . $category->name }}</h5>
			 
			 <div class="col-xs-12">
			 	{{ Options::get_option( 'catAd' ) }}
			 </div>

		</div><!-- ./card -->
		<br>

		@foreach( $sites as $site )
		<div class="card">
			<h5>{{ $site->business_name }}</h5>
			<span>
				<i class="fa fa-globe"></i> {{ $site->location }} 
				@if( !is_null($location) )
				( {{ number_format($site->distance,2) }} {{ _( 'miles distance' )}} )
				@endif
			</span>
			<div class="row">
				<div class="col-4">
		            <h5 class="text-warning">
		            	{!! str_repeat('<i class="fa fa-star"></i>', $site->reviews->avg('rating')) !!}
		                <span class="badge badge-light">
		                	{{ number_format($site->reviews->avg('rating'),2)  }}/5.00
		            	</span>
		        	</h5>
	        	</div>
	        	<div class="col-8">
					<h5 class="text-muted">{{ $site->reviews->count() }} {{ __('reviews') }}</h5>
				</div>
        	</div>
			<hr>
        	<div class="row">
			@forelse( $site->reviews()->take(2)->orderBy('id','DESC')->get() as $r )
				<div class="col-6">
		            <h6 class="text-muted">{{ $r->review_title }}</h6>
		            <small>{{ substr( $r->review_content, 0, 70 )}}....</small>
	        	</div>
	        @empty
	        	<h6 class="text-muted">&nbsp;&nbsp;&nbsp; {{ _( 'No reviews yet' )}}</h6>
	        @endforelse	
        	</div>
			
			<hr>
        	<a href="{{ route( 'reviewsForSite', [ 'site' => $site ] ) }}" class="text-success">{{ _('Read all reviews for') . ' ' . $site->url }}</a>

		</div><!-- ./card -->
		<br>
		@endforeach

		{{ $sites->appends([ 'sortBy' => request('sortBy'), 
		                     'reviewsCount' => request('reviewsCount'), 
		                     'lati' => request('lati'), 
		                     'longi' => request('longi'), 
		                     'location' => request('location'), 
							 'companyStatus' => request( 'companyStatus' ) ])
							 ->links() }}

		</div>		
		</div>
	</div><!-- /.container card -->
   
@endsection

@section( 'extraJS' )

 <script src="https://maps.google.com/maps/api/js?libraries=places&key={{ Options::get_option('mapsApiKey') }}"></script>
  <script>

  // Address autocomplete
    var placeSearch, autocomplete;
    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'short_name',
      country: 'long_name',
      postal_code: 'short_name'
    };

    function initialize() {
      // Create the autocomplete object, restricting the search
      // to geographical location types.
      autocomplete = new google.maps.places.Autocomplete(
          /** @type {HTMLInputElement} */(document.getElementById('city_region')),
          { types: ['geocode'] });
      // When the user selects an address from the dropdown,
      // populate the address fields in the form.
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        fillInAddress();
      });
    }

    // [START region_fillform]
    function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace();

      console.log( place.address_components );


      // get latitute and longitude
      var lati = place.geometry.location.lat();
      var longi = place.geometry.location.lng();

      document.getElementById('lati').value = lati;
      document.getElementById('longi').value = longi;

      // get city and state
      var ac = place.address_components;
      var city = ac[ 1 ].long_name;
      var state = ac[ 3 ].long_name;

      document.getElementById('city').value = city;
      document.getElementById('state').value = state;

      // console.log( "City: " + city + ", State: " + state );

      for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
      }

      // Get each component of the address from the place details
      // and fill the corresponding field on the form.
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          document.getElementById(addressType).value = val;
          console.log( addressType + "=" + val );
        }
      }
    }
    // [END region_fillform]

    // [START region_geolocation]
    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = new google.maps.LatLng(
              position.coords.latitude, position.coords.longitude);
          var circle = new google.maps.Circle({
            center: geolocation,
            radius: position.coords.accuracy
          });
          autocomplete.setBounds(circle.getBounds());
        });
      }
    }
    // [END region_geolocation]

    $( document ).ready( function() {
    	initialize();
    });
    </script>

@endsection

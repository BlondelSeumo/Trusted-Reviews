@extends( 'base' )

@section( 'content' )

<div class="container">
<div class="row">
<div class="col-10 mx-auto">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'myaccount' ) }}">{{ _('My Reviews') }}</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'myprofile' ) }}">{{ _('My Profile') }}</a>
  </li>

  <li class="nav-item">
    <a class="nav-link active" href="{{ route( 'mycompany' ) }}">{{ _('My Company') }}</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'mybilling' ) }}">{{ _('My Billing') }}</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'logout' ) }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ _('Log Out') }}</a>
  </li>
</ul>
</div><!-- /.col-10 card -->

<div class="col-10 mx-auto">
<div class="card">
<h2>Manage Company</h2>
<hr>

<form method="POST" enctype="multipart/form-data" action="{{ route('manageCompanyProcess') }}">

  @csrf

  <input type="hidden" id="lati" name="lati" value="{{ $company->lati }}">
  <input type="hidden" id="longi" name="longi" value="{{ $company->longi }}">

  <dl>
    <dt>Company URL</dt>
    <dd><a href="{{ $company->url }}" target="_blank">{{ $company->url }}</a></dd>
    
    <dt>Company Category</dt>
    <dd>
      <select class="form-control" name="category_id">
      @foreach( $categories as $c )
        <option value="{{ $c->id }}" @if(@$company->categories->first()->id == $c->id) selected @endif>{{ $c->name }}</option>
      @endforeach
      </select>
    </dd>

    <dt>Company Name</dt>
    <dd><input type="text" name="business_name" value="{{ $company->business_name }}" class="form-control"></dd>

    <dt>Company Address</dt>
    <dd><input type="text" id="city_region" name="location" value="{{ $company->location }}" class="form-control"></dd>

    <dt>Notifications Email <small>( for when you get a new review )</small></dt>
    <dd><input type="text" name="notifications_email" class="form-control" value="{{ $company['metadata']['notifications_email'] }}"></dd>

    <dt>Company Description</dt>
    <dd>
      <textarea name="company_description" class="form-control" rows="5">{{ $company['metadata']['description'] }}</textarea>
    </dd>

    <dt>Company Logo</dt>
    <dd><input type="file" name="company_logo" class="form-control"></dd>

    <dt>&nbsp;</dt>
    <dd><input type="submit" name="sbCompanyUpdate" class="btn btn-primary" value="Save Details"></dd>

  </dl>

</form>

</div><!-- /.card -->
</div><!-- /.col-10 -->


</div><!-- /.container -->

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
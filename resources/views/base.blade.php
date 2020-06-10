<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="crivion">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

    <title>@if(isset($seo_title)) {{ $seo_title }} @else {{ Options::get_option( 'seo_title', 'PHP Trusted Reviews' ) }} @endif</title>

    @if( $d = Options::get_option( 'seo_desc' ) )
    <meta name="description" content="{{ $d }}" />
    @endif

    @if( $k = Options::get_option( 'seo_keys' ) )
    <meta name="keywords" content="{{ $k }}" />
    @endif

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">

    <!-- Bootstrap Select CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
  
    <!-- extra CSS loaded by other views -->
    @yield( 'extraCSS' )

    @if( Options::get_option( 'extra_js' ) )
        {!! Options::get_option( 'extra_js' ) !!}
    @endif

  </head>

  <body>
    @include( 'partials/navi' )

    <main role="main">
        
     @if( 'home' == Route::currentRouteName() )
      @include( 'partials/home-header' )
     @else
      @include( 'partials/inner-header' )
     @endif

    @yield( 'content' )
    
    </main>
    
    <br/>
    @include( 'partials/footer' )
    
    <script src="{{ asset('js/popper.min.js') }}"></script>

    <!-- jQuery Lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Twitter Bootstrap 4 Lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <!-- BS Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

    <!-- Stripe JS SDK -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
    <script type="text/javascript">
      Stripe.setPublishableKey('{{ Options::get_option('STRIPE_PUBLISHABLE_KEY') }}');
    </script>

    <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	@include('sweet::alert')


    {!! Options::get_option( 'siteAnalytics' ) !!}

    <!-- extra JS loaded by other views -->
    @yield( 'extraJS' )

  </body>
</html>
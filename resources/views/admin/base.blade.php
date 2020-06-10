<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="token" content="{!! csrf_token() !!}">
    <title>@yield('seo_title', 'PHP Trusted Reviews')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('resources/assets/admin/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('resources/assets/admin/css/AdminLTE.min.css') }}">
    <!-- WYSIWYG -->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/assets/admin/css/skins/skin-black.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/plugins/iCheck/flat/blue.css') }}">
    <!-- dataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- colorPicker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
    <!-- select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/admin/css/style.css') }}">
    <!-- jQuery JS 2.1.4 -->
    <script src="{{ asset('resources/assets/admin/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <!-- morris.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- country list -->
    <script src="{{ asset('resources/assets/admin/js/countries.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('resources/assets/admin/plugins/select2/select2.min.js') }}"></script>
    <script>
    $(function() {
      $(".js-example-basic-multiple").select2({
            multiple: true,
            data: Col1,
      });
    });
    
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-black sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="/admin" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>T.</b>R</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Trusted</b>.Reviews</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">ADMIN MENU</li>
            <!-- Optionally, you can add icons to the links -->
            <li @if(isset($active) AND ($active == 'dashboard')) class="active" @endif>
              <a href="/admin"><i class="fa fa-link"></i> <span>Dashboard</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'reviews')) class="active" @endif>
              <a href="/admin/reviews"><i class="fa fa-star"></i> <span>Reviews</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'companies')) class="active" @endif">
              <a href="/admin/companies"><i class="fa fa-globe"></i> <span>Companies</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'categories')) class="active" @endif">
              <a href="/admin/categories"><i class="fa fa-bars"></i> <span>Categories</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'users')) class="active" @endif">
              <a href="/admin/users"><i class="fa fa-user"></i> <span>Users</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'bulk')) class="active" @endif">
              <a href="/admin/bulk"><i class="fa fa-upload"></i> <span>Bulk Import</span></a>
            </li>
              <li @if(isset($active) AND ($active == 'pages')) class="active" @endif>
                <a href="/admin/cms"><i class="fa fa-sticky-note-o"></i> <span>General Pages</span></a>
              </li>
              <li @if(isset($active) AND ($active == 'ads')) class="active" @endif>
                <a href="/admin/ads"><i class="fa fa-at"></i> <span>Ads Setup</span></a>
              </li>
              <li @if(isset($active) AND ($active == 'config')) class="active" @endif>
                <a href="/admin/configuration"><i class="fa fa-cog"></i> <span>Configuration</span></a>
              </li>
              <li @if(isset($active) AND ($active == 'mailconfig')) class="active" @endif>
                <a href="/admin/mailconfiguration"><i class="fa fa-envelope"></i> <span>Mail Server</span></a>
              </li>
            </li>
            <li>
              <a href="/admin/logout"><i class="fa fa-power-off"></i> <span>Log Out</span></a>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
        <hr />
        

        @if( session('msg') )
        <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        {!! session('msg') !!}
        </div>
        @endif

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('extra_top') 

        <div class="box">
          <div class="box-header with-border">@yield('section_title', 'Section Title')</div>
          <div class="box-body">
          @yield('section_body', 'Body')
          </div>
          <div class="box-footer"></div>
        </div>

        @yield('extra_bottom') 
        
        
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery UI -->
    <script src="{{ asset('resources/assets/admin/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('resources/assets/admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- wysiwyg -->
    <script src="{{ asset('resources/assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('resources/assets/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- dataTables -->
    <script src="{{ asset('resources/assets/admin/plugins/datatables/jQuery.dataTables.min.js') }}"></script>
    <script src="{{ asset('resources/assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('resources/assets/admin/js/app.min.js') }}"></script>
    <!-- laravel.js -->
    <script src="{{ asset('resources/assets/js/laravel.js') }}"></script>
    <!-- colorPicker -->
    <script src="{{ asset('resources/assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>

    <script>
    jQuery(document).ready(function($){
      $(".textarea").wysihtml5();
      $( ".sortableUI tbody" ).sortable({
        update: function() {
            var order = $( ".sortableUI tbody" ).sortable('toArray');
            $.get('/admin/navigation-ajax-sort', { 'navi_order': order }, function(r) {
              $('.order-result').show();
            });
        }
      });
      $( ".sortableUI" ).disableSelection();
      $('input').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'icheckbox_flat-blue',
        increaseArea: '20%' // optional
      });
      $('.dataTable').dataTable();
      $('.my-colorpicker2').colorpicker();

      $( '.select2' ).select2();

    });
    </script>
  </body>
</html>

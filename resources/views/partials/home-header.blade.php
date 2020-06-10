@if( Options::get_option( 'homepage_header_image' ) )
<style>
.homepage-header {
    margin-top: -10px;
    background-image: url('/public/{{ Options::get_option( 'homepage_header_image' ) }}');
}
</style>
@endif

<div class="homepage-header">
<div class="container">
  <div class="homepage-header-container">
  <h1 class="display-5 text-center">
    <span style="background: #ffc107; color: #212529; border-radius: 6px; padding: 4px;">
      {{ Options::get_option( 'site_description', '#1 Community Trusted Reviews' ) }}
    </span><!-- /.label label-primary -->
  </h1>
  <h3 class="text-center">
    <span class="badge badge-warning">{{ Options::get_option( 'site_belowdescription', 'From People Like You' ) }}</span>
  </h3>
  <div class="searchProcessing"></div><!-- /.searchProcessing -->
  <form method="GET" action="{{ route('search') }}" id="searchUser">
  <div class="row">
    <div class="col-md-3 col-1">&nbsp;</div><!-- /.col-md-1 -->
    <div class="col-md-6 col-8 no-padding">
        <input type="text" name="searchQuery" class="form-control search-padding" placeholder="{{ _('Search for a company') }}" required>
    </div><!-- /.col-7 -->
      <div class="col-md-1 col-2 no-padding">
          <input type="submit" name="searchAction" class="btn btn-warning btn-block search-btn-padding" value="{{ _('Go') }}">
      </div><!-- /.col-md-1 no-padding -->
    </div><!-- /.row -->
  </form>
  </div><!-- /.homepage-header-container -->
</div>
</div><!-- ./jumbotron-->
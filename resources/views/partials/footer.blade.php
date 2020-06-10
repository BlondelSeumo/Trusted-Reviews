<footer class="container-fluid">
<hr>
<div class="row">
<div class="col">
  <h5><i class="far fa-star"></i> {{ Options::get_option( 'site_title', 'PHP Trusted Reviews' ) }}</h5>
</div><!-- /.pull-left -->
<div class="col text-right">
  @foreach( App\Page::all() as $page )
  <a href="/p-{{ $page->page_slug }}">{{ $page->page_title }}</a> | 
  @endforeach
  <a href="{{ route('contact') }}">{{ _('Get In Touch') }}</a> | 
  <a href="{{ route('sitemap') }}">{{ _('Sitemap') }}</a>
</div><!-- /.pull-right -->
</div><!-- /.row -->
</footer>
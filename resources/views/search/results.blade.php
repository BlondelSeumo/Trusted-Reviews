@extends( 'base' )

@section( 'content' )

<div class="container">
    <p>
        <h3>
        {{ __('Search Results: ') }}<span class="text text-success"><em>"{{ request('searchQuery') }}"</em></span>
        </h3>
    </p>

    <div class="row">
    <div class="col-md-8">

    @forelse( $sites as $site )
      
      <div class="card">
      <h5>{{ $site->business_name }}</h5>
      <span><i class="fa fa-globe"></i> {{ $site->location }}</span>
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

    @empty
          <span class="text text-success">
              <h5>
                    {{ __('Sorry, no matching results found') }}
              </h5>
          </span>
            <!-- /.col-md-4 -->
    
          @component('components/add-new-site') @endcomponent
          <!-- /.well -->
    @endforelse

    </div>
    </div>
    <!-- /.text-success -->
</div><!-- /.container -->

@endsection
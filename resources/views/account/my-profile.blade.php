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
    <a class="nav-link active" href="{{ route( 'myprofile' ) }}">{{ _('My Profile') }}</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route( 'mycompany' ) }}">{{ _('My Company') }}</a>
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
<h2>My Profile</h2>
<hr>

<form method="POST" action="{{ route('updateprofile') }}">
  @csrf
  <input type="hidden" name="_method" value="PUT">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control" required="required">
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" required="required">
  </div>
  <div class="form-group">
    <label for="password">Password <small>(leave empty to keep current)</small></label>
    <input type="password" name="password" value="" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">
    <i class="fa fa-btn fa-sign-in"></i>Update My Profile
  </button>
</form>

</div><!-- /.card -->
</div><!-- /.col-10 -->


</div><!-- /.container -->

@endsection
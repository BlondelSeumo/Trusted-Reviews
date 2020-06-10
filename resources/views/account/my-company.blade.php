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
<h2>My Company</h2>
<hr>

@if($company)

<table>
	<thead>
		<tr>
			<th>URL</th>
			<th>Name</th>
			<th>Location</th>
			<th>Manage</th>
			<th>Embed Reviews</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<a href="{{ route('reviewsForSite', ['site'=> $company->url]) }}">
					{{ $company->url }}
				</a>
			</td>
			<td><strong>{{ $company->business_name }}</strong></td>
			<td>{{ $company->location }}</td>
			<td>
				<a href="{{ route('manageCompany') }}" class="btn btn-sm btn-primary">Manage Profile</a><br>
			</td>
			<td>
				<a href="{{ route('myEmbeddedCodes') }}" class="btn btn-sm btn-warning">Embedded Codes</a>
			</td>
		</tr>
	</tbody>
</table>

@else
<div class="well">
	{{ _( 'You did not claim any company.' )}}
</div><!-- /.well -->

@endif

</div><!-- /.card -->
</div><!-- /.col-10 -->


</div><!-- /.container -->

@endsection
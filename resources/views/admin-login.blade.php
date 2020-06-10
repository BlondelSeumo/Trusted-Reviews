@extends('base')

@section('content')
<div class="container add-paddings">
<div class="card">
        <h3 class="heading"><i class="glyphicon glyphicon-lock"></i> Login</h3>
        
        {!! $message !!}

		<form method="POST" action="/admin/login">
		    {!! csrf_field() !!}

		    <div>
		        User
		        <input type="text" name="ausername" class="form-control">
		    </div>

		    <div>
		        Password
		        <input type="password" name="apassword" class="form-control">
		    </div>

		    <div>
		    	<br />
		        <button type="submit" class="btn btn-primary">Login</button>
		    </div>
		</form>
</div>
</div>
@endsection
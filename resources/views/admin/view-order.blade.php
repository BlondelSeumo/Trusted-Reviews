@extends('admin.base')

@section('section_title')
	<strong>Order Content <a href="/admin" class="btn btn-default btn-xs">Back to Dashboard</a></strong>
@endsection

@section('extra_top')

<div class="row">
	<div class="col-xs-12 col-md-4">
		<div class="box">
			<div class="box-header with-border"><strong>Order Contact</strong></div>
			<div class="box-body">

			<dl>
			<dt>Customer Name:</dt>
				<dd>{{ $order->customer }}</dd>
			<dt>Customer Email:</dt>
			<dd>
				<a href="mailto:{{ $order->email }}?subject=Order #{{ $order->id }}">
					{{ $order->email }}
				</a>
			</dd>
			</dl>

			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-8">
		<div class="box">
			<div class="box-header with-border"><strong>Order Info</strong></div>
			<div class="box-body">
			
			<div class="row">
				<div class="col-xs-6">
					<dl>
					<dt>Order Status</dt>
						<dd>{{ $order->order_status}}</dd>
					<dt>Total</dt>
						<dd>${{ number_format( $order->total, 0 ) }}</dd>
					</dl>
				</div><!-- ./col-xs-6 -->
				<div class="col-xs-6">
					<dl>
					<dt>Order Date</dt>
						<dd>{{ date( 'jS F Y H:i', strtotime( $order->order_date ) ) }}</dd>
					<dt>Payment Type</dt>
						<dd>{{ $order->payment_type }}</dd>
					</dl>
				</div><!-- ./col-xs-6 -->
			</div><!-- ./row -->

			</div>
		</div>
	</div>
</div>
@endsection

@section('section_body')

	<table class="table dataTable">
	<thead>
		<tr>
			<th>Domain ID</th>
			<th>Domain Name</th>
			<th>Price</th>
		</tr>
	</thead>
	<tbody>
	@foreach( $order_content as $o )

	<tr>
		<td>{!! $o->id !!}</td>
		<td>{{ $o->name }}</td>
		<td>${{ number_format($o->price,0) }}</td>
	</tr>

	@endforeach
	</tbody>
	</table>

@endsection
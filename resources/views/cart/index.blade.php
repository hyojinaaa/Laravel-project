@extends('master')

@section('title', 'Cart')
@section('description', 'This is cart page')

@section('styles')

@endsection

@section('content')
	<h1>This is our Shopping Cart for {{ \Auth::user()->name }}</h1>
	 {{-- Row of the Cart --}}
	@if(count($Cart) > 0)
		<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th style="width:50%">Product</th>
					<th style="width:10%">Price</th>
					<th style="width:10%">Quantity</th>
					<th style="width:20%">Subtotal</th>
					<th style="width:10%"></th>
				</tr>
			</thead>
			<body>
				@foreach($Cart as $CartRow)
					<tr>
						{{-- call function 'product' from Cart Model --}}
						<td><a href="/Shop/{{ $CartRow->product_id }}">{{ $CartRow->product->title }}</a> - Size {{ $CartRow->size }}</td>
						<td>{{ $CartRow->product->price }}</td>
						<td>{{ $CartRow->quantity }}</td>
						<td>{{ $CartRow->subtotal }}</td>
						<td><a href="/Cart/Remove/{{ $CartRow->id }}" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></a></td>
					</tr>
				@endforeach
			</body>
			<tfoot>
				<tr>
					<td><a href="/Shop" class="btn btn-warning">Continue Shopping</a></td>
					<td></td>
					<td></td>
					<td></td>
					<td><a href="" class="btn btn-success">Checkout</a></td>
				</tr>
			</tfoot>
		</table>
	@else
		<p>Your Shopping Cart is Empty!</p>
	@endif
@endsection

@section('scripts')

@endsection
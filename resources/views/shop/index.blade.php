@extends('master')

@section('title', 'Shop')
@section('description', 'This is our shop page')

@section('styles')

@endsection

@section('content')
	<h1>This is our Shop Page</h1>
	<?php if(count($AllProducts) > 0 ): ?>
		<?php foreach($AllProducts as $product): ?>
			<div class="col-sm-4 col-xs-12">
				<a href="Shop/{{ $product->id }}">
					<div class="thumbnail">
						<h3>{{ $product->title }}</h3>
						<p>{{ $product->price }}</p>
						<img class="img-responsive" src="images/products/{{ $product->image }}">
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<p>There are no products in the database.</p>
	<?php endif; ?>
	<p><a href="/Shop/AddProduct" class="btn btn-primary">Add New Product</a></p>
@endsection

@section('scripts')

@endsection
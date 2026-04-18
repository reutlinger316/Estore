@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>My Marketplace Products</h2>

        <a href="{{ route('customer.marketplace.products.create') }}">Add New Product</a><br><br>

        @forelse($products as $product)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                <h3>{{ $product->name }}</h3>
                <p><strong>Price:</strong> {{ number_format($product->price, 2) }} Tk</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                <p><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
            </div>
        @empty
            <p>You have not listed any marketplace products yet.</p>
        @endforelse
    </div>
@endsection
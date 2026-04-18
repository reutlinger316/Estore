@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Customer Marketplace</h2>

        <a href="{{ route('customer.marketplace.account') }}">Marketplace Account</a><br><br>
        <a href="{{ route('customer.marketplace.products.create') }}">Sell a Product</a><br><br>
        <a href="{{ route('customer.marketplace.products.my-products') }}">My Products</a><br><br>

        @forelse($products as $product)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="120"><br><br>
                @endif

                <h3>{{ $product->name }}</h3>
                <p><strong>Seller:</strong> {{ $product->seller->name }}</p>
                <p><strong>Price:</strong> {{ number_format($product->price, 2) }} Tk</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                <p>{{ $product->description }}</p>
            </div>
        @empty
            <p>No marketplace products available yet.</p>
        @endforelse
    </div>
@endsection
@extends('layouts.app')

@section('content')

    <h1>{{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>
    <p>Location: {{ $storeFront->location }}</p>

    <div style="margin-bottom: 15px;">
        <a href="{{ route('customer.cart.index') }}">
            <button>View My Cart ({{ $cartCount }})</button>
        </a>

        @if($cart && $cart->store_front_id)
            <p>
                Current cart shop:
                {{ $cart->storeFront?->name ?? 'N/A' }}
                - {{ $cart->storeFront?->branch_name ?? 'N/A' }}
            </p>
        @endif
    </div>

    <hr>

    <h2>Menu</h2>

    @forelse($items as $item)
        <div>
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" width="140"><br><br>
            @endif

            <h3>{{ $item->item_name }}</h3>
            <p>{{ $item->description }}</p>
            <p>Price: {{ $item->price }}</p>
            <p>Stock: {{ $item->stock_quantity }}</p>

            @if($item->discount > 0)
                <p>Discount: {{ $item->discount }}</p>
            @endif

            <form method="POST" action="{{ route('customer.cart.add', $item) }}">
                @csrf
                <button type="submit">Add to Cart</button>
            </form>
        </div>

        <hr>
    @empty
        <p>No items available for this shop.</p>
    @endforelse

    <hr>

    <h2>Customer Reviews</h2>

    @auth
    <form method="POST" action="{{ route('customer.reviews.store', $storeFront) }}">
        @csrf
        <div class="mb-3">
            <label for="rating">Rating (1–5)</label>
            <input type="number" name="rating" min="1" max="5">
        </div>
        <div class="mb-3">
            <label for="title">Review Title</label>
            <input type="text" name="title">
        </div>
        <div class="mb-3">
            <label for="body">Review Body</label>
            <textarea name="body"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Review</button>
    </form>
    @endauth

    <a href="{{ route('customer.reviews.index', $storeFront) }}" class="btn btn-info">See Reviews</a>

    <hr>

    <h2>Our Other Branches</h2>

    @forelse($otherBranches as $branch)
        <div>
            <a href="{{ route('customer.shops.show', $branch) }}">
                {{ $branch->name }} - {{ $branch->branch_name }}
            </a>
        </div>
    @empty
        <p>No other branches available.</p>
    @endforelse

@endsection

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
        @php
            $originalPrice = (float) $item->price;
            $discountPercent = (float) $item->discount;
            $discountAmount = round(($originalPrice * $discountPercent) / 100, 2);
            $discountedPrice = max(round($originalPrice - $discountAmount, 2), 0);
        @endphp

        <div>
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" width="140"><br><br>
            @endif

            <h3>{{ $item->item_name }}</h3>
            <p>{{ $item->description }}</p>

            <p>
                <strong>Price:</strong>
                @if($item->discount > 0)
                    <span style="text-decoration: line-through; color: #888;">
                        {{ number_format($item->price, 2) }}
                    </span>
                    <span style="color: green; font-weight: bold; margin-left: 6px;">
                        {{ number_format($discountedPrice, 2) }}
                    </span>
                @else
                    {{ number_format($item->price, 2) }}
                @endif
            </p>

            <p>Stock: {{ $item->stock_quantity }}</p>

            @if($item->discount > 0)
                <p>Discount: {{ number_format($item->discount, 2) }}%</p>
            @endif

            @if($item->averageRating())
                @php
                    $avg = round($item->averageRating(), 1);
                @endphp
                <p>
                    <strong>Item Rating:</strong>
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avg))
                            <span style="color: gold;">★</span>
                        @else
                            <span style="color: #ccc;">★</span>
                        @endif
                    @endfor
                    {{ $avg }}/5
                </p>
            @else
                <p><strong>Item Rating:</strong> No ratings yet</p>
            @endif

            <div style="display: flex; gap: 10px; align-items: center; margin-top: 10px; flex-wrap: wrap;">
                <form method="POST" action="{{ route('customer.cart.add', $item) }}" style="margin: 0;">
                    @csrf
                    <button type="submit">Add to Cart</button>
                </form>

                @auth
                    @if(!$item->reviews->where('customer_id', auth()->id())->count())
                        <a href="{{ route('customer.item-reviews.create', $item) }}" class="btn btn-primary">
                            Add Review
                        </a>
                    @endif
                @endauth

                <a href="{{ route('customer.item-reviews.index', $item) }}" class="btn btn-info">
                    See Item Reviews
                </a>
            </div>
        </div>

        <hr>
    @empty
        <p>No items available for this shop.</p>
    @endforelse

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
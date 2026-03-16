@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">My Cart</h2>

        @if($cart->storeFront)
            <div class="card">
                <p><strong>Cart Shop:</strong> {{ $cart->storeFront->name }} - {{ $cart->storeFront->branch_name }}</p>
            </div>
        @endif

        @php $total = 0; @endphp

        <div class="list-block">
            @forelse($cartItems as $cartItem)
                @php
                    $subtotal = $cartItem->item->price * $cartItem->quantity;
                    $total += $subtotal;
                @endphp

                <div class="card">
                    <h3>{{ $cartItem->item->item_name }}</h3>
                    <p><strong>Price:</strong> {{ $cartItem->item->price }}</p>
                    <p><strong>Quantity:</strong> {{ $cartItem->quantity }}</p>
                    <p><strong>Subtotal:</strong> {{ $subtotal }}</p>

                    <div class="actions">
                        <form method="POST" action="{{ route('customer.cart.remove', $cartItem) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>Your cart is empty.</p>
                </div>
            @endforelse
        </div>

        @if($cartItems->count() > 0)
            <div class="card">
                <h3>Total: {{ $total }}</h3>

                <div class="actions">
                    <form method="POST" action="{{ route('customer.cart.checkout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

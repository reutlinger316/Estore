@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="welcome">
            <h1>Customer Dashboard</h1>
            <p>Welcome, <strong>{{ auth()->user()->name }}</strong></p>
            <p>Role: {{ auth()->user()->role }}</p>
            <p>Balance: {{ number_format(auth()->user()->balance, 2) }}</p>
        </div>

        <div class="actions" style="justify-content:center;">
            <a href="{{ route('customer.shops.index') }}" class="btn btn-primary">Browse Shops</a>
            <a href="{{ route('customer.cart.index') }}" class="btn btn-primary">My Cart</a>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-primary">My Orders</a>
            <a href="{{ route('customer.creditcards.index') }}" class="btn btn-primary">Manage Credit Cards</a>
            <a href="{{ route('customer.funds.index') }}" class="btn btn-primary">Manage Funds</a>
        </div>
        <div class="actions" style="justify-content:center;">
            <a href="{{ route('customer.marketplace.account') }}">
                <button>Marketplace Account</button>
            </a>

            <a href="{{ route('customer.marketplace.products.index') }}">
                <button>Customer Marketplace</button>
            </a>
        </div>
    </div>
@endsection

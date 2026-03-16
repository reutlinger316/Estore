@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="welcome">
            <h1>Merchant Dashboard</h1>
            <p>Welcome, <strong>{{ auth()->user()->name }}</strong></p>
            <p>Role: {{ auth()->user()->role }}</p>
            <p>Balance: {{ number_format(auth()->user()->balance, 2) }}</p>

        </div>

        <div class="actions" style="justify-content:center;">
            <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-primary">Manage StoreFronts</a>
            <a href="{{ route('merchant.discounts.index') }}" class="btn btn-primary">Manage Discounts</a>
        </div>
    </div>
@endsection

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
            <a href="{{ route('customer.creditcards.index') }}" class="btn btn-primary">Manage Credit Cards</a>
            <a href="{{ route('customer.funds.index') }}" class="btn btn-primary">Manage Funds</a>
        </div>
    </div>
@endsection

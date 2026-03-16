@extends('layouts.app')

@section('content')
    <h1>StoreFront Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    <h3>Branch Balances</h3>

    @forelse($branches as $branch)
        <div style="margin-bottom: 12px;">
            <p><strong>{{ $branch->name }} - {{ $branch->branch_name }}</strong></p>
            <p>Balance: {{ number_format($branch->balance, 2) }}</p>
        </div>
    @empty
        <p>No branch assigned yet.</p>
    @endforelse

    <a href="{{ route('storefront.branch-requests') }}">
        <button>View Branch Requests</button>
    </a>

    <a href="{{ route('storefront.orders.index') }}">
        <button>Manage Orders</button>
    </a>
@endsection

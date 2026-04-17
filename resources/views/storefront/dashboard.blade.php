@extends('layouts.app')

@section('content')
    <h1>StoreFront Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    <h3>Branch Balances</h3>

    @forelse($branches as $branch)
        <div style="margin-bottom: 18px; border:1px solid #ddd; padding:12px;">
            <p><strong>{{ $branch->name }} - {{ $branch->branch_name }}</strong></p>
            <p>Balance: {{ number_format($branch->balance, 2) }}</p>

            <p><strong>Items:</strong></p>
            @forelse($branch->items as $item)
                <div style="margin-bottom: 8px;">
                    {{ $item->item_name }} — Stock: {{ $item->stock_quantity }} / Threshold: {{ $item->low_stock_threshold }}
                    <a href="{{ route('storefront.restock-requests.create', $branch) }}" style="margin-left: 10px;">Request Restock</a>
                </div>
            @empty
                <p>No items found for this branch.</p>
            @endforelse
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
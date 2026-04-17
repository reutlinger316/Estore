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
            <a href="{{ route('merchant.restock-requests.index') }}" class="btn btn-primary">Restock Requests</a>
            <a href="{{ route('merchant.performance.index') }}" class="btn btn-primary">Store Fronts Performance</a>
        </div>

        <div class="list-block" style="margin-top: 30px;">
            <div class="card">
                <h2>Low Stock Alerts</h2>
                @forelse($lowStockItems as $item)
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $item->item_name }}</strong>
                        <div>{{ $item->storeFront->name }} - {{ $item->storeFront->branch_name }}</div>
                        <div>Stock: {{ $item->stock_quantity }} | Threshold: {{ $item->low_stock_threshold }}</div>
                    </div>
                @empty
                    <p>No low stock alerts right now.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
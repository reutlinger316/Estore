@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Orders - {{ $storeFront->name }}</h2>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Filter Orders by Status</h3>

            <form method="GET" action="{{ route('merchant.performance.orders', $storeFront) }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <select name="status" style="padding:8px;">
                    <option value="">All Orders</option>
                    <option value="pending" {{ $selectedStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $selectedStatus == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ $selectedStatus == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>

                <a href="{{ route('merchant.performance.orders', $storeFront) }}" class="btn btn-secondary">
                    Clear Filter
                </a>
            </form>
        </div>

        <div class="card">
            <h3>Orders List</h3>

            @forelse($orders as $order)
                <div style="border-bottom:1px solid #ddd; padding:10px 0;">
                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p><strong>Customer:</strong> {{ $order->customer?->name ?? 'Unknown' }}</p>

                    <p>
                        <strong>Status:</strong>
                        <span style="
                            padding:4px 8px;
                            border-radius:5px;
                            color:white;
                            background-color:
                                {{ $order->status === 'paid' ? 'green' :
                                   ($order->status === 'pending' ? 'orange' : 'red') }};
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>

                    <p><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
                    <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }}</p>
                    <p><strong>Paid At:</strong> {{ $order->paid_at ? $order->paid_at->format('d M Y h:i A') : 'Not paid yet' }}</p>

                    @if($order->orderItems->count())
                        <p><strong>Items:</strong></p>
                        <ul>
                            @foreach($order->orderItems as $orderItem)
                                <li>
                                    {{ $orderItem->item?->name ?? 'Item removed' }}
                                    @if(isset($orderItem->quantity))
                                        - Qty: {{ $orderItem->quantity }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <p>No orders found for this storefront.</p>
            @endforelse
        </div>

        <div class="actions" style="gap: 10px; flex-wrap: wrap; margin-top: 20px;">
            <a href="{{ route('merchant.performance.show', $storeFront) }}" class="btn btn-secondary">
                Back to Summary
            </a>
        </div>
    </div>
@endsection
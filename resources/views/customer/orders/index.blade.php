@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">My Orders</h2>

        <div class="list-block">
            @forelse($orders as $order)
                <div class="card">
                    <h3>Order #{{ $order->id }}</h3>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                    <p><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p><strong>Total:</strong> {{ $order->total_amount }}</p>

                    <h4 class="mt-3">Items:</h4>
                    @foreach($order->orderItems as $orderItem)
                        <p>
                            {{ $orderItem->item->item_name ?? 'Item deleted' }}
                            - Qty: {{ $orderItem->quantity }}
                            - Price: {{ $orderItem->price }}
                        </p>
                    @endforeach
                </div>
            @empty
                <div class="card">
                    <p>No orders placed yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">My Orders</h2>

        <div class="list-block">
            @forelse($orders as $order)
                <div class="card">
                    <h3>Order #{{ $order->id }}</h3>
                    <p><strong>Receipt No:</strong> {{ $order->receipt_number ?? 'Not generated yet' }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($order->type) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}</p>

                    @if($order->type === 'delivery')
                        <p><strong>Zone:</strong>
                            {{ $order->delivery_zone === 'inside' ? 'Inside ' . $order->storeFront->delivery_city : 'Outside ' . $order->storeFront->delivery_city }}
                        </p>
                        <p><strong>Delivery Fee:</strong> {{ number_format($order->delivery_fee, 2) }}</p>
                        <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    @endif

                    <h4 class="mt-3">Items:</h4>
                    @foreach($order->orderItems as $orderItem)
                        <p>
                            {{ $orderItem->item->item_name ?? 'Item deleted' }}
                            - Qty: {{ $orderItem->quantity }}
                            - Unit Price: {{ number_format($orderItem->price, 2) }}
                        </p>
                    @endforeach

                    <div class="actions" style="margin-top: 12px; display:flex; gap:10px; flex-wrap:wrap;">
                        <a href="{{ route('customer.receipts.show', $order) }}" class="btn btn-primary">
                            View Receipt
                        </a>

                        <form method="POST" action="{{ route('customer.orders.order-again', $order) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                Order Again
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No orders placed yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
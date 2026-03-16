@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Branch Orders</h2>

        <div class="list-block">
            @forelse($orders as $order)
                <div class="card">
                    <h3>Order #{{ $order->id }}</h3>
                    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Branch:</strong> {{ $order->storeFront->name }} - {{ $order->storeFront->branch_name }}</p>
                    <p><strong>Current Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p><strong>Total:</strong> {{ $order->total_amount }}</p>

                    <h4 class="mt-3">Items</h4>
                    @foreach($order->orderItems as $orderItem)
                        <p>
                            {{ $orderItem->item->item_name ?? 'Item deleted' }}
                            - Qty: {{ $orderItem->quantity }}
                            - Price: {{ $orderItem->price }}
                        </p>
                    @endforeach

                    <form method="POST" action="{{ route('storefront.orders.status.update', $order) }}">
                        @csrf

                        <div class="mb-3">
                            <label>Update Status</label>
                            <select name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            @empty
                <div class="card">
                    <p>No orders available for your branch yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

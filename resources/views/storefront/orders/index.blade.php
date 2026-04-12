@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Branch Orders</h2>

        <div class="list-block">
            @forelse($orders as $order)
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="margin: 0;">Order #{{ $order->id }}</h3>
                        
                        @if($order->type === 'takeaway')
                            <span style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">
                                Takeaway (Pickup)
                            </span>
                        @else
                            <span style="background: #f3e8ff; color: #7e22ce; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">
                                Delivery
                            </span>
                        @endif
                    </div>
                    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Branch:</strong> {{ $order->storeFront->name }} - {{ $order->storeFront->branch_name }}</p>
                    <p><strong>Current Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p><strong>Total:</strong> {{ $order->total_amount }}</p>

                    @if($order->type === 'delivery')
                        <div style="margin-top: 1rem; background-color: #fafafa; padding: 1rem; border-radius: 6px; border-left: 4px solid #a855f7;">
                            <h4 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 0.95rem; color: #333;">Delivery Information</h4>
                            <p style="margin-bottom: 0.25rem;"><strong>Phone:</strong> {{ $order->delivery_phone }}</p>
                            <p style="margin-bottom: 0.5rem;"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                            
                            @if($order->delivery_lat && $order->delivery_lng)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->delivery_lat }},{{ $order->delivery_lng }}" 
                                   target="_blank" 
                                   style="display: inline-block; margin-top: 0.5rem; color: #007bff; font-weight: 600; text-decoration: underline;">
                                   &rarr; Open in Google Maps
                                </a>
                            @endif
                        </div>
                    @endif

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

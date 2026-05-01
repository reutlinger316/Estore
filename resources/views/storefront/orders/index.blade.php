@extends('layouts.app')

@section('page_title', 'Branch Orders')
@section('page_subtitle', 'Track delivery and takeaway orders, receipts, and status updates for your branch.')

@section('content')
<div class="page-shell fade-up">
    @if($orders->count())
        <div class="stack-list">
            @foreach($orders as $order)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">Order #{{ $order->id }}</h3>
                            <p>Receipt No: {{ $order->receipt_number ?? 'Not generated yet' }}</p>
                        </div>
                        <span class="badge {{ $order->type === 'takeaway' ? 'badge-info' : 'badge-purple' }}">{{ ucfirst($order->type) }}</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Customer</span><strong>{{ $order->customer->name }}</strong></div>
                        <div class="entity-row"><span>Branch</span><strong>{{ $order->storeFront->name }} - {{ $order->storeFront->branch_name }}</strong></div>
                        <div class="entity-row"><span>Status</span><strong>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong></div>
                        <div class="entity-row"><span>Payment</span><strong>{{ $order->paid_at ? 'Paid' : 'Unpaid' }}</strong></div>
                        <div class="entity-row"><span>Total</span><strong>{{ number_format($order->total_amount, 2) }}</strong></div>
                    </div>

                    @if($order->type === 'delivery')
                        <div class="order-item" style="margin-top:16px;">
                            <strong>Delivery Information</strong><br>
                            Zone: {{ $order->delivery_zone === 'inside' ? 'Inside ' . $order->storeFront->delivery_city : 'Outside ' . $order->storeFront->delivery_city }}<br>
                            Delivery Fee: {{ number_format($order->delivery_fee, 2) }}<br>
                            Phone: {{ $order->delivery_phone }}<br>
                            Address: {{ $order->delivery_address }}<br>
                            @if($order->delivery_lat && $order->delivery_lng)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->delivery_lat }},{{ $order->delivery_lng }}" target="_blank" class="map-link">Open in Google Maps</a>
                            @endif
                        </div>
                    @endif

                    <div class="order-items-list">
                        @foreach($order->orderItems as $orderItem)
                            <div class="order-item">
                                <strong>{{ $orderItem->item->item_name ?? 'Item deleted' }}</strong><br>
                                Qty: {{ $orderItem->quantity }} · Price: {{ number_format($orderItem->price, 2) }}

                                @if($orderItem->is_pre_order)
                                    <div style="margin-top: 8px; padding: 10px; border: 1px solid #facc15; background: #fef9c3; border-radius: 8px;">
                                        <strong style="color:#92400e;">Pre-order: {{ ucfirst($orderItem->pre_order_status) }}</strong>

                                        @if($orderItem->pre_order_available_on)
                                            <p style="margin: 4px 0 0;">Available {{ $orderItem->pre_order_available_on->format('M d, Y') }}</p>
                                        @endif

                                        @if($orderItem->pre_order_note)
                                            <p style="margin: 4px 0 0;">{{ $orderItem->pre_order_note }}</p>
                                        @endif

                                        @if($orderItem->pre_order_status !== 'fulfilled')
                                            <form method="POST" action="{{ route('storefront.preorder.fulfill', $orderItem) }}" style="margin-top: 8px;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Mark Pre-order Fulfilled</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="entity-actions">
                        <a href="{{ route('storefront.receipts.show', $order) }}" class="btn btn-secondary">View Receipt</a>
                    </div>

                    <form method="POST" action="{{ route('storefront.orders.status.update', $order) }}" class="toolbar-row">
                        @csrf
                        <select name="status">
                            @if($order->type === 'takeaway')
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="handed_over" {{ $order->status == 'handed_over' ? 'selected' : '' }}>Handed Over</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            @else
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            @endif
                        </select>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No orders available for your branch yet.</div>
    @endif
</div>
@endsection

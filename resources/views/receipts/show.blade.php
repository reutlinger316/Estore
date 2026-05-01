@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="max-width: 900px; margin: 0 auto 16px auto; padding: 12px 16px; background: #d1fae5; border: 1px solid #10b981; color: #065f46; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    @php
        $itemsSubtotal = $order->orderItems->sum(function ($orderItem) {
            return $orderItem->price * $orderItem->quantity;
        });
    @endphp

    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <div class="card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
                <div>
                    <h2 style="margin: 0 0 8px 0;">Order Receipt</h2>
                    <p style="margin: 4px 0;"><strong>Receipt No:</strong> {{ $order->receipt_number }}</p>
                    <p style="margin: 4px 0;"><strong>Generated At:</strong> {{ $order->receipt_generated_at?->format('d M Y, h:i A') }}</p>
                    <p style="margin: 4px 0;"><strong>Order ID:</strong> #{{ $order->id }}</p>
                </div>

                <div style="text-align: right;">
                    <p style="margin: 4px 0;"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p style="margin: 4px 0;"><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p style="margin: 4px 0;"><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
                </div>
            </div>

            <hr style="margin: 20px 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h3 style="margin-bottom: 10px;">Customer Information</h3>
                    <p style="margin: 4px 0;"><strong>Name:</strong> {{ $order->customer->name }}</p>
                    <p style="margin: 4px 0;"><strong>Email:</strong> {{ $order->customer->email }}</p>

                    @if($order->type === 'delivery')
                        <p style="margin: 4px 0;"><strong>Phone:</strong> {{ $order->delivery_phone }}</p>
                        <p style="margin: 4px 0;"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                        <p style="margin: 4px 0;">
                            <strong>Zone:</strong>
                            {{ $order->delivery_zone === 'inside'
                                ? 'Inside ' . $order->storeFront->delivery_city
                                : 'Outside ' . $order->storeFront->delivery_city }}
                        </p>
                    @endif
                </div>

                <div>
                    <h3 style="margin-bottom: 10px;">Store Information</h3>
                    <p style="margin: 4px 0;"><strong>Store:</strong> {{ $order->storeFront->name }}</p>
                    <p style="margin: 4px 0;"><strong>Branch:</strong> {{ $order->storeFront->branch_name }}</p>
                    <p style="margin: 4px 0;"><strong>Location:</strong> {{ $order->storeFront->location }}</p>
                </div>
            </div>

            <hr style="margin: 20px 0;">

            <h3 style="margin-bottom: 12px;">Ordered Items</h3>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Item</th>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Qty</th>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: right;">Unit Price</th>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: right;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $orderItem)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 10px;">
                                    {{ $orderItem->item->item_name ?? 'Item deleted' }}
                                    @if($orderItem->is_pre_order)
                                        <div style="margin-top: 6px; padding: 6px; border: 1px solid #facc15; background: #fef9c3; border-radius: 6px;">
                                            <strong style="color:#92400e;">Pre-order: {{ ucfirst($orderItem->pre_order_status) }}</strong>
                                            @if($orderItem->pre_order_available_on)
                                                <div>Available {{ $orderItem->pre_order_available_on->format('M d, Y') }}</div>
                                            @endif
                                            @if($orderItem->pre_order_note)
                                                <div>{{ $orderItem->pre_order_note }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                                    {{ $orderItem->quantity }}
                                </td>
                                <td style="border: 1px solid #ddd; padding: 10px; text-align: right;">
                                    {{ number_format($orderItem->price, 2) }}
                                </td>
                                <td style="border: 1px solid #ddd; padding: 10px; text-align: right;">
                                    {{ number_format($orderItem->price * $orderItem->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                <div style="width: 100%; max-width: 320px;">
                    <p style="display: flex; justify-content: space-between; margin: 8px 0;">
                        <span><strong>Items Subtotal:</strong></span>
                        <span>{{ number_format($itemsSubtotal, 2) }}</span>
                    </p>

                    @if($order->type === 'delivery')
                        <p style="display: flex; justify-content: space-between; margin: 8px 0;">
                            <span><strong>Delivery Fee:</strong></span>
                            <span>{{ number_format($order->delivery_fee, 2) }}</span>
                        </p>
                    @endif

                    @if($order->points_redeemed > 0)
                        <p style="display: flex; justify-content: space-between; margin: 8px 0; color: green;">
                            <span><strong>Loyalty Discount ({{ number_format($order->points_discount_percent, 2) }}%):</strong></span>
                            <span>-{{ number_format($order->points_discount_amount, 2) }}</span>
                        </p>
                        <p style="display: flex; justify-content: space-between; margin: 8px 0;">
                            <span><strong>Points Redeemed:</strong></span>
                            <span>{{ $order->points_redeemed }} {{ $order->points_owner_type === 'merchant' ? 'merchant' : 'global' }} points</span>
                        </p>
                    @endif

                    @if($order->global_points_earned > 0 || $order->merchant_points_earned > 0)
                        <p style="display: flex; justify-content: space-between; margin: 8px 0;">
                            <span><strong>Global Points Earned:</strong></span>
                            <span>{{ $order->global_points_earned }}</span>
                        </p>
                        <p style="display: flex; justify-content: space-between; margin: 8px 0;">
                            <span><strong>Merchant Points Earned:</strong></span>
                            <span>{{ $order->merchant_points_earned }}</span>
                        </p>
                    @endif

                    <hr>

                    <p style="display: flex; justify-content: space-between; margin: 8px 0; font-size: 1.1rem;">
                        <span><strong>Grand Total:</strong></span>
                        <span><strong>{{ number_format($order->total_amount, 2) }}</strong></span>
                    </p>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>

                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                    <a href="{{ route('customer.shops.index') }}" class="btn btn-secondary">Continue Shopping</a>
                @elseif(auth()->user()->role === 'storefront')
                    <a href="{{ route('storefront.orders.index') }}" class="btn btn-secondary">Back to Branch Orders</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">My Orders</h2>

        <div class="card" style="margin-bottom: 18px;">
            <form method="GET" action="{{ route('customer.orders.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <input
                    type="text"
                    name="store"
                    value="{{ $storeSearch ?? '' }}"
                    placeholder="Search by store name"
                    style="flex:1; min-width:220px;"
                >

                <button type="submit" class="btn btn-primary">Search</button>

                @if(!empty($storeSearch))
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </form>
        </div>

        <div class="list-block">
            @forelse($orders as $order)
                @php
                    $trackingSteps = $order->type === 'takeaway'
                        ? ['pending', 'accepted', 'handed_over']
                        : ['pending', 'accepted', 'preparing', 'ready', 'delivered'];

                    $currentStepIndex = array_search($order->status, $trackingSteps, true);
                    $isCancelled = $order->status === 'cancelled';
                @endphp

                <div class="card">
                    <h3>Order #{{ $order->id }}</h3>
                    <p><strong>Store:</strong> {{ $order->storeFront->name ?? 'Store deleted' }}{{ $order->storeFront && $order->storeFront->branch_name ? ' - ' . $order->storeFront->branch_name : '' }}</p>
                    <p><strong>Receipt No:</strong> {{ $order->receipt_number ?? 'Not generated yet' }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($order->type) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p><strong>Payment:</strong> {{ $order->paid_at ? 'Paid' : 'Unpaid' }}</p>
                    <p><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}</p>

                    <div style="margin: 18px 0; padding: 14px; border: 1px solid #e5e7eb; border-radius: 12px;">
                        <h4 style="margin-bottom: 14px;">Order Tracker</h4>

                        @if($isCancelled)
                            <div style="padding: 12px; border-radius: 10px; background: #fee2e2; color: #991b1b; font-weight: 600;">
                                This order has been cancelled.
                            </div>
                        @else
                            <div style="display:flex; gap:8px; align-items:flex-start; overflow-x:auto;">
                                @foreach($trackingSteps as $index => $step)
                                    @php
                                        $isCompleted = $currentStepIndex !== false && $index <= $currentStepIndex;
                                        $isCurrent = $currentStepIndex !== false && $index === $currentStepIndex;
                                    @endphp

                                    <div style="display:flex; align-items:center; flex:1; min-width:110px;">
                                        <div style="text-align:center; flex:1;">
                                            <div style="
                                                width:34px;
                                                height:34px;
                                                border-radius:50%;
                                                margin:0 auto 8px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                font-weight:700;
                                                color:white;
                                                background: {{ $isCompleted ? '#16a34a' : '#d1d5db' }};
                                            ">
                                                {{ $isCompleted ? '✓' : $index + 1 }}
                                            </div>

                                            <div style="
                                                font-size:13px;
                                                font-weight:{{ $isCurrent ? '700' : '500' }};
                                                color:{{ $isCompleted ? '#166534' : '#6b7280' }};
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $step)) }}
                                            </div>
                                        </div>

                                        @if(!$loop->last)
                                            <div style="
                                                height:4px;
                                                flex:1;
                                                margin-top:15px;
                                                background: {{ $currentStepIndex !== false && $index < $currentStepIndex ? '#16a34a' : '#d1d5db' }};
                                                border-radius:999px;
                                            "></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

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
                    <p>No orders found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
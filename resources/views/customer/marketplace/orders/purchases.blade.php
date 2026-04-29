@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>My Marketplace Purchases</h2>
            <p>Products you purchased from the customer marketplace.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.my-trades') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Bargains</span>
                <span class="dashboard-action-btn__subtitle">View offers</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="marketplace-product-list">
            @forelse($orders as $order)
                <div class="marketplace-product-card marketplace-product-body">
                    <h3>{{ $order->product->name ?? 'Deleted Product' }}</h3>

                    <p><strong>Seller:</strong> {{ $order->seller->name ?? 'Deleted Seller' }}</p>
                    <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                    <p><strong>Unit Price:</strong> {{ number_format($order->unit_price, 2) }} Tk</p>
                    <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }} Tk</p>
                    <p><strong>Status:</strong> <span class="marketplace-status">{{ ucfirst($order->status) }}</span></p>
                    <p><strong>Purchased At:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            @empty
                <p>You have not purchased any marketplace products yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
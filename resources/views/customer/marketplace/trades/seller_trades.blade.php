@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Buyer Bargains For My Products</h2>
            <p>Review, accept, counter, or reject buyer bargain requests.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.products.my-products') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Products</span>
                <span class="dashboard-action-btn__subtitle">Manage listings</span>
            </a>

            <a href="{{ route('customer.marketplace.sales') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Sales</span>
                <span class="dashboard-action-btn__subtitle">Sold items</span>
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
            @forelse($trades as $trade)
                <div class="marketplace-product-card marketplace-product-body">
                    <h3>{{ $trade->product->name ?? 'Deleted Product' }}</h3>

                    <p><strong>Buyer:</strong> {{ $trade->buyer->name ?? 'Deleted Buyer' }}</p>
                    <p><strong>Quantity:</strong> {{ $trade->quantity }}</p>
                    <p><strong>Original Price:</strong> {{ number_format($trade->original_price, 2) }} Tk</p>
                    <p><strong>Buyer Offer:</strong> {{ number_format($trade->buyer_offer_price, 2) }} Tk</p>

                    @if($trade->seller_counter_price)
                        <p><strong>Your Counter Offer:</strong> {{ number_format($trade->seller_counter_price, 2) }} Tk</p>
                    @endif

                    @if($trade->final_price)
                        <p><strong>Final Price:</strong> {{ number_format($trade->final_price, 2) }} Tk</p>
                    @endif

                    <p><strong>Status:</strong> <span class="marketplace-status">{{ ucfirst($trade->status) }}</span></p>

                    @if($trade->buyer_message)
                        <p><strong>Buyer Message:</strong> {{ $trade->buyer_message }}</p>
                    @endif

                    @if($trade->seller_message)
                        <p><strong>Your Message:</strong> {{ $trade->seller_message }}</p>
                    @endif

                    @if(in_array($trade->status, ['pending', 'countered']))
                        <div class="marketplace-form-grid">
                            <form method="POST" action="{{ route('customer.marketplace.trades.accept', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-primary-btn">Accept Offer</button>
                            </form>

                            <form method="POST" action="{{ route('customer.marketplace.trades.counter', $trade) }}" class="marketplace-form-card">
                                @csrf

                                <label>Counter Offer Price Per Item</label>
                                <input type="number" name="seller_counter_price" step="0.01" min="1" required>

                                <label>Message</label>
                                <textarea name="seller_message" placeholder="Write your counter message..."></textarea>

                                <button type="submit" class="marketplace-primary-btn">Send Counter Offer</button>
                            </form>

                            <form method="POST" action="{{ route('customer.marketplace.trades.reject', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-danger-btn">Reject Offer</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p>No buyers are bargaining for your products yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
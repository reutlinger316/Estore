@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>My Bargains</h2>
            <p>Track bargain requests you sent to sellers.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.purchases') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Purchases</span>
                <span class="dashboard-action-btn__subtitle">Bought items</span>
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

                    <p><strong>Seller:</strong> {{ $trade->seller->name ?? 'Deleted Seller' }}</p>
                    <p><strong>Quantity:</strong> {{ $trade->quantity }}</p>
                    <p><strong>Original Price:</strong> {{ number_format($trade->original_price, 2) }} Tk</p>
                    <p><strong>Your Offer:</strong> {{ number_format($trade->buyer_offer_price, 2) }} Tk</p>

                    @if($trade->seller_counter_price)
                        <p><strong>Seller Counter Offer:</strong> {{ number_format($trade->seller_counter_price, 2) }} Tk</p>
                    @endif

                    @if($trade->final_price)
                        <p><strong>Final Price:</strong> {{ number_format($trade->final_price, 2) }} Tk</p>
                    @endif

                    <p><strong>Status:</strong> <span class="marketplace-status">{{ ucfirst($trade->status) }}</span></p>

                    @if($trade->buyer_message)
                        <p><strong>Your Message:</strong> {{ $trade->buyer_message }}</p>
                    @endif

                    @if($trade->seller_message)
                        <p><strong>Seller Message:</strong> {{ $trade->seller_message }}</p>
                    @endif

                    @if($trade->status === 'countered')
                        <div class="marketplace-form-grid">
                            <form method="POST" action="{{ route('customer.marketplace.trades.accept-counter', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-primary-btn">Accept Counter Offer</button>
                            </form>

                            <form method="POST" action="{{ route('customer.marketplace.trades.cancel', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-danger-btn">Cancel Bargain</button>
                            </form>
                        </div>
                    @elseif($trade->status === 'accepted')
                        <div class="marketplace-form-grid">
                            <form method="POST" action="{{ route('customer.marketplace.trades.complete', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-primary-btn">Complete Payment</button>
                            </form>

                            <form method="POST" action="{{ route('customer.marketplace.trades.cancel', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-danger-btn">Cancel Bargain</button>
                            </form>
                        </div>
                    @elseif(in_array($trade->status, ['pending']))
                        <div class="marketplace-form-grid">
                            <form method="POST" action="{{ route('customer.marketplace.trades.cancel', $trade) }}" class="marketplace-form-card">
                                @csrf
                                <button type="submit" class="marketplace-danger-btn">Cancel Bargain</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p>You have not sent any bargain requests yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
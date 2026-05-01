@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
    <div class="shop-hero">
        <div class="shop-hero-text">
            <h1>Customer Marketplace</h1>
            <p>Browse products, buy directly, or send bargain requests.</p>
        </div>
        <div class="shop-hero-anim">
            <lottie-player src="{{ asset('animations/Trade.json') }}" background="transparent" speed="1" style="width: 160px; height: 160px;" loop autoplay></lottie-player>
        </div>
    </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.account') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Marketplace Account</span>
                <span class="dashboard-action-btn__subtitle">Manage eligibility</span>
            </a>

            <a href="{{ route('customer.marketplace.products.create') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Sell a Product</span>
                <span class="dashboard-action-btn__subtitle">Create a listing</span>
            </a>

            <a href="{{ route('customer.marketplace.products.my-products') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Products</span>
                <span class="dashboard-action-btn__subtitle">Your listings</span>
            </a>

            <a href="{{ route('customer.marketplace.my-trades') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Bargains</span>
                <span class="dashboard-action-btn__subtitle">Offers you sent</span>
            </a>

            <a href="{{ route('customer.marketplace.seller-trades') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Buyer Bargains</span>
                <span class="dashboard-action-btn__subtitle">Offers received</span>
            </a>

            <a href="{{ route('customer.marketplace.purchases') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Purchases</span>
                <span class="dashboard-action-btn__subtitle">Bought items</span>
            </a>

            <a href="{{ route('customer.marketplace.sales') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Sales</span>
                <span class="dashboard-action-btn__subtitle">Sold items</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Available Products</h2>
            <p>Search by product name or generalized terms like PC, computer, laptop, or electronics.</p>
        </div>

        <form method="GET" action="{{ route('customer.marketplace.products.index') }}" class="marketplace-form-card">
            <div class="form-group">
                <label>Search Marketplace</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search by product name or generalized term, e.g. PC, electronics, laptop"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(($categoryFilter ?? '') === $category)>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="button-group" style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary">Search</button>

                @if(!empty($search) || !empty($categoryFilter))
                    <a href="{{ route('customer.marketplace.products.index') }}" class="btn btn-ghost">
                        Clear Search
                    </a>
                @endif
            </div>
        </form>

        <div class="marketplace-product-list">
            @forelse($products as $product)
                <details class="marketplace-product-card">
                    <summary class="marketplace-product-summary">
                        <div class="marketplace-product-summary__left">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="marketplace-product-thumb">
                            @endif

                            <div>
                                <h3>{{ $product->name }}</h3>
                                <p><strong>Seller:</strong> {{ $product->seller->name ?? 'Unknown Seller' }}</p>
                                <p><strong>Category:</strong> {{ $product->category ?? 'Other' }}</p>
                                <p><strong>Price:</strong> {{ number_format($product->price, 2) }} Tk · <strong>Stock:</strong> {{ $product->stock }}</p>
                            </div>
                        </div>

                        <span class="marketplace-product-chevron">Open</span>
                    </summary>

                    <div class="marketplace-product-body">
                        <p>{{ $product->description ?: 'No description provided.' }}</p>

                        @if($product->activeTrade)
                            <p class="marketplace-warning">
                                This item is currently locked because one customer is bargaining for it.
                            </p>
                        @endif

                        @if($product->seller_id !== auth()->id())
                            @if(!$product->activeTrade && $product->stock > 0)
                                <div class="marketplace-form-grid">
                                    <form method="POST" action="{{ route('customer.marketplace.products.buy-now', $product) }}" class="marketplace-form-card">
                                        @csrf

                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="form-control" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Buy Now</button>
                                    </form>

                                    <form method="POST" action="{{ route('customer.marketplace.products.bargain', $product) }}" class="marketplace-form-card">
                                        @csrf

                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Your Offer Price Per Item</label>
                                            <input type="number" name="buyer_offer_price" step="0.01" min="1" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea name="buyer_message" placeholder="Write your bargain message..." class="form-control"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Send Bargain Request</button>
                                    </form>
                                </div>
                            @endif

                            @php
                                $alreadyReported = $product->seller
                                    ? $product->seller->reportsReceived->where('reporter_id', auth()->id())->isNotEmpty()
                                    : false;
                            @endphp

                            @if($alreadyReported)
                                <p class="marketplace-warning">Seller already reported.</p>
                            @elseif($product->seller)
                                <a href="{{ route('customer.marketplace.sellers.report.form', $product->seller) }}" class="marketplace-secondary-btn">
                                    Report Seller
                                </a>
                            @endif
                        @else
                            <p><em>This is your product.</em></p>
                        @endif
                    </div>
                </details>
            @empty
                <p>No marketplace products found.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

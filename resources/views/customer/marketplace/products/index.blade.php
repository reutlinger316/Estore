@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Customer Marketplace</h2>
            <p>Browse products, buy directly, or send bargain requests.</p>
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
            <label>Search Marketplace</label>
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search by product name or generalized term, e.g. PC, electronics, laptop"
            >

            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(($categoryFilter ?? '') === $category)>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="marketplace-primary-btn">Search</button>

            @if(!empty($search) || !empty($categoryFilter))
                <a href="{{ route('customer.marketplace.products.index') }}" class="marketplace-secondary-btn">
                    Clear Search
                </a>
            @endif
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

                                        <label>Quantity</label>
                                        <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" required>

                                        <button type="submit" class="marketplace-primary-btn">Buy Now</button>
                                    </form>

                                    <form method="POST" action="{{ route('customer.marketplace.products.bargain', $product) }}" class="marketplace-form-card">
                                        @csrf

                                        <label>Quantity</label>
                                        <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" required>

                                        <label>Your Offer Price Per Item</label>
                                        <input type="number" name="buyer_offer_price" step="0.01" min="1" required>

                                        <label>Message</label>
                                        <textarea name="buyer_message" placeholder="Write your bargain message..."></textarea>

                                        <button type="submit" class="marketplace-primary-btn">Send Bargain Request</button>
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

@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>My Marketplace Products</h2>
            <p>Manage your marketplace listings.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.products.create') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Add New Product</span>
                <span class="dashboard-action-btn__subtitle">Create listing</span>
            </a>

            <a href="{{ route('customer.marketplace.seller-trades') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Buyer Bargains</span>
                <span class="dashboard-action-btn__subtitle">Offers received</span>
            </a>

            <a href="{{ route('customer.marketplace.sales') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Sales</span>
                <span class="dashboard-action-btn__subtitle">Sold items</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Your Listings</h2>
            <p>Products you have added to the marketplace.</p>
        </div>

        <div class="marketplace-product-list">
            @forelse($products as $product)
                <div class="marketplace-product-card marketplace-product-body">
                    <h3>{{ $product->name }}</h3>
                    <p><strong>Category:</strong> {{ $product->category ?? 'Other' }}</p>
                    <p><strong>Price:</strong> {{ number_format($product->price, 2) }} Tk</p>
                    <p><strong>Stock:</strong> {{ $product->stock }}</p>
                    <p><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                    <p>{{ $product->description ?: 'No description provided.' }}</p>

                    @if($product->activeTrade)
                        <p class="marketplace-warning">
                            One customer is currently bargaining for this item.
                        </p>
                    @endif
                </div>
            @empty
                <p>You have not listed any marketplace products yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

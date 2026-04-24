@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">

    <section class="customer-hero">
        <div class="customer-hero__content">
            <div class="customer-hero__badge">Customer Dashboard</div>
            <h1 class="customer-hero__title">Welcome, {{ auth()->user()->name }}</h1>

            <div class="customer-hero__meta">
                <div class="hero-meta-card">
                    <span class="hero-meta-label">Role</span>
                    <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                </div>

                <div class="hero-meta-card">
                    <span class="hero-meta-label">Balance</span>
                    <strong>{{ number_format(auth()->user()->balance, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="customer-hero__orb"></div>
    </section>

    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Quick Actions</h2>
            <p>Use these shortcuts to manage your shopping and account faster.</p>
        </div>

        <div class="dashboard-action-grid">
            <a href="{{ route('customer.shops.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Browse Shops</span>
                <span class="dashboard-action-btn__subtitle">Explore storefronts</span>
            </a>

            <a href="{{ route('customer.cart.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Cart</span>
                <span class="dashboard-action-btn__subtitle">Review selected items</span>
            </a>

            <a href="{{ route('customer.orders.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Orders</span>
                <span class="dashboard-action-btn__subtitle">Track current purchases</span>
            </a>

            <a href="{{ route('customer.creditcards.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Manage Credit Cards</span>
                <span class="dashboard-action-btn__subtitle">Saved payment methods</span>
            </a>

            <a href="{{ route('customer.funds.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Manage Funds</span>
                <span class="dashboard-action-btn__subtitle">Wallet and balance</span>
            </a>
        </div>
    </section>

    <section class="marketplace-box">
        <div class="marketplace-box__header">
            <h2>Marketplace</h2>
            <p>Access your marketplace tools and product area.</p>
        </div>

        <div class="marketplace-button-grid">
            <a href="{{ route('customer.marketplace.account') }}" class="marketplace-glow-btn">
                <span class="marketplace-glow-btn__icon">★</span>
                <span class="marketplace-glow-btn__text">
                    <strong>Marketplace Account</strong>
                    <small>Manage your marketplace profile</small>
                </span>
            </a>

            <a href="{{ route('customer.marketplace.products.index') }}" class="marketplace-glow-btn marketplace-glow-btn--alt">
                <span class="marketplace-glow-btn__icon">🛍</span>
                <span class="marketplace-glow-btn__text">
                    <strong>Customer Marketplace</strong>
                    <small>Browse marketplace products</small>
                </span>
            </a>
        </div>
    </section>

</div>
@endsection
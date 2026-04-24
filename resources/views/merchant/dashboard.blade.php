@extends('layouts.app')

@section('page_title', 'Merchant Dashboard')
@section('page_subtitle', 'Manage storefronts, promotions, stock flow, and performance in one place.')

@section('content')
<div class="page-shell fade-up">
    <section class="role-hero role-hero--merchant">
        <div class="role-hero__content">
            <div class="role-hero__eyebrow">Merchant Overview</div>
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <p>Run your storefront operations faster with clearer actions and performance visibility.</p>

            <div class="role-hero__stats">
                <div class="role-stat">
                    <span class="role-stat__label">Role</span>
                    <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                </div>
                <div class="role-stat">
                    <span class="role-stat__label">Wallet Balance</span>
                    <strong>{{ number_format(auth()->user()->balance, 2) }}</strong>
                </div>
                <div class="role-stat">
                    <span class="role-stat__label">Low Stock Alerts</span>
                    <strong>{{ $lowStockItems->count() }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Quick Actions</h2>
                <p>Access your most common merchant tools instantly.</p>
            </div>
        </div>

        <div class="action-grid">
            <a href="{{ route('merchant.storefronts.index') }}" class="action-tile">
                <span class="action-tile__title">Manage Storefronts</span>
                <span class="action-tile__subtitle">Create and control branches</span>
            </a>
            <a href="{{ route('merchant.discounts.index') }}" class="action-tile action-tile--alt">
                <span class="action-tile__title">Discounts</span>
                <span class="action-tile__subtitle">Apply item and branch offers</span>
            </a>
            <a href="{{ route('merchant.restock-requests.index') }}" class="action-tile">
                <span class="action-tile__title">Restock Requests</span>
                <span class="action-tile__subtitle">Review branch stock requests</span>
            </a>
            <a href="{{ route('merchant.performance.index') }}" class="action-tile action-tile--dark">
                <span class="action-tile__title">Performance</span>
                <span class="action-tile__subtitle">See sales, reviews, and ratings</span>
            </a>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Low Stock Alerts</h2>
                <p>Items that have reached or dropped below their configured threshold.</p>
            </div>
        </div>

        @if($lowStockItems->count())
            <div class="entity-grid">
                @foreach($lowStockItems as $item)
                    <div class="entity-card">
                        <div class="entity-card__header">
                            <h3 class="entity-card__title">{{ $item->item_name }}</h3>
                            <span class="badge badge-danger">Low Stock</span>
                        </div>

                        <div class="entity-card__meta">
                            <div class="entity-row"><span>Storefront</span><strong>{{ $item->storeFront->name }} - {{ $item->storeFront->branch_name }}</strong></div>
                            <div class="entity-row"><span>Stock</span><strong>{{ $item->stock_quantity }}</strong></div>
                            <div class="entity-row"><span>Threshold</span><strong>{{ $item->low_stock_threshold }}</strong></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">No low stock alerts right now.</div>
        @endif
    </section>
</div>
@endsection

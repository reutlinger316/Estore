@extends('layouts.app')

@section('page_title', 'Storefront Dashboard')
@section('page_subtitle', 'Monitor branch health, stock levels, requests, and order flow.')

@section('content')
<div class="page-shell fade-up">
    <section class="role-hero role-hero--storefront">
        <div class="role-hero__content">
            <div class="role-hero__eyebrow">Storefront Overview</div>
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <p>Keep your assigned branches running smoothly with clearer branch and stock visibility.</p>

            <div class="role-hero__stats">
                <div class="role-stat">
                    <span class="role-stat__label">Role</span>
                    <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                </div>
                <div class="role-stat">
                    <span class="role-stat__label">Assigned Branches</span>
                    <strong>{{ $branches->count() }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Quick Actions</h2>
                <p>Use these shortcuts to stay on top of branch operations.</p>
            </div>
        </div>

        <div class="action-grid">
            <a href="{{ route('storefront.branch-requests') }}" class="action-tile">
                <span class="action-tile__title">Branch Requests</span>
                <span class="action-tile__subtitle">Accept or reject new assignments</span>
            </a>
            <a href="{{ route('storefront.orders.index') }}" class="action-tile action-tile--alt">
                <span class="action-tile__title">Manage Orders</span>
                <span class="action-tile__subtitle">Track order status and receipts</span>
            </a>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Branch Balances & Items</h2>
                <p>See all assigned branches, balances, and stock thresholds at a glance.</p>
            </div>
        </div>

        @if($branches->count())
            <div class="entity-grid">
                @foreach($branches as $branch)
                    <div class="entity-card">
                        <div class="entity-card__header">
                            <div>
                                <h3 class="entity-card__title">{{ $branch->name }} - {{ $branch->branch_name }}</h3>
                                <p>{{ $branch->location }}</p>
                            </div>
                            <span class="badge badge-success">{{ number_format($branch->balance, 2) }}</span>
                        </div>

                        <div class="branch-item-list">
                            @forelse($branch->items as $item)
                                <div class="branch-item">
                                    <strong>{{ $item->item_name }}</strong><br>
                                    <span>Stock: {{ $item->stock_quantity }} / Threshold: {{ $item->low_stock_threshold }}</span>
                                </div>
                            @empty
                                <div class="branch-item">No items found for this branch.</div>
                            @endforelse
                        </div>

                        <div class="entity-actions">
                            <a href="{{ route('storefront.restock-requests.create', $branch) }}" class="btn btn-primary">Request Restock</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">No branch assigned yet.</div>
        @endif
    </section>
</div>
@endsection

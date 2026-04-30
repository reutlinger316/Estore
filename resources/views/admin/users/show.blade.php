@extends('layouts.app')

@section('page_title', 'User Activity History')
@section('page_subtitle', 'View profile summary, report history, and recent activity for this user.')

@section('content')
<section class="panel">
    <div class="section-heading">
        <div>
            <h3>{{ $user->name }}</h3>
            <p>{{ $user->email }} · {{ ucfirst($user->role) }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Back to Users</a>
    </div>

    <div class="card-grid">
        <article class="stat-card"><p>Reports Received</p><h3>{{ $user->reports_received_count }}</h3></article>
        <article class="stat-card"><p>Reports Made</p><h3>{{ $user->reports_made_count }}</h3></article>
        <article class="stat-card"><p>Marketplace Products</p><h3>{{ $user->marketplace_products_count }}</h3></article>
        <article class="stat-card"><p>Marketplace Trades</p><h3>{{ $user->marketplace_trades_as_buyer_count + $user->marketplace_trades_as_seller_count }}</h3></article>
        <article class="stat-card"><p>Marketplace Orders</p><h3>{{ $user->marketplace_purchases_count + $user->marketplace_sales_count }}</h3></article>
        <article class="stat-card"><p>Status</p><h3>{{ $user->status ? 'Active' : 'Banned' }}</h3></article>
    </div>
</section>

<section class="panel" style="margin-top:24px;">
    <div class="section-heading">
        <div>
            <h3>User Reports</h3>
            <p>Reports submitted against this user, sorted newest first.</p>
        </div>
    </div>

    @if($reportsReceived->count())
        <div class="entity-grid">
            @foreach($reportsReceived as $report)
                <article class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">Reported by {{ optional($report->reporter)->name ?? 'Deleted User' }}</h3>
                            <p>{{ optional($report->reporter)->email }}</p>
                        </div>
                        <span class="badge badge-warning">{{ $report->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <p style="margin-top:12px;">{{ $report->reason }}</p>
                </article>
            @endforeach
        </div>
    @else
        <div class="empty-state">No reports against this user.</div>
    @endif
</section>

<section class="panel" style="margin-top:24px;">
    <div class="section-heading">
        <div>
            <h3>Recent Activity</h3>
            <p>Latest available actions from orders, marketplace records, reports, reviews, transactions, and storefront records.</p>
        </div>
    </div>

    @if($activities->count())
        <div class="entity-grid">
            @foreach($activities as $activity)
                <article class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $activity['type'] }}</h3>
                            <p>{{ $activity['description'] }}</p>
                        </div>
                        <span class="badge badge-success">{{ $activity['date']->format('M d, Y h:i A') }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="empty-state">No activity found for this user yet.</div>
    @endif
</section>
@endsection

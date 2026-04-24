@extends('layouts.app')

@section('page_title', 'Merchant Storefronts')
@section('page_subtitle', 'Inspect all storefront branches that belong to a specific merchant.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>{{ $merchant->name }}</h2>
                <p>{{ $merchant->email }}</p>
            </div>
            <a href="{{ route('admin.users.merchants') }}" class="btn btn-ghost">Back to Merchants</a>
        </div>
    </section>

    @if($storeFronts->count())
        <div class="entity-grid">
            @foreach($storeFronts as $storeFront)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $storeFront->name }}</h3>
                            <p>{{ $storeFront->branch_name }}</p>
                        </div>
                        <span class="badge {{ $storeFront->confirmation_status === 'approved' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($storeFront->confirmation_status) }}</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Location</span><strong>{{ $storeFront->location }}</strong></div>
                        <div class="entity-row"><span>Delivery City</span><strong>{{ $storeFront->delivery_city }}</strong></div>
                        <div class="entity-row"><span>Status</span><strong>{{ $storeFront->status }}</strong></div>
                        <div class="entity-row"><span>Balance</span><strong>{{ number_format($storeFront->balance, 2) }}</strong></div>
                        @if($storeFront->storeAccount)
                            <div class="entity-row"><span>Storefront User</span><strong>{{ $storeFront->storeAccount->name }} ({{ $storeFront->storeAccount->email }})</strong></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No storefronts found under this merchant.</div>
    @endif
</div>
@endsection

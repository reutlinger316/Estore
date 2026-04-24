@extends('layouts.app')

@section('page_title', 'My Storefronts')
@section('page_subtitle', 'Create, review, and manage every storefront branch under your account.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Search & Create</h2>
                <p>Find branches quickly or create a new storefront.</p>
            </div>
            <a href="{{ route('merchant.storefronts.create') }}" class="btn btn-primary">Create Storefront</a>
        </div>

        <div class="filter-row">
            <form method="GET" action="{{ route('merchant.storefronts.index') }}">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by store, branch or location">
                <button type="submit" class="btn btn-primary">Search</button>
                @if(!empty($search))
                    <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </form>
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
                        <span class="badge {{ $storeFront->status ? 'badge-success' : 'badge-warning' }}">{{ $storeFront->status ? 'Active' : 'Inactive' }}</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Location</span><strong>{{ $storeFront->location }}</strong></div>
                        <div class="entity-row"><span>Delivery City</span><strong>{{ $storeFront->delivery_city }}</strong></div>
                        <div class="entity-row"><span>Inside Fee</span><strong>{{ number_format($storeFront->inside_delivery_fee, 2) }}</strong></div>
                        <div class="entity-row"><span>Outside Fee</span><strong>{{ number_format($storeFront->outside_delivery_fee, 2) }}</strong></div>
                        <div class="entity-row"><span>Combos</span><strong>{{ $storeFront->allow_combos ? 'Enabled' : 'Disabled' }}</strong></div>
                        <div class="entity-row"><span>Balance</span><strong>{{ number_format($storeFront->balance, 2) }}</strong></div>
                        <div class="entity-row"><span>Storefront Account</span><strong>{{ $storeFront->storeAccount?->name ?? 'Not assigned' }}</strong></div>
                        <div class="entity-row"><span>Confirmation</span><strong>{{ ucfirst($storeFront->confirmation_status) }}</strong></div>
                    </div>

                    <div class="entity-actions">
                        <a href="{{ route('merchant.items.index', $storeFront) }}" class="btn btn-primary">Manage Items</a>
                        <form method="POST" action="{{ route('merchant.storefronts.toggle-combos', $storeFront) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-ghost">{{ $storeFront->allow_combos ? 'Disable Combos' : 'Enable Combos' }}</button>
                        </form>
                        <form method="POST" action="{{ route('merchant.storefronts.transfer-balance', $storeFront) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('Transfer this branch balance to merchant balance?')">Transfer Balance</button>
                        </form>
                        <form method="POST" action="{{ route('merchant.storefronts.destroy', $storeFront) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-soft" onclick="return confirm('Are you sure you want to delete this storefront?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No storefronts created yet.</div>
    @endif
</div>
@endsection

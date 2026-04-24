@extends('layouts.app')

@section('page_title', 'Manage Discounts')
@section('page_subtitle', 'Apply global, branch-level, and item-level discounts from one place.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Search Branches</h2>
                <p>Locate a branch to apply or review discounts.</p>
            </div>
        </div>
        <div class="filter-row">
            <form method="GET" action="{{ route('merchant.discounts.index') }}">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search branches by name or location">
                <button type="submit" class="btn btn-primary">Search</button>
                @if(!empty($search) || !empty($selectedBranchId))
                    <a href="{{ route('merchant.discounts.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </form>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header"><div><h2>Global Discount</h2><p>Apply a promotional discount to all your branches at once.</p></div></div>
        <form method="POST" action="{{ route('merchant.discounts.global.update') }}" class="form-grid">
            @csrf
            <div class="form-group">
                <label>Discount for all branches (%)</label>
                <input type="number" step="0.01" name="discount" min="0">
            </div>
            <div class="form-group actions" style="align-items:end; margin-top:30px;">
                <button type="submit" class="btn btn-primary">Apply Global Discount</button>
            </div>
        </form>
    </section>

    <section class="section-card">
        <div class="section-header"><div><h2>Your Branches</h2><p>Select a branch to manage its item discounts.</p></div></div>
        @if($storeFronts->count())
            <div class="entity-grid">
                @foreach($storeFronts as $storeFront)
                    <a href="{{ route('merchant.discounts.index', ['branch' => $storeFront->id, 'search' => !empty($search) ? $search : null]) }}" class="entity-card">
                        <div class="entity-card__header">
                            <h3 class="entity-card__title">{{ $storeFront->name }} - {{ $storeFront->branch_name }}</h3>
                            @if($selectedStoreFront && $selectedStoreFront->id === $storeFront->id)
                                <span class="badge badge-purple">Selected</span>
                            @endif
                        </div>
                        <p>{{ $storeFront->location }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">No storefronts found.</div>
        @endif
    </section>

    @if($selectedStoreFront)
        <section class="section-card">
            <div class="section-header">
                <div>
                    <h2>Selected Branch: {{ $selectedStoreFront->name }} - {{ $selectedStoreFront->branch_name }}</h2>
                    <p>Apply a branch-wide discount and fine tune discounts item by item.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('merchant.discounts.storefronts.update', $selectedStoreFront) }}" class="form-grid" style="margin-bottom: 24px;">
                @csrf
                <div class="form-group">
                    <label>Branch Discount (%)</label>
                    <input type="number" step="0.01" name="discount" min="0">
                </div>
                <div class="form-group actions" style="align-items:end; margin-top:30px;">
                    <button type="submit" class="btn btn-primary">Apply to This Branch</button>
                </div>
            </form>

            @if($selectedStoreFront->items->count())
                <div class="stack-list">
                    @foreach($selectedStoreFront->items as $item)
                        <div class="entity-card">
                            <div class="entity-card__header">
                                <div>
                                    <h3 class="entity-card__title">{{ $item->item_name }}</h3>
                                    <p>Current Discount: {{ number_format($item->discount, 2) }}%</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('merchant.discounts.items.update', $item) }}" class="toolbar-row">
                                @csrf
                                <input type="number" step="0.01" name="discount" min="0" value="{{ $item->discount }}">
                                <button type="submit" class="btn btn-primary">Update Item Discount</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">No items in this branch.</div>
            @endif
        </section>
    @else
        <div class="empty-state">Select a branch above to view and manage its item discounts.</div>
    @endif
</div>
@endsection

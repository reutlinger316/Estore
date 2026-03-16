@extends('layouts.app')

@section('content')
    <h1>Manage Discounts</h1>

    <form method="GET" action="{{ route('merchant.discounts.index') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search branches by name or location">
        <button type="submit">Search</button>

        @if(!empty($search) || !empty($selectedBranchId))
            <a href="{{ route('merchant.discounts.index') }}">
                <button type="button">Clear</button>
            </a>
        @endif
    </form>

    <h2>Global Discount</h2>
    <form method="POST" action="{{ route('merchant.discounts.global.update') }}" style="margin-bottom: 20px;">
        @csrf
        <label>Discount for all branches:</label>
        <input type="number" step="0.01" name="discount" min="0">
        <button type="submit">Apply Global Discount</button>
    </form>

    <hr>

    <h2>Your Branches</h2>

    @forelse($storeFronts as $storeFront)
        <div style="margin-bottom: 12px;">
            <a href="{{ route('merchant.discounts.index', ['branch' => $storeFront->id, 'search' => !empty($search) ? $search : null]) }}">
                <strong>{{ $storeFront->name }} - {{ $storeFront->branch_name }}</strong>
            </a>
            <br>
            <small>Location: {{ $storeFront->location }}</small>
        </div>
    @empty
        <p>No storefronts found.</p>
    @endforelse

    <hr>

    @if($selectedStoreFront)
        <h2>Selected Branch: {{ $selectedStoreFront->name }} - {{ $selectedStoreFront->branch_name }}</h2>

        <form method="POST" action="{{ route('merchant.discounts.storefronts.update', $selectedStoreFront) }}" style="margin-bottom: 20px;">
            @csrf
            <label>Branch Discount:</label>
            <input type="number" step="0.01" name="discount" min="0">
            <button type="submit">Apply to This Branch</button>
        </form>

        <h3>Items</h3>

        @forelse($selectedStoreFront->items as $item)
            <div style="margin-bottom: 18px; padding-left: 12px; border-left: 3px solid #ccc;">
                <p>
                    <strong>{{ $item->item_name }}</strong>
                    | Current Discount: {{ $item->discount }}
                </p>

                <form method="POST" action="{{ route('merchant.discounts.items.update', $item) }}">
                    @csrf
                    <input type="number" step="0.01" name="discount" min="0" value="{{ $item->discount }}">
                    <button type="submit">Update Item Discount</button>
                </form>
            </div>
        @empty
            <p>No items in this branch.</p>
        @endforelse
    @else
        <p>Select a branch above to view and manage its item discounts.</p>
    @endif
@endsection

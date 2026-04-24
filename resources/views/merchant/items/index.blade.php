@extends('layouts.app')

@section('page_title', 'Storefront Items')
@section('page_subtitle', 'Manage items, stock, discounts, and pre-order settings for this branch.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>{{ $storeFront->name }} - {{ $storeFront->branch_name }}</h2>
                <p>Review all items for this storefront and keep stock accurate.</p>
            </div>
            <div class="actions" style="margin-top:0;">
                <a href="{{ route('merchant.items.create', $storeFront) }}" class="btn btn-primary">Add New Item</a>
                <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-ghost">Back to Storefronts</a>
            </div>
        </div>
    </section>

    @if($items->count())
        <div class="entity-grid">
            @foreach($items as $item)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $item->item_name }}</h3>
                            <p>{{ $item->description }}</p>
                        </div>
                        @if($item->isLowStock())
                            <span class="badge badge-danger">Low Stock</span>
                        @elseif($item->is_pre_order)
                            <span class="badge badge-info">Pre-order</span>
                        @endif
                    </div>

                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="media-thumb">
                    @endif

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Price</span><strong>{{ number_format($item->price, 2) }}</strong></div>
                        <div class="entity-row"><span>Stock</span><strong>{{ $item->stock_quantity }}</strong></div>
                        <div class="entity-row"><span>Low Stock Threshold</span><strong>{{ $item->low_stock_threshold }}</strong></div>
                        <div class="entity-row"><span>Discount</span><strong>{{ number_format($item->discount, 2) }}%</strong></div>
                        <div class="entity-row"><span>Pre-order</span><strong>{{ $item->is_pre_order ? 'Yes' : 'No' }}</strong></div>
                    </div>

                    <div class="entity-actions">
                        <a href="{{ route('merchant.items.edit', ['storeFront' => $storeFront, 'item' => $item]) }}" class="btn btn-primary">Edit</a>
                        <form method="POST" action="{{ route('merchant.items.destroy', ['storeFront' => $storeFront, 'item' => $item]) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-soft" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No items added yet.</div>
    @endif
</div>
@endsection

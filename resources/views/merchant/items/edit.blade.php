@extends('layouts.app')

@section('page_title', 'Edit Item')
@section('page_subtitle', 'Update pricing, stock, discount, and media for this storefront item.')

@section('content')
<div class="page-shell fade-up">
    <section class="form-shell">
        <div class="section-header">
            <div>
                <h2>Edit {{ $item->item_name }}</h2>
                <p>Keep this item accurate and up to date.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('merchant.items.update', ['storeFront' => $storeFront, 'item' => $item]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" value="{{ $item->item_name }}">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="{{ $item->price }}">
                </div>
                <div class="form-group full-span">
                    <label>Description</label>
                    <textarea name="description" rows="4">{{ $item->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="media-thumb">
                    @else
                        <div class="empty-state" style="padding:18px;">No image uploaded</div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ $item->stock_quantity }}">
                </div>
                <div class="form-group">
                    <label>Low Stock Alert Threshold</label>
                    <input type="number" name="low_stock_threshold" min="1" value="{{ $item->low_stock_threshold }}">
                </div>
                <div class="form-group">
                    <label>Discount (%)</label>
                    <input type="number" step="0.01" name="discount" value="{{ $item->discount }}">
                </div>
                <div class="form-group full-span">
                    <label class="checkbox-box">
                        <input type="checkbox" name="is_pre_order" value="1" {{ old('is_pre_order', $item->is_pre_order) ? 'checked' : '' }}>
                        <span>
                            <strong>Is Pre-order</strong><br>
                            <small>Keep this enabled if customers can place advance orders.</small>
                        </span>
                    </label>
                </div>
                <div class="form-group">
                    <label>Pre-order Available On</label>
                    <input type="date" name="pre_order_available_on" value="{{ old('pre_order_available_on', $item->pre_order_available_on ? $item->pre_order_available_on->format('Y-m-d') : '') }}">
                    <small>Optional. Customers will see this expected availability date.</small>
                </div>
                <div class="form-group full-span">
                    <label>Pre-order Note</label>
                    <textarea name="pre_order_note" rows="3" placeholder="Example: Ships after Eid / Available next week">{{ old('pre_order_note', $item->pre_order_note) }}</textarea>
                    <small>Optional. Add shipping or fulfillment information for customers.</small>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Update Item</button>
                <a href="{{ route('merchant.items.index', $storeFront) }}" class="btn btn-ghost">Back to Items</a>
            </div>
        </form>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('page_title', 'Add Item')
@section('page_subtitle', 'Create a new item for this storefront with pricing, stock, and optional pre-order.')

@section('content')
<div class="page-shell fade-up">
    <section class="form-shell">
        <div class="section-header">
            <div>
                <h2>Add Item to {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h2>
                <p>Provide the item details below.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('merchant.items.store', $storeFront) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price">
                </div>
                <div class="form-group full-span">
                    <label>Description</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Item Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity">
                </div>
                <div class="form-group">
                    <label>Low Stock Alert Threshold</label>
                    <input type="number" name="low_stock_threshold" min="1" value="3">
                </div>
                <div class="form-group">
                    <label>Discount (%)</label>
                    <input type="number" step="0.01" name="discount" value="0">
                </div>
                <div class="form-group full-span">
                    <label class="checkbox-box">
                        <input type="checkbox" name="is_pre_order" value="1">
                        <span>
                            <strong>Is Pre-order</strong><br>
                            <small>Enable this if customers can order before the item is in stock.</small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Create Item</button>
                <a href="{{ route('merchant.items.index', $storeFront) }}" class="btn btn-ghost">Back to Items</a>
            </div>
        </form>
    </section>
</div>
@endsection

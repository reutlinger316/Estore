@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Sell a Marketplace Product</h2>
            <p>Add a product that other customers can buy or bargain for.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.products.my-products') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Products</span>
                <span class="dashboard-action-btn__subtitle">Manage listings</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('customer.marketplace.products.store') }}" enctype="multipart/form-data" class="marketplace-form-card">
            @csrf

            <label>Product Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label>General Category</label>
            <select name="category" required>
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(old('category') === $category)>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <label>Description</label>
            <textarea name="description" placeholder="Write product description...">{{ old('description') }}</textarea>

            <label>Price</label>
            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required>

            <label>Stock</label>
            <input type="number" name="stock" min="1" value="{{ old('stock', 1) }}" required>

            <label>Image</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit" class="marketplace-primary-btn">Create Product</button>
        </form>
    </section>
</div>
@endsection

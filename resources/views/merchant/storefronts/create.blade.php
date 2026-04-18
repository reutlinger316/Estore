@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Create StoreFront</h2>

        <form method="POST" action="{{ route('merchant.storefronts.store') }}" style="max-width: 700px;">
            @csrf

            <div class="mb-3">
                <label>Store Name</label>
                <input type="text" name="name" value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Branch Name</label>
                <input type="text" name="branch_name" value="{{ old('branch_name') }}">
            </div>

            <div class="mb-3">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}">
            </div>

            <div class="mb-3">
                <label>Delivery City/Area Name</label>
                <input type="text" name="delivery_city" value="{{ old('delivery_city') }}" placeholder="Example: Dhaka">
            </div>

            <div class="mb-3">
                <label>Inside Delivery Fee</label>
                <input type="number" step="0.01" name="inside_delivery_fee" value="{{ old('inside_delivery_fee') }}" placeholder="Example: 80">
            </div>

            <div class="mb-3">
                <label>Outside Delivery Fee</label>
                <input type="number" step="0.01" name="outside_delivery_fee" value="{{ old('outside_delivery_fee') }}" placeholder="Example: 150">
            </div>

            <div class="mb-3">
                <label>Assign StoreFront Account Email</label>
                <input type="email" name="store_account_email" value="{{ old('store_account_email') }}" placeholder="Enter StoreFront account email">
            </div>

            <div class="mb-3">
                <label>
                    <input type="checkbox" name="allow_combos" value="1" {{ old('allow_combos') ? 'checked' : '' }}>
                    Allow customer combos for this storefront
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Create StoreFront</button>
        </form>

        <div class="actions">
            <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-primary">Back to list</a>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('page_title', 'Create Storefront')
@section('page_subtitle', 'Set up a new storefront branch with delivery fees, assignment, and combo settings.')

@section('content')
<div class="page-shell fade-up">
    <section class="form-shell">
        <div class="section-header">
            <div>
                <h2>Storefront Details</h2>
                <p>Fill in the branch information below.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('merchant.storefronts.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Store Name</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label>Branch Name</label>
                    <input type="text" name="branch_name" value="{{ old('branch_name') }}">
                </div>
                <div class="form-group full-span">
                    <label>Location</label>
                    <input type="text" name="location" value="{{ old('location') }}">
                </div>
                <div class="form-group">
                    <label>Delivery City / Area</label>
                    <input type="text" name="delivery_city" value="{{ old('delivery_city') }}" placeholder="Example: Dhaka">
                </div>
                <div class="form-group">
                    <label>Assign Storefront Account Email</label>
                    <input type="email" name="store_account_email" value="{{ old('store_account_email') }}" placeholder="storefront@example.com">
                </div>
                <div class="form-group">
                    <label>Inside Delivery Fee</label>
                    <input type="number" step="0.01" name="inside_delivery_fee" value="{{ old('inside_delivery_fee') }}" placeholder="80">
                </div>
                <div class="form-group">
                    <label>Outside Delivery Fee</label>
                    <input type="number" step="0.01" name="outside_delivery_fee" value="{{ old('outside_delivery_fee') }}" placeholder="150">
                </div>
                <div class="form-group full-span">
                    <label class="checkbox-box">
                        <input type="checkbox" name="allow_combos" value="1" {{ old('allow_combos') ? 'checked' : '' }}>
                        <span>
                            <strong>Allow customer combos for this storefront</strong><br>
                            <small>Enable custom combo purchasing for customers at this branch.</small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Create Storefront</button>
                <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-ghost">Back to List</a>
            </div>
        </form>
    </section>
</div>
@endsection

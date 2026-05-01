@extends('layouts.app')

@section('page_title', 'Merchant Loyalty Points')
@section('page_subtitle', 'Configure loyalty points for your linked storefront branches.')

@section('content')
    <div class="container">
        <div class="card">
            <h3>Your Linked Storefront Branches</h3>
            @forelse($storefronts as $storefront)
                <p>{{ $storefront->name }} - {{ $storefront->branch_name }}</p>
            @empty
                <p>No storefront branches are linked with your merchant account yet.</p>
            @endforelse
        </div>

        <div class="card" style="margin-top:20px;">
            <h3>Merchant Point Earning Rate</h3>
            <p>Applies only to purchases from your linked storefront branches.</p>

            <form method="POST" action="{{ route('merchant.loyalty-points.setting.update') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:6px;">Amount Per 1 Point</label>
                    <input type="number" step="0.01" min="0.01" name="amount_per_point" value="{{ old('amount_per_point', $setting->amount_per_point) }}" style="width:100%; padding:8px;">
                </div>

                <label style="display:block; margin-bottom:12px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                    Active
                </label>

                <button type="submit" class="btn btn-primary">Save Earning Rate</button>
            </form>
        </div>

        <div class="card" style="margin-top:20px;">
            <h3>Create Merchant Redeem Rule</h3>
            <form method="POST" action="{{ route('merchant.loyalty-points.rules.store') }}">
                @csrf
                <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:6px;">Points Required</label>
                        <input type="number" min="1" name="points_required" value="{{ old('points_required') }}" style="width:100%; padding:8px;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:bold; margin-bottom:6px;">Discount Percent</label>
                        <input type="number" step="0.01" min="0.01" max="100" name="discount_percent" value="{{ old('discount_percent') }}" style="width:100%; padding:8px;">
                    </div>
                </div>

                <label style="display:block; margin:12px 0;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active
                </label>

                <button type="submit" class="btn btn-primary">Add Rule</button>
            </form>
        </div>

        <div class="card" style="margin-top:20px;">
            <h3>Merchant Redeem Rules</h3>

            @forelse($rules as $rule)
                <form method="POST" action="{{ route('merchant.loyalty-points.rules.update', $rule) }}" style="border:1px solid #ddd; padding:12px; border-radius:8px; margin-bottom:12px;">
                    @csrf
                    @method('PUT')
                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:6px;">Points Required</label>
                            <input type="number" min="1" name="points_required" value="{{ $rule->points_required }}" style="width:100%; padding:8px;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:6px;">Discount Percent</label>
                            <input type="number" step="0.01" min="0.01" max="100" name="discount_percent" value="{{ $rule->discount_percent }}" style="width:100%; padding:8px;">
                        </div>
                    </div>

                    <label style="display:block; margin:12px 0;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $rule->is_active ? 'checked' : '' }}>
                        Active
                    </label>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>

                <form method="POST" action="{{ route('merchant.loyalty-points.rules.destroy', $rule) }}" onsubmit="return confirm('Delete this rule?')" style="margin-top:-8px; margin-bottom:16px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Rule</button>
                </form>
            @empty
                <p>No merchant redeem rules yet.</p>
            @endforelse
        </div>
    </div>
@endsection

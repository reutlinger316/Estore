@extends('layouts.app')

@section('page_title', 'Global Loyalty Points')
@section('page_subtitle', 'Configure app-wide point earning and redeem discounts.')

@section('content')
    <div class="container">
        <div class="card">
            <h3>Global Point Earning Rate</h3>
            <p>Example: if amount per point is 100, customer earns 1 point for every 100 spent.</p>

            <form method="POST" action="{{ route('admin.loyalty-points.setting.update') }}">
                @csrf
                <div class="form-group">
                    <label>Amount Per 1 Point</label>
                    <input type="number" step="0.01" min="0.01" name="amount_per_point" value="{{ old('amount_per_point', $setting->amount_per_point) }}" class="form-control">
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
            <h3>Create Global Redeem Rule</h3>
            <p>Example: 100 points gives 5% discount.</p>

            <form method="POST" action="{{ route('admin.loyalty-points.rules.store') }}">
                @csrf
                <div class="form-grid" style="margin-bottom: 18px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Points Required</label>
                        <input type="number" min="1" name="points_required" value="{{ old('points_required') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Discount Percent</label>
                        <input type="number" step="0.01" min="0.01" max="100" name="discount_percent" value="{{ old('discount_percent') }}" class="form-control">
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
            <h3>Global Redeem Rules</h3>

            @forelse($rules as $rule)
                <form method="POST" action="{{ route('admin.loyalty-points.rules.update', $rule) }}" style="border:1px solid #ddd; padding:12px; border-radius:8px; margin-bottom:12px;">
                    @csrf
                    @method('PUT')
                    <div class="form-grid" style="margin-bottom: 18px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Points Required</label>
                            <input type="number" min="1" name="points_required" value="{{ $rule->points_required }}" class="form-control">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Discount Percent</label>
                            <input type="number" step="0.01" min="0.01" max="100" name="discount_percent" value="{{ $rule->discount_percent }}" class="form-control">
                        </div>
                    </div>

                    <label style="display:block; margin:12px 0;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $rule->is_active ? 'checked' : '' }}>
                        Active
                    </label>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.loyalty-points.rules.destroy', $rule) }}" onsubmit="return confirm('Delete this rule?')" style="margin-top:-8px; margin-bottom:16px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Rule</button>
                </form>
            @empty
                <p>No global redeem rules yet.</p>
            @endforelse
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Report Seller</h2>
            <p>Submit a report for marketplace seller behavior.</p>
        </div>

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="marketplace-product-card marketplace-product-body">
            <h3>Seller: {{ $seller->name }}</h3>

            <form method="POST" action="{{ route('customer.marketplace.sellers.report', $seller) }}" class="marketplace-form-card">
                @csrf

                <label>Reason</label>
                <textarea name="reason" required placeholder="Write the reason for reporting this seller...">{{ old('reason') }}</textarea>

                <button type="submit" class="marketplace-danger-btn">Submit Report</button>
            </form>
        </div>
    </section>
</div>
@endsection
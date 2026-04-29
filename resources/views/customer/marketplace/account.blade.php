@extends('layouts.app')

@section('content')
<div class="customer-dashboard-page fade-up">
    <section class="dashboard-action-box">
        <div class="dashboard-action-box__header">
            <h2>Marketplace Account</h2>
            <p>Activate and manage your marketplace eligibility.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="dashboard-action-grid marketplace-actions-grid">
            <a href="{{ route('customer.marketplace.products.index') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Back to Marketplace</span>
                <span class="dashboard-action-btn__subtitle">Browse products</span>
            </a>

            <a href="{{ route('customer.marketplace.products.create') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">Sell a Product</span>
                <span class="dashboard-action-btn__subtitle">Create listing</span>
            </a>

            <a href="{{ route('customer.marketplace.products.my-products') }}" class="dashboard-action-btn">
                <span class="dashboard-action-btn__title">My Products</span>
                <span class="dashboard-action-btn__subtitle">Manage listings</span>
            </a>
        </div>
    </section>

    <section class="dashboard-action-box">
        <div class="marketplace-product-list">
            <div class="marketplace-product-card marketplace-product-body">
                <h3>Account Status</h3>

                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Balance:</strong> {{ number_format($user->balance, 2) }} Tk</p>

                @if($account && $account->is_eligible)
                    <p><strong>Status:</strong> <span class="marketplace-status">Active</span></p>
                    <p><strong>Paid Fee:</strong> {{ number_format($account->paid_fee, 2) }} Tk</p>
                    <p><strong>Activated At:</strong> {{ optional($account->paid_at)->format('d M Y, h:i A') }}</p>
                @else
                    <p><strong>Status:</strong> <span class="marketplace-status">Not Active</span></p>
                    <p><strong>Registration Fee:</strong> {{ number_format($setting->registration_fee ?? 0, 2) }} Tk</p>

                    <form method="POST" action="{{ route('customer.marketplace.account.pay') }}" class="marketplace-form-card">
                        @csrf
                        <button type="submit" class="marketplace-primary-btn">Pay Fee And Activate Marketplace</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
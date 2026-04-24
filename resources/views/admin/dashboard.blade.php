@extends('layouts.app')

@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Monitor users, storefront accounts, marketplace settings, and platform activity.')

@section('content')
<div class="page-shell fade-up">
    <section class="role-hero role-hero--admin">
        <div class="role-hero__content">
            <div class="role-hero__eyebrow">Admin Control Center</div>
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <p>Keep the platform healthy with quick access to users, storefronts, and marketplace configuration.</p>

            <div class="role-hero__stats">
                <div class="role-stat">
                    <span class="role-stat__label">Role</span>
                    <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Administration Shortcuts</h2>
                <p>Jump directly into the most important moderation and settings tools.</p>
            </div>
        </div>

        <div class="action-grid">
            <a href="{{ route('admin.users.index') }}" class="action-tile"><span class="action-tile__title">All Users</span><span class="action-tile__subtitle">Review every account</span></a>
            <a href="{{ route('admin.users.customers') }}" class="action-tile action-tile--alt"><span class="action-tile__title">Customers</span><span class="action-tile__subtitle">Filter customer accounts</span></a>
            <a href="{{ route('admin.users.merchants') }}" class="action-tile"><span class="action-tile__title">Merchants</span><span class="action-tile__subtitle">Check merchant records</span></a>
            <a href="{{ route('admin.users.storefronts') }}" class="action-tile action-tile--dark"><span class="action-tile__title">Storefronts</span><span class="action-tile__subtitle">Manage storefront users</span></a>
            <a href="{{ route('admin.users.banned') }}" class="action-tile"><span class="action-tile__title">Banned Users</span><span class="action-tile__subtitle">Review restricted accounts</span></a>
            <a href="{{ route('admin.marketplace.settings.edit') }}" class="action-tile action-tile--alt"><span class="action-tile__title">Marketplace Settings</span><span class="action-tile__subtitle">Configure registration fees</span></a>
            <a href="{{ route('admin.marketplace.users') }}" class="action-tile"><span class="action-tile__title">Marketplace Users</span><span class="action-tile__subtitle">Track enrolled accounts</span></a>
        </div>
    </section>
</div>
@endsection

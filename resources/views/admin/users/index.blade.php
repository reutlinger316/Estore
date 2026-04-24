@extends('layouts.app')

@section('page_title', 'Admin Users')
@section('page_subtitle', 'Filter and moderate users across all roles with quick status actions.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>
                    @if($role === 'customer') Customers
                    @elseif($role === 'merchant') Merchants
                    @elseif($role === 'storefront') Storefronts
                    @else All Users
                    @endif
                </h2>
                <p>Review balances, roles, account status, and related storefront records.</p>
            </div>
        </div>

        <div class="action-grid">
            <a href="{{ route('admin.users.index') }}" class="action-tile action-tile--dark"><span class="action-tile__title">All Users</span><span class="action-tile__subtitle">Full account list</span></a>
            <a href="{{ route('admin.users.customers') }}" class="action-tile"><span class="action-tile__title">Customers</span><span class="action-tile__subtitle">Buyer accounts</span></a>
            <a href="{{ route('admin.users.merchants') }}" class="action-tile action-tile--alt"><span class="action-tile__title">Merchants</span><span class="action-tile__subtitle">Seller accounts</span></a>
            <a href="{{ route('admin.users.storefronts') }}" class="action-tile"><span class="action-tile__title">Storefronts</span><span class="action-tile__subtitle">Branch accounts</span></a>
            <a href="{{ route('admin.users.banned') }}" class="action-tile action-tile--dark"><span class="action-tile__title">Banned Users</span><span class="action-tile__subtitle">Restricted accounts</span></a>
        </div>
    </section>

    @if($users->count())
        <div class="entity-grid">
            @foreach($users as $user)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $user->name }}</h3>
                            <p>{{ $user->email }}</p>
                        </div>
                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">{{ $user->is_active ? 'Active' : 'Banned' }}</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Role</span><strong>{{ ucfirst($user->role) }}</strong></div>
                        <div class="entity-row"><span>Balance</span><strong>{{ number_format($user->balance, 2) }}</strong></div>
                    </div>

                    <div class="entity-actions">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn {{ $user->is_active ? 'btn-danger-soft' : 'btn-secondary' }}">{{ $user->is_active ? 'Ban Account' : 'Activate Account' }}</button>
                        </form>

                        @if($user->role === 'merchant')
                            <a href="{{ route('admin.merchants.storefronts', $user) }}" class="btn btn-primary">View Storefronts</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No users found.</div>
    @endif
</div>
@endsection

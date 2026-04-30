@extends('layouts.app')

@section('page_title', 'Admin Users')
@section('page_subtitle', 'Filter, moderate, delete, inspect reports, and view user activity history.')

@section('content')
@php
    $currentRoute = request()->route()->getName();

    $title = match ($role ?? null) {
        'customer' => 'Customers',
        'merchant' => 'Merchants',
        'storefront' => 'Storefronts',
        'admin' => 'Admins',
        default => 'All Users',
    };
@endphp

<section class="panel">
    <div class="section-heading">
        <div>
            <h3>{{ $title }}</h3>
            <p>Review balances, roles, account status, report counts, and user activity.</p>
        </div>
    </div>

    <form method="GET" action="{{ route($currentRoute) }}" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        @if(!empty($role))
            <input type="hidden" name="role" value="{{ $role }}">
        @endif

        @if(!empty($status))
            <input type="hidden" name="status" value="{{ $status }}">
        @endif

        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by username, email, or role" style="flex:1; min-width:260px; padding:12px 14px; border-radius:14px; border:1px solid #d8e0ec;">

        <button type="submit" class="btn btn-primary">Search</button>

        @if(!empty($search))
            <a href="{{ route($currentRoute, array_filter(['role' => $role ?? null, 'status' => $status ?? null])) }}" class="btn btn-ghost">Clear</a>
        @endif
    </form>

    <div class="action-grid">
        <a href="{{ route('admin.users.index') }}" class="action-tile"><span class="action-tile__title">All Users</span><span class="action-tile__subtitle">Full account list</span></a>
        <a href="{{ route('admin.users.customers') }}" class="action-tile"><span class="action-tile__title">Customers</span><span class="action-tile__subtitle">Buyer accounts</span></a>
        <a href="{{ route('admin.users.merchants') }}" class="action-tile"><span class="action-tile__title">Merchants</span><span class="action-tile__subtitle">Seller accounts</span></a>
        <a href="{{ route('admin.users.storefronts') }}" class="action-tile"><span class="action-tile__title">Storefronts</span><span class="action-tile__subtitle">Branch accounts</span></a>
        <a href="{{ route('admin.users.banned') }}" class="action-tile"><span class="action-tile__title">Banned Users</span><span class="action-tile__subtitle">Restricted accounts</span></a>
    </div>
</section>

@if($users->count())
    <div class="card-grid">
        @foreach($users as $user)
            <article class="stat-card">
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                    <div>
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                    </div>

                    @if($user->status)
                        <span style="font-size:12px; font-weight:700; color:#047857; background:#d1fae5; padding:6px 10px; border-radius:999px;">Active</span>
                    @else
                        <span style="font-size:12px; font-weight:700; color:#dc2626; background:#fee2e2; padding:6px 10px; border-radius:999px;">Banned</span>
                    @endif
                </div>

                <div class="stat-row"><span>Role</span><strong>{{ ucfirst($user->role) }}</strong></div>
                <div class="stat-row"><span>Balance</span><strong>{{ number_format($user->balance ?? 0, 2) }}</strong></div>
                <div class="stat-row"><span>Reports Received</span><strong>{{ $user->reports_received_count ?? $user->reportsReceived()->count() }}</strong></div>
                <div class="stat-row"><span>Reports Made</span><strong>{{ $user->reports_made_count ?? $user->reportsMade()->count() }}</strong></div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost">View Activity</a>

                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf
                        @if(auth()->id() === $user->id)
                            <button type="button" class="btn btn-ghost" disabled>Current Admin</button>
                        @elseif($user->status)
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to ban this user?')">Ban</button>
                        @else
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to activate this user?')">Activate</button>
                        @endif
                    </form>

                    @if(auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user permanently? Related records with cascade delete will also be removed.')">Delete</button>
                        </form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@else
    <section class="panel"><p>No users found.</p></section>
@endif
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <p>Welcome, {{ auth()->user()->name }}</p>
        <p>Role: {{ auth()->user()->role }}</p>

        <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
            <a href="{{ route('admin.users.index') }}">
                <button type="button">Manage All Users</button>
            </a>

            <a href="{{ route('admin.users.customers') }}">
                <button type="button">View Customers</button>
            </a>

            <a href="{{ route('admin.users.merchants') }}">
                <button type="button">View Merchants</button>
            </a>

            <a href="{{ route('admin.users.storefronts') }}">
                <button type="button">View Storefronts</button>
            </a>

            <a href="{{ route('admin.users.banned') }}">
                <button type="button">Currently Banned Users</button>
            </a>

            <a href="{{ route('admin.marketplace.settings.edit') }}">
                <button type="button">Marketplace Settings</button>
            </a>
            <a href="{{ route('admin.marketplace.users') }}">
                <button>Marketplace Users</button>
            </a>
        </div>
    </div>
@endsection
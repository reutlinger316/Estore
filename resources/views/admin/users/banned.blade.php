@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Admin - Banned Users</h2>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.users.index') }}"><button>All Users</button></a>
            <a href="{{ route('admin.users.customers') }}"><button>Customers</button></a>
            <a href="{{ route('admin.users.merchants') }}"><button>Merchants</button></a>
            <a href="{{ route('admin.users.storefronts') }}"><button>Storefronts</button></a>
            <a href="{{ route('admin.users.banned') }}"><button>Banned Users</button></a>
        </div>

        @forelse($users as $user)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Status:</strong> Banned</p>

                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    <button type="submit">Activate Account</button>
                </form>
            </div>
        @empty
            <p>No banned users found.</p>
        @endforelse
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Admin - Users</h2>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.users.index') }}"><button>All Users</button></a>
            <a href="{{ route('admin.users.customers') }}"><button>Customers</button></a>
            <a href="{{ route('admin.users.merchants') }}"><button>Merchants</button></a>
            <a href="{{ route('admin.users.storefronts') }}"><button>Storefronts</button></a>
            <a href="{{ route('admin.users.banned') }}"><button>Banned Users</button></a>
        </div>

        @if($role === 'customer')
            <h3>Customers</h3>
        @elseif($role === 'merchant')
            <h3>Merchants</h3>
        @elseif($role === 'storefront')
            <h3>Storefronts</h3>
        @else
            <h3>All Users</h3>
        @endif

        @forelse($users as $user)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Banned' }}</p>
                <p><strong>Balance:</strong> {{ number_format($user->balance, 2) }}</p>

                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display:inline-block;">
                    @csrf
                    <button type="submit">
                        {{ $user->is_active ? 'Ban Account' : 'Activate Account' }}
                    </button>
                </form>

                @if($user->role === 'merchant')
                    <a href="{{ route('admin.merchants.storefronts', $user) }}">
                        <button type="button">View StoreFronts</button>
                    </a>
                @endif
            </div>
        @empty
            <p>No users found.</p>
        @endforelse
    </div>
@endsection
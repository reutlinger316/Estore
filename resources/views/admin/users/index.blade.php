@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Manage Users</h2>

        <form method="GET" action="{{ route('admin.users.index') }}" style="max-width: 700px; margin-bottom: 1rem;">
            <div class="mb-3">
                <label>Search users</label>
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name, email, or role"
                    value="{{ request('search') }}"
                >
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Search</button>

                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Clear</a>
                @endif
            </div>
        </form>

        <div class="list-block">
            @forelse($users as $user)
                <div class="card">
                    <h3>{{ $user->name }}</h3>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Status:</strong> {{ $user->status ? 'Active' : 'Inactive' }}</p>

                    <div class="actions">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ $user->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No users found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

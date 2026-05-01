@extends('layouts.app')

@section('page_title', 'Banned Users')
@section('page_subtitle', 'Review restricted accounts, reports, and activity before reactivation or deletion.')

@section('content')
<section class="panel">
    <div class="section-heading"><div><h3>Banned Users</h3><p>These users cannot access protected areas until reactivated.</p></div></div>

    <form method="GET" action="{{ route('admin.users.banned') }}" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by username, email, or role" class="form-control" style="flex:1; min-width:260px;">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(!empty($search))<a href="{{ route('admin.users.banned') }}" class="btn btn-ghost">Clear</a>@endif
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">All Users</a>
    </form>
</section>

@if($users->count())
    <div class="card-grid">
        @foreach($users as $user)
            <article class="stat-card">
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                    <div><h3>{{ $user->name }}</h3><p>{{ $user->email }}</p></div>
                    <span class="badge badge-danger">Banned</span>
                </div>

                <div class="stat-row"><span>Role</span><strong>{{ ucfirst($user->role) }}</strong></div>
                <div class="stat-row"><span>Balance</span><strong>{{ number_format($user->balance ?? 0, 2) }}</strong></div>
                <div class="stat-row"><span>Reports Received</span><strong>{{ $user->reports_received_count ?? $user->reportsReceived()->count() }}</strong></div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost">View Activity</a>
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to activate this user?')">Activate</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user permanently?')">Delete</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@else
    <section class="panel"><p>No banned users found.</p></section>
@endif
@endsection

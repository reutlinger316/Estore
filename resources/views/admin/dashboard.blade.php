@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="welcome">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <strong>{{ auth()->user()->name }}</strong></p>
            <p>Role: {{ auth()->user()->role }}</p>
        </div>

        <div class="actions" style="justify-content:center;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Manage Users</a>
        </div>
    </div>
@endsection

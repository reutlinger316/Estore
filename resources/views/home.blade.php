@extends('layouts.app')

@section('content')
    <div class="welcome">
        <h1>Welcome to E-Store</h1>
        <p>This is the online marketplace system.</p>

        <div class="actions" style="justify-content:center;">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
    </div>
@endsection

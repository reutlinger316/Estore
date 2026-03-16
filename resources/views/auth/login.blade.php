@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title text-center">Login</h2>

        <form method="POST" action="{{ route('login.post') }}" class="ms-auto me-auto mt-3" style="max-width: 500px;">
            @csrf

            <div class="mb-3">
                <label>Email address</label>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
@endsection
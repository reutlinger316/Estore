@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card fade-up">
        <div class="auth-badge">Welcome Back</div>

        <h2 class="auth-title">Login to your account</h2>
        <p class="auth-subtitle">
            Access your dashboard, manage your orders, and continue your marketplace journey.
        </p>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control"
                    placeholder="Enter your email"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary auth-submit">Login</button>
        </form>

        <div class="auth-divider"><span>New here?</span></div>

        <a href="{{ route('register') }}" class="btn btn-secondary auth-submit">Create Account</a>
    </div>
</div>
@endsection
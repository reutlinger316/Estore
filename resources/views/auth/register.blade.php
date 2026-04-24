@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card fade-up">
        <div class="auth-badge auth-badge--alt">Get Started</div>

        <h2 class="auth-title">Create your account</h2>
        <p class="auth-subtitle">
            Join E-Store and start exploring shops, managing orders, and growing your store experience.
        </p>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control"
                    placeholder="Enter your full name"
                    required
                    autofocus
                >
            </div>

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
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Create a password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm your password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary auth-submit">Register</button>
        </form>

        <div class="auth-divider"><span>Already have an account?</span></div>

        <a href="{{ route('login') }}" class="btn btn-ghost auth-submit">Back to Login</a>
    </div>
</div>
@endsection
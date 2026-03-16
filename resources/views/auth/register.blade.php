@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title text-center">Register</h2>

        <form method="POST" action="{{ route('register.post') }}" class="ms-auto me-auto mt-3" style="max-width: 500px;">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Email address</label>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm Password">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role">
                    <option value="customer">Customer</option>
                    <option value="merchant">Merchant</option>
                    <option value="storefront">StoreFront</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
@endsection
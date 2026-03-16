<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">E-Store</a>

        <ul class="navbar-nav">
            <li><a class="nav-link active" href="{{ route('home') }}">Home</a></li>

            @auth
                @php
                    $previous = url()->previous();
                @endphp

                <li>
                    <a class="nav-link"
                       href="{{ $previous !== url()->current() ? $previous : url('/') }}">
                        Back
                    </a>
                </li>

                @if(auth()->user()->role === 'customer')
                    <li><span class="balance-badge">Balance: {{ number_format(auth()->user()->balance, 2) }}</span></li>
                @endif

                <li>
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            @else
                <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>

<div class="page">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>
